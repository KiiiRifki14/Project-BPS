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

    // ─────────────────────────────────────────────────────────────────────────
    // SEEDER & UPLOAD DASAR
    // ─────────────────────────────────────────────────────────────────────────

    public function test_seeder_populates_required_users_and_pok_hierarchy(): void
    {
        $this->assertDatabaseHas('users', ['nip_username' => 'admin',      'role' => 'ADMIN']);
        $this->assertDatabaseHas('users', ['nip_username' => 'supervisor', 'role' => 'SUPERVISOR']);
        $this->assertDatabaseHas('users', ['nip_username' => 'operator',   'role' => 'OPERATOR']);
        $this->assertDatabaseHas('users', ['nip_username' => 'bendahara',  'role' => 'BENDAHARA']);

        $this->assertDatabaseHas('items', ['code' => '001366']);
        $this->assertDatabaseHas('items', ['code' => '001211']);
        $this->assertDatabaseHas('items', ['code' => '001351']);
    }

    public function test_operator_can_upload_multi_file_document_to_item(): void
    {
        Storage::fake('private');

        $operator = User::where('role', 'OPERATOR')->first();
        $item     = Item::where('code', '001366')->first();

        $response = $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [
                UploadedFile::fake()->create('bapp_honor.pdf', 500, 'application/pdf'),
                UploadedFile::fake()->create('kuitansi.png',   300, 'image/png'),
            ],
            'labels' => ['BAPP Honor Sensus', 'Kuitansi Pembayaran'],
        ]);

        $response->assertRedirect();
        $this->assertCount(2, $item->fresh()->documents);

        $doc1 = $item->documents()->where('file_name', 'bapp_honor.pdf')->first();
        $this->assertNotNull($doc1);
        $this->assertEquals('BAPP Honor Sensus', $doc1->label);
        Storage::disk('private')->assertExists($doc1->file_path);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ALUR VERIFIKASI BENDAHARA
    // ─────────────────────────────────────────────────────────────────────────

    public function test_bendahara_can_verify_and_approve_item(): void
    {
        Storage::fake('private');

        $bendahara = User::where('role', 'BENDAHARA')->first();
        $operator  = User::where('role', 'OPERATOR')->first();
        $item      = Item::where('code', '001366')->first();

        // Upload 1 dokumen dulu (Guard 2 prerequisite)
        $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('spj.pdf', 200, 'application/pdf')],
            'labels' => ['SPJ Honor'],
        ]);

        $response = $this->actingAs($bendahara)->patch(route('items.verify', $item), [
            'action' => 'APPROVED',
        ]);

        $response->assertRedirect();
        $this->assertEquals('APPROVED', $item->fresh()->verification_status);
    }

    public function test_bendahara_rejection_requires_rejection_note(): void
    {
        Storage::fake('private');

        $bendahara = User::where('role', 'BENDAHARA')->first();
        $operator  = User::where('role', 'OPERATOR')->first();
        $item      = Item::where('code', '001366')->first();

        $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('kuitansi.pdf', 200, 'application/pdf')],
            'labels' => ['Kuitansi'],
        ]);

        $response = $this->actingAs($bendahara)->patch(route('items.verify', $item), [
            'action'         => 'REJECTED',
            'rejection_note' => 'Kuitansi belum ditandatangani oleh penerima.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('REJECTED',                                       $item->fresh()->verification_status);
        $this->assertEquals('Kuitansi belum ditandatangani oleh penerima.',   $item->fresh()->rejection_note);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GUARD 1 — LOCK DOKUMEN ITEM APPROVED
    // ─────────────────────────────────────────────────────────────────────────

    public function test_guard1_operator_cannot_upload_to_approved_item(): void
    {
        Storage::fake('private');

        $bendahara = User::where('role', 'BENDAHARA')->first();
        $operator  = User::where('role', 'OPERATOR')->first();
        $item      = Item::where('code', '001366')->first();

        $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('spj.pdf', 200, 'application/pdf')],
            'labels' => ['SPJ'],
        ]);
        $this->actingAs($bendahara)->patch(route('items.verify', $item), ['action' => 'APPROVED']);
        $this->assertEquals('APPROVED', $item->fresh()->verification_status);

        // Coba upload lagi → harus diblokir Guard 1
        $response = $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('extra.pdf', 100, 'application/pdf')],
            'labels' => ['Extra'],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertCount(1, $item->fresh()->documents); // tetap 1, tidak bertambah
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GUARD 2 — MIN 1 DOKUMEN SEBELUM APPROVED
    // ─────────────────────────────────────────────────────────────────────────

    public function test_guard2_bendahara_cannot_approve_item_with_zero_documents(): void
    {
        $bendahara = User::where('role', 'BENDAHARA')->first();
        $item      = Item::where('code', '001366')->first();

        $response = $this->actingAs($bendahara)->patch(route('items.verify', $item), [
            'action' => 'APPROVED',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals('PENDING', $item->fresh()->verification_status);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GUARD 3 — GARBAGE COLLECTION FILE FISIK
    // ─────────────────────────────────────────────────────────────────────────

    public function test_guard3_physical_file_deleted_on_document_destroy(): void
    {
        Storage::fake('private');

        $operator = User::where('role', 'OPERATOR')->first();
        $item     = Item::where('code', '001366')->first();

        $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('delete_me.pdf', 100, 'application/pdf')],
            'labels' => ['Test Hapus'],
        ]);

        $doc = $item->fresh()->documents()->first();
        $this->assertNotNull($doc);
        Storage::disk('private')->assertExists($doc->file_path);

        $response = $this->actingAs($operator)->delete(route('documents.destroy', $doc));
        $response->assertRedirect();

        Storage::disk('private')->assertMissing($doc->file_path);
        $this->assertDatabaseMissing('documents', ['id' => $doc->id]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STATE MACHINE — REJECTED → PENDING SAAT UPLOAD BARU (Diagram 5 ERD)
    // ─────────────────────────────────────────────────────────────────────────

    public function test_state_machine_upload_on_rejected_item_resets_to_pending(): void
    {
        Storage::fake('private');

        $bendahara = User::where('role', 'BENDAHARA')->first();
        $operator  = User::where('role', 'OPERATOR')->first();
        $item      = Item::where('code', '001366')->first();

        // Upload awal & reject
        $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('draft.pdf', 100, 'application/pdf')],
            'labels' => ['Draft'],
        ]);
        $this->actingAs($bendahara)->patch(route('items.verify', $item), [
            'action'         => 'REJECTED',
            'rejection_note' => 'Kuitansi belum ditandatangani.',
        ]);
        $this->assertEquals('REJECTED', $item->fresh()->verification_status);

        // Operator upload dokumen revisi → status HARUS kembali ke PENDING
        $response = $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('revisi.pdf', 120, 'application/pdf')],
            'labels' => ['Revisi Final'],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $freshItem = $item->fresh();
        $this->assertEquals('PENDING', $freshItem->verification_status);
        $this->assertNull($freshItem->rejection_note); // rejection_note harus terhapus
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ROUTE RBAC — OPERATOR TIDAK BISA AKSES VERIFY (Diagram 12 ERD)
    // ─────────────────────────────────────────────────────────────────────────

    public function test_operator_cannot_access_verify_route(): void
    {
        Storage::fake('private');

        $operator = User::where('role', 'OPERATOR')->first();
        $item     = Item::where('code', '001366')->first();

        // Upload 1 dokumen agar tidak terhalang Guard 2
        $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('spj.pdf', 100, 'application/pdf')],
            'labels' => ['SPJ'],
        ]);

        // PATCH verify → harus 403 karena Operator tidak ada di group role:BENDAHARA,ADMIN
        $response = $this->actingAs($operator)->patch(route('items.verify', $item), [
            'action' => 'APPROVED',
        ]);

        $response->assertStatus(403);
        $this->assertEquals('PENDING', $item->fresh()->verification_status);
    }
}
