<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BpsSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_seeder_populates_required_users_and_pok_hierarchy(): void
    {
        $this->assertDatabaseHas('users', ['nip_username' => 'admin', 'role' => 'ADMIN']);
        $this->assertDatabaseHas('users', ['nip_username' => 'supervisor', 'role' => 'SUPERVISOR']);
        $this->assertDatabaseHas('users', ['nip_username' => 'operator', 'role' => 'OPERATOR']);
        $this->assertDatabaseHas('users', ['nip_username' => 'bendahara', 'role' => 'BENDAHARA']);

        // Check MVP core items exist
        $this->assertDatabaseHas('items', ['code' => '001366']); // Honor petugas pendataan sensus
        $this->assertDatabaseHas('items', ['code' => '001211']); // Honor petugas lapangan sensus
        $this->assertDatabaseHas('items', ['code' => '001351']); // Paket Meeting Fullboard
    }

    public function test_operator_can_upload_multi_file_document_to_item(): void
    {
        Storage::fake('private');

        $operator = User::where('role', 'OPERATOR')->first();
        $item = Item::where('code', '001366')->first();

        $file1 = UploadedFile::fake()->create('bapp_honor.pdf', 500, 'application/pdf');
        $file2 = UploadedFile::fake()->create('kuitansi.png', 300, 'image/png');

        $response = $this->actingAs($operator)->post(route('documents.store', $item), [
            'files' => [$file1, $file2],
            'labels' => ['BAPP Honor Sensus', 'Kuitansi Pembayaran'],
        ]);

        $response->assertRedirect();
        $this->assertCount(2, $item->fresh()->documents);

        $doc1 = $item->documents()->where('file_name', 'bapp_honor.pdf')->first();
        $this->assertNotNull($doc1);
        $this->assertEquals('BAPP Honor Sensus', $doc1->label);
        Storage::disk('private')->assertExists($doc1->file_path);
    }

    public function test_bendahara_can_verify_and_approve_item(): void
    {
        $bendahara = User::where('role', 'BENDAHARA')->first();
        $item = Item::where('code', '001366')->first();

        $response = $this->actingAs($bendahara)->patch(route('items.verify', $item), [
            'verification_status' => 'APPROVED',
        ]);

        $response->assertRedirect();
        $this->assertEquals('APPROVED', $item->fresh()->verification_status);
    }

    public function test_bendahara_rejection_requires_rejection_note(): void
    {
        $bendahara = User::where('role', 'BENDAHARA')->first();
        $item = Item::where('code', '001366')->first();

        $response = $this->actingAs($bendahara)->patch(route('items.verify', $item), [
            'verification_status' => 'REJECTED',
            'rejection_note' => 'Kuitansi belum ditandatangani oleh penerima.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('REJECTED', $item->fresh()->verification_status);
        $this->assertEquals('Kuitansi belum ditandatangani oleh penerima.', $item->fresh()->rejection_note);
    }
}
