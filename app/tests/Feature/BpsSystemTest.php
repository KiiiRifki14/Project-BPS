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
        $this->assertFalse($doc1->is_checked);
        Storage::disk('private')->assertExists($doc1->file_path);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ALUR VERIFIKASI BENDAHARA & CHECKLIST INTERAKTIF
    // ─────────────────────────────────────────────────────────────────────────

    public function test_bendahara_can_toggle_document_check_and_approve_item(): void
    {
        Storage::fake('private');

        $bendahara = User::where('role', 'BENDAHARA')->first();
        $operator  = User::where('role', 'OPERATOR')->first();
        $item      = Item::where('code', '001366')->first();

        // 1. Operator upload 1 dokumen
        $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('spj.pdf', 200, 'application/pdf')],
            'labels' => ['SPJ Honor'],
        ]);

        $doc = $item->fresh()->documents()->first();
        $this->assertFalse($doc->is_checked);

        // 2. Bendahara centang dokumen via AJAX PATCH /documents/{doc}/check
        $checkResponse = $this->actingAs($bendahara)->patchJson(route('documents.check', $doc), [
            'is_checked' => true,
        ]);

        $checkResponse->assertOk();
        $checkResponse->assertJson(['success' => true, 'is_checked' => true, 'can_approve' => true]);
        $this->assertTrue($doc->fresh()->is_checked);

        // 3. Bendahara setujui pencairan
        $verifyResponse = $this->actingAs($bendahara)->patch(route('items.verify', $item), [
            'action' => 'APPROVED',
        ]);

        $verifyResponse->assertRedirect();
        $this->assertEquals('APPROVED', $item->fresh()->verification_status);
    }

    public function test_guard2_revisi_bendahara_cannot_approve_if_unchecked_documents_exist(): void
    {
        Storage::fake('private');

        $bendahara = User::where('role', 'BENDAHARA')->first();
        $operator  = User::where('role', 'OPERATOR')->first();
        $item      = Item::where('code', '001366')->first();

        // Upload 1 dokumen tapi BELUM dicentang
        $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('spj_unchecked.pdf', 200, 'application/pdf')],
            'labels' => ['SPJ Honor'],
        ]);

        // Coba approve tanpa mencentang → HARUS diblokir Guard 2 REVISI
        $response = $this->actingAs($bendahara)->patch(route('items.verify', $item), [
            'action' => 'APPROVED',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals('PENDING', $item->fresh()->verification_status);
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
        $this->assertEquals('REJECTED',                                     $item->fresh()->verification_status);
        $this->assertEquals('Kuitansi belum ditandatangani oleh penerima.', $item->fresh()->rejection_note);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GUARD 4 — KEPEMILIKAN HAPUS DOKUMEN OPERATOR
    // ─────────────────────────────────────────────────────────────────────────

    public function test_guard4_operator_cannot_delete_other_operators_document(): void
    {
        Storage::fake('private');

        $operator1 = User::where('role', 'OPERATOR')->first();
        $operator2 = User::create([
            'name'         => 'Operator Dua',
            'nip_username' => 'operator2',
            'email'        => 'op2@bps.go.id',
            'password'     => bcrypt('password'),
            'role'         => 'OPERATOR',
        ]);

        $item = Item::where('code', '001366')->first();

        // Operator 1 upload dokumen A
        $this->actingAs($operator1)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('doc_op1.pdf', 100, 'application/pdf')],
            'labels' => ['Doc Op 1'],
        ]);
        $doc = $item->fresh()->documents()->first();

        // Operator 2 mencoba menghapus dokumen milik Operator 1 → HARUS abort 403 (Guard 4)
        $response = $this->actingAs($operator2)->delete(route('documents.destroy', $doc));
        $response->assertStatus(403);
        $this->assertDatabaseHas('documents', ['id' => $doc->id]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GUARD 5 — RESET CHECKLIST DOKUMEN SAAT REJECTED & RE-UPLOAD
    // ─────────────────────────────────────────────────────────────────────────

    public function test_guard5_reset_checklist_on_rejected_and_reupload(): void
    {
        Storage::fake('private');

        $bendahara = User::where('role', 'BENDAHARA')->first();
        $operator  = User::where('role', 'OPERATOR')->first();
        $item      = Item::where('code', '001366')->first();

        // Upload & Bendahara centang dokumen 1
        $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('draft.pdf', 100, 'application/pdf')],
            'labels' => ['Draft'],
        ]);
        $doc = $item->fresh()->documents()->first();
        $this->actingAs($bendahara)->patchJson(route('documents.check', $doc), ['is_checked' => true]);
        $this->assertTrue($doc->fresh()->is_checked);

        // Bendahara melakukan REJECTED → Guard 5 mereset is_checked ke false
        $this->actingAs($bendahara)->patch(route('items.verify', $item), [
            'action'         => 'REJECTED',
            'rejection_note' => 'Perlu revisi berkas.',
        ]);

        $this->assertFalse($doc->fresh()->is_checked);
        $this->assertNull($doc->fresh()->checked_by_user_id);
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

        $doc = $item->fresh()->documents()->first();
        $this->actingAs($bendahara)->patchJson(route('documents.check', $doc), ['is_checked' => true]);
        $this->actingAs($bendahara)->patch(route('items.verify', $item), ['action' => 'APPROVED']);
        $this->assertEquals('APPROVED', $item->fresh()->verification_status);

        // Coba upload lagi → harus diblokir Guard 1
        $response = $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('extra.pdf', 100, 'application/pdf')],
            'labels' => ['Extra'],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertCount(1, $item->fresh()->documents);
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
    // ROUTE RBAC — OPERATOR TIDAK BISA AKSES VERIFY
    // ─────────────────────────────────────────────────────────────────────────

    public function test_operator_cannot_access_verify_route(): void
    {
        Storage::fake('private');

        $operator = User::where('role', 'OPERATOR')->first();
        $item     = Item::where('code', '001366')->first();

        $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('spj.pdf', 100, 'application/pdf')],
            'labels' => ['SPJ'],
        ]);

        $response = $this->actingAs($operator)->patch(route('items.verify', $item), [
            'action' => 'APPROVED',
        ]);

        $response->assertStatus(403);
        $this->assertEquals('PENDING', $item->fresh()->verification_status);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // NEW FEATURE TESTS — ZIP DOWNLOAD, CSV EXPORT & ACTIVITY LOGS
    // ─────────────────────────────────────────────────────────────────────────

    public function test_user_can_download_item_documents_as_zip(): void
    {
        if (!class_exists(\ZipArchive::class)) {
            $this->markTestSkipped('Ekstensi PHP ZipArchive tidak aktif pada PHP CLI lingkungan pengujian.');
        }

        Storage::fake('private');

        $operator = User::where('role', 'OPERATOR')->first();
        $item     = Item::where('code', '001366')->first();

        $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('berkas1.pdf', 100, 'application/pdf')],
            'labels' => ['Berkas 1'],
        ]);

        $response = $this->actingAs($operator)->get(route('items.download-zip', $item));
        $response->assertOk();
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'zip'));
    }

    public function test_user_can_export_report_as_csv(): void
    {
        $admin = User::where('role', 'ADMIN')->first();

        $response = $this->actingAs($admin)->get(route('reports.export', ['year' => 2026, 'month' => 8]));
        $response->assertOk();
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
    }

    public function test_activity_log_recorded_on_actions(): void
    {
        Storage::fake('private');

        $operator  = User::where('role', 'OPERATOR')->first();
        $bendahara = User::where('role', 'BENDAHARA')->first();
        $item      = Item::where('code', '001366')->first();

        // 1. Upload logs activity
        $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('dokumen_audit.pdf', 100, 'application/pdf')],
            'labels' => ['Audit Doc'],
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'item_id' => $item->id,
            'user_id' => $operator->id,
            'action'  => 'UPLOAD_DOCUMENT',
        ]);

        // 2. Check document logs activity
        $doc = $item->fresh()->documents()->first();
        $this->actingAs($bendahara)->patchJson(route('documents.check', $doc), ['is_checked' => true]);

        $this->assertDatabaseHas('activity_logs', [
            'item_id' => $item->id,
            'user_id' => $bendahara->id,
            'action'  => 'CHECK_DOCUMENT',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SECURITY TESTS — UPLOAD VALIDATION, DIRECT ACCESS & IDOR
    // ─────────────────────────────────────────────────────────────────────────

    public function test_security_upload_blocks_php_and_executable_files(): void
    {
        Storage::fake('private');

        $operator = User::where('role', 'OPERATOR')->first();
        $item     = Item::where('code', '001366')->first();

        // 1. Coba upload file .php
        $responsePhp = $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('exploit.php', 50, 'application/x-php')],
            'labels' => ['Exploit Script'],
        ]);
        $responsePhp->assertSessionHasErrors('files.0');
        $this->assertCount(0, $item->fresh()->documents);

        // 2. Coba upload file .phtml
        $responsePhtml = $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('shell.phtml', 50, 'text/html')],
            'labels' => ['Phtml Shell'],
        ]);
        $responsePhtml->assertSessionHasErrors('files.0');
        $this->assertCount(0, $item->fresh()->documents);

        // 3. Coba upload file .exe
        $responseExe = $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('malware.exe', 50, 'application/x-msdownload')],
            'labels' => ['Malware Exe'],
        ]);
        $responseExe->assertSessionHasErrors('files.0');
        $this->assertCount(0, $item->fresh()->documents);
    }

    public function test_security_unauthenticated_cannot_download_or_stream_document(): void
    {
        Storage::fake('private');

        $operator = User::where('role', 'OPERATOR')->first();
        $item     = Item::where('code', '001366')->first();

        $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [UploadedFile::fake()->create('confidential_spj.pdf', 100, 'application/pdf')],
            'labels' => ['Confidential SPJ'],
        ]);

        $doc = $item->fresh()->documents()->first();
        $this->assertNotNull($doc);

        // Logout dan coba akses stream / preview dokumen tanpa login
        auth()->logout();
        $responseStream = $this->get(route('documents.stream', $doc));
        $this->assertTrue(in_array($responseStream->status(), [302, 401, 403]));

        // Coba akses download dokumen tanpa login
        $responseDownload = $this->get(route('documents.download', $doc));
        $this->assertTrue(in_array($responseDownload->status(), [302, 401, 403]));
    }

    public function test_security_direct_storage_or_uploads_path_is_blocked(): void
    {
        // URL akses langsung ke direktori file private harus 404
        $responseStorage = $this->get('/storage/');
        $this->assertEquals(404, $responseStorage->status());

        $responseUploads = $this->get('/uploads/');
        $this->assertEquals(404, $responseUploads->status());
    }
}
