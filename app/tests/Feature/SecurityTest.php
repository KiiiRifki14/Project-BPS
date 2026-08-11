<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use Tests\TestCase;

/**
 * SecurityTest — Suite Pengujian Keamanan OWASP Top 10
 *
 * Menguji:
 * 1. Broken Access Control (Register disabled, Document ownership, Admin self-deletion)
 * 2. Security Headers (X-Frame-Options, X-Content-Type-Options, CSP, Referrer-Policy)
 * 3. Identification & Auth Failures (Login Throttling, Strong Password Policy)
 * 4. Security Logging (AuditLogger output to audit.log)
 * 5. Injection & File Upload Security (Magic Bytes Validation against PHP payloads)
 */
class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * OWASP A01: Broken Access Control
     * Rute /register publik HARUS dinonaktifkan (404/Not Found).
     */
    public function test_public_register_route_is_disabled(): void
    {
        $response = $this->get('/register');
        $response->assertNotFound();

        $postResponse = $this->post('/register', [
            'name' => 'Hacker',
            'nip_username' => 'hacker99',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);
        $postResponse->assertNotFound();
    }

    /**
     * OWASP A05: Security Misconfiguration
     * HTTP Security Headers HARUS disertakan dalam setiap respons web.
     */
    public function test_security_headers_are_present_on_web_requests(): void
    {
        $response = $this->get('/login');

        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->assertTrue($response->headers->has('Content-Security-Policy'));
    }

    /**
     * OWASP A01: Broken Access Control
     * Operator HANYA boleh mengakses (stream/download) dokumen miliknya sendiri.
     */
    public function test_document_stream_and_download_restricted_by_ownership_for_operator(): void
    {
        Storage::fake('private');

        $operator1 = User::where('role', 'OPERATOR')->first();
        $admin     = User::where('role', 'ADMIN')->first();
        $item      = Item::first();

        // Admin membuat dokumen
        $docAdmin = Document::create([
            'item_id'             => $item->id,
            'file_name'           => 'dokumen_admin.pdf',
            'stored_file_name'    => 'admin_file.pdf',
            'file_path'           => 'uploads/2026/test/admin_file.pdf',
            'file_size'           => 1024,
            'file_type'           => 'pdf',
            'uploaded_by_user_id' => $admin->id,
        ]);
        Storage::disk('private')->put($docAdmin->file_path, '%PDF-1.4 Fake PDF Content');

        // Operator1 mencoba streaming dokumen Admin → HARUS DITOLAK (403)
        $streamResponse = $this->actingAs($operator1)->get(route('documents.stream', $docAdmin));
        $streamResponse->assertForbidden();

        // Operator1 mencoba mengunduh dokumen Admin → HARUS DITOLAK (403)
        $downloadResponse = $this->actingAs($operator1)->get(route('documents.download', $docAdmin));
        $downloadResponse->assertForbidden();

        // Admin mencoba streaming dokumennya sendiri → BISA (200)
        $adminStreamResponse = $this->actingAs($admin)->get(route('documents.stream', $docAdmin));
        $adminStreamResponse->assertOk();
    }

    /**
     * OWASP A07: Identification and Authentication Failures
     * Password baru HARUS memenuhi standar keamanan (min 8 karakter, ada huruf besar/kecil & angka).
     */
    public function test_password_validation_enforces_strong_password_rules(): void
    {
        $admin = User::where('role', 'ADMIN')->first();

        // Password lemah (hanya 6 huruf kecil) → Harus GAGAL
        $responseWeak = $this->actingAs($admin)->post(route('users.store'), [
            'name'                  => 'User Baru',
            'nip_username'          => 'userbaru01',
            'role'                  => 'OPERATOR',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ]);
        $responseWeak->assertSessionHasErrors('password');

        // Password kuat (8+ karakter, mixed case + numbers) → Harus SUKSES
        $responseStrong = $this->actingAs($admin)->post(route('users.store'), [
            'name'                  => 'User Baru Valid',
            'nip_username'          => 'userbaru02',
            'role'                  => 'OPERATOR',
            'password'              => 'Subang2026!',
            'password_confirmation' => 'Subang2026!',
        ]);
        $responseStrong->assertRedirect();
        $this->assertDatabaseHas('users', ['nip_username' => 'userbaru02']);
    }

    /**
     * OWASP A03: Injection & Magic Bytes Validation
     * Skrip PHP berbahaya yang di-rename menjadi `.pdf` HARUS DITOLAK.
     */
    public function test_fake_php_file_disguised_as_pdf_is_rejected_by_magic_bytes(): void
    {
        Storage::fake('private');

        $operator = User::where('role', 'OPERATOR')->first();
        $item     = Item::first();

        // Buat file berisi skrip PHP tapi diberi nama `.pdf`
        $maliciousFile = UploadedFile::fake()->createWithContent('exploit.pdf', "<?php phpinfo(); ?>");

        $response = $this->actingAs($operator)->post(route('documents.store', $item), [
            'files'  => [$maliciousFile],
            'labels' => ['Spoofed PDF'],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertCount(0, $item->documents);
    }

    /**
     * OWASP A09: Security Logging & Monitoring
     * Aktivitas login HARUS dicatat di storage/logs/audit.log.
     */
    public function test_audit_log_is_written_on_successful_login(): void
    {
        $auditLogFile = storage_path('logs/audit.log');
        if (File::exists($auditLogFile)) {
            File::delete($auditLogFile);
        }

        $response = $this->post(route('login'), [
            'nip_username' => 'admin',
            'password'     => 'admin123',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticated();

        $this->assertFileExists($auditLogFile);
        $content = File::get($auditLogFile);
        $this->assertStringContainsString('LOGIN', $content);
        $this->assertStringContainsString('admin', $content);
    }

    /**
     * OWASP A01: Broken Access Control
     * Admin TIDAK BOLEH menghapus akunnya sendiri.
     */
    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = User::where('role', 'ADMIN')->first();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $admin));
        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}
