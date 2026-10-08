<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\FiscalYear;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FiscalYearCloneTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_supervisor_can_clone_pok_hierarchy_to_new_fiscal_year(): void
    {
        Storage::fake('private');

        $supervisor = User::where('role', 'SUPERVISOR')->first();
        $sourceFy   = FiscalYear::where('is_active', true)->first();

        // Add a document to item 001366 to ensure documents are NOT copied
        $item = Item::where('code', '001366')->first();
        $operator = User::where('role', 'OPERATOR')->first();
        $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('bapp.pdf', 100, 'application/pdf')],
            'labels' => ['BAPP'],
        ]);
        $this->assertEquals(1, $item->fresh()->documents()->count());

        $itemCountBefore = Item::whereHas('account.subComponent.component.subOutput.output.program', function ($q) use ($sourceFy) {
            $q->where('fiscal_year_id', $sourceFy->id);
        })->count();

        // Clone to 2027
        $response = $this->actingAs($supervisor)->post(route('master.fiscal-years.clone'), [
            'source_fiscal_year_id' => $sourceFy->id,
            'target_year'           => 2027,
            'copy_pagu'             => 1,
            'set_active'            => 1,
        ]);

        $response->assertSessionHas('success');

        // Target year 2027 exists and is active
        $targetFy = FiscalYear::where('year', 2027)->first();
        $this->assertNotNull($targetFy);
        $this->assertTrue($targetFy->is_active);

        // Previous source year is now inactive
        $this->assertFalse($sourceFy->fresh()->is_active);

        // Target year has the same number of items
        $newItemCount = Item::whereHas('account.subComponent.component.subOutput.output.program', function ($q) use ($targetFy) {
            $q->where('fiscal_year_id', $targetFy->id);
        })->count();
        $this->assertEquals($itemCountBefore, $newItemCount);

        // Cloned item 001366 exists in target year with matching pagu, fresh status PENDING, and ZERO documents
        $clonedItem = Item::where('code', '001366')
            ->whereHas('account.subComponent.component.subOutput.output.program', function ($q) use ($targetFy) {
                $q->where('fiscal_year_id', $targetFy->id);
            })->first();

        $this->assertNotNull($clonedItem);
        $this->assertEquals($item->pagu, $clonedItem->pagu);
        $this->assertEquals('PENDING', $clonedItem->verification_status);
        $this->assertEquals(0, $clonedItem->documents()->count(), 'SPJ documents must NOT be cloned to new fiscal year.');
    }

    public function test_cannot_clone_to_existing_year_with_programs(): void
    {
        $supervisor = User::where('role', 'SUPERVISOR')->first();
        $sourceFy   = FiscalYear::where('is_active', true)->first();

        // Attempt cloning to same year
        $responseSame = $this->actingAs($supervisor)->post(route('master.fiscal-years.clone'), [
            'source_fiscal_year_id' => $sourceFy->id,
            'target_year'           => $sourceFy->year,
            'copy_pagu'             => 1,
        ]);
        $responseSame->assertSessionHas('error');

        // Create 2028 with 1 program
        $fy2028 = FiscalYear::create(['year' => 2028, 'is_active' => false]);
        \App\Models\Program::create([
            'fiscal_year_id' => $fy2028->id,
            'code'           => 'TEST.01',
            'name'           => 'Program Uji',
        ]);

        $responseDuplicate = $this->actingAs($supervisor)->post(route('master.fiscal-years.clone'), [
            'source_fiscal_year_id' => $sourceFy->id,
            'target_year'           => 2028,
            'copy_pagu'             => 1,
        ]);
        $responseDuplicate->assertSessionHas('error');
    }

    public function test_operator_and_bendahara_cannot_access_clone_route(): void
    {
        $sourceFy = FiscalYear::where('is_active', true)->first();

        $operator = User::where('role', 'OPERATOR')->first();
        $this->actingAs($operator)->post(route('master.fiscal-years.clone'), [
            'source_fiscal_year_id' => $sourceFy->id,
            'target_year'           => 2027,
        ])->assertForbidden();

        $bendahara = User::where('role', 'BENDAHARA')->first();
        $this->actingAs($bendahara)->post(route('master.fiscal-years.clone'), [
            'source_fiscal_year_id' => $sourceFy->id,
            'target_year'           => 2027,
        ])->assertForbidden();
    }

    public function test_dashboard_renders_new_year_notice(): void
    {
        $supervisor = User::where('role', 'SUPERVISOR')->first();

        $response = $this->actingAs($supervisor)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('newYearNotice');
    }
}
