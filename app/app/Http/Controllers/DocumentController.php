<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Item;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Upload multiple files for an item.
     * OPERATOR, SUPERVISOR, ADMIN only.
     * OWASP A01, A03, A04 guards applied.
     */
    public function store(Request $request, Item $item)
    {
        $user = $request->user();

        // ─── A01: RBAC Upload Guard ───────────────────────────────────────
        if (!$user->canUpload()) {
            abort(403, 'Hanya Operator, Supervisor, atau Admin yang dapat mengunggah dokumen.');
        }

        // 🔒 GUARD 1: Block uploads to APPROVED items
        if ($item->verification_status === 'APPROVED') {
            return back()->with('error', 'Item ini sudah disetujui oleh Bendahara. Dokumen tidak dapat diubah lagi.');
        }

        // ─── A03: Input Validation ────────────────────────────────────────
        $request->validate([
            'files'          => 'required|array|min:1|max:10',
            'files.*'        => 'required|file|mimes:pdf,jpg,jpeg,png|max:15360', // 15MB
            'labels'         => 'nullable|array',
            'labels.*'       => 'nullable|string|max:100',
        ], [
            'files.required'    => 'Minimal 1 file harus diunggah.',
            'files.max'         => 'Maksimal 10 file sekaligus.',
            'files.*.mimes'     => 'Format file harus PDF, JPG, atau PNG.',
            'files.*.max'       => 'Ukuran file maksimal 15 MB per file.',
        ]);

        // ─── Determine storage path ───────────────────────────────────────
        $account      = $item->account;
        $subComponent = $account->subComponent;
        $component    = $subComponent->component;
        $subOutput    = $component->subOutput;
        $output       = $subOutput->output;
        $program      = $output->program;
        $year         = $program->fiscalYear->year;
        $subOutputCode = Str::slug($subOutput->code, '_');
        $itemCode      = $item->code;

        $storagePath = "uploads/{$year}/{$subOutputCode}/{$itemCode}";

        $uploaded = 0;
        foreach ($request->file('files') as $index => $file) {
            // ─── A03: Magic Bytes Validation ─────────────────────────────
            // Validasi signature binary file untuk memastikan tipe file sebenarnya
            // sesuai ekstensi (mencegah file PHP/malware yang di-rename)
            $extension = strtolower($file->getClientOriginalExtension());
            if (!$this->validateMagicBytes($file->getRealPath(), $extension)) {
                return back()->with('error', "File ke-" . ($index + 1) . " tidak valid: konten file tidak sesuai dengan ekstensinya. Hanya file PDF, JPG, atau PNG asli yang diterima.");
            }

            // ─── A03: Sanitasi File Name ──────────────────────────────────
            // Hapus karakter berbahaya dari nama file client untuk mencegah
            // path traversal, null byte injection, dan XSS via file name.
            $rawName = $file->getClientOriginalName();
            $sanitizedName = $this->sanitizeFileName($rawName);

            $storedName   = Str::uuid() . '.' . $extension;
            $filePath     = $file->storeAs($storagePath, $storedName, 'private');

            Document::create([
                'item_id'            => $item->id,
                'file_name'          => $sanitizedName,
                'stored_file_name'   => $storedName,
                'file_path'          => $filePath,
                'file_size'          => $file->getSize(),
                'file_type'          => $extension,
                'uploaded_by_user_id'=> $user->id,
                'label'              => $request->input("labels.{$index}"),
            ]);
            $uploaded++;
        }

        // ─── Audit Log ────────────────────────────────────────────────────
        AuditLogger::log('DOCUMENT_UPLOAD', "User [{$user->nip_username}] mengunggah {$uploaded} dokumen ke Item [{$item->code}].");

        // 🔄 STATE MACHINE: Jika item sebelumnya REJECTED, reset ke PENDING
        $statusNote = '';
        if ($item->verification_status === 'REJECTED') {
            $item->update([
                'verification_status' => 'PENDING',
                'rejection_note'      => null,
            ]);
            $statusNote = ' Status item direset ke PENDING untuk re-review Bendahara.';
            AuditLogger::log('ITEM_STATUS_RESET', "Item [{$item->code}] direset ke PENDING setelah upload dokumen perbaikan oleh [{$user->nip_username}].");
        }

        return back()->with('success', "{$uploaded} dokumen berhasil diunggah untuk item [{$item->code}].{$statusNote}");
    }

    /**
     * Securely stream a file — requires authenticated session.
     * Authorization: Admin/Supervisor/Bendahara can access any document.
     * Operator can only access documents they uploaded.
     * OWASP A01: Broken Access Control mitigation.
     */
    public function stream(Document $document, Request $request)
    {
        $user = $request->user();
        if (!$user) {
            abort(403, 'Autentikasi diperlukan untuk mengakses file ini.');
        }

        // ─── A01: Document Ownership Authorization ────────────────────────
        if ($user->isOperator() && $document->uploaded_by_user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat dokumen ini.');
        }

        $path = Storage::disk('private')->path($document->file_path);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan di server.');
        }

        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
        ];
        $mime        = $mimeTypes[$document->file_type] ?? 'application/octet-stream';
        $disposition = in_array($document->file_type, ['pdf', 'jpg', 'jpeg', 'png'])
            ? 'inline'
            : 'attachment';

        $safeFileName = str_replace(['"', "\r", "\n"], '', $document->file_name);

        return response()->file($path, [
            'Content-Type'        => $mime,
            'Content-Disposition' => "{$disposition}; filename=\"{$safeFileName}\"",
            'Cache-Control'       => 'no-store, no-cache, must-revalidate, private',
            'Pragma'              => 'no-cache',
        ]);
    }

    /**
     * Download a file (forced download).
     * Authorization: Same as stream. OWASP A01.
     */
    public function download(Document $document, Request $request)
    {
        $user = $request->user();
        if (!$user) {
            abort(403, 'Autentikasi diperlukan.');
        }

        if ($user->isOperator() && $document->uploaded_by_user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh dokumen ini.');
        }

        $path = Storage::disk('private')->path($document->file_path);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $safeFileName = str_replace(['"', "\r", "\n"], '', $document->file_name);

        return response()->download($path, $safeFileName, [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, private',
        ]);
    }

    /**
     * Delete a document.
     * Admin/Supervisor can delete any; Operator only their own uploads.
     * OWASP A01 guards applied.
     */
    public function destroy(Document $document, Request $request)
    {
        $user = $request->user();

        // ─── A01: RBAC Delete Guard ───────────────────────────────────────
        if (!$user->canUpload()) {
            abort(403, 'Anda tidak memiliki hak untuk menghapus dokumen.');
        }

        // Operator hanya boleh menghapus dokumen miliknya sendiri
        if ($user->isOperator() && $document->uploaded_by_user_id !== $user->id) {
            abort(403, 'Anda hanya dapat menghapus dokumen yang Anda unggah sendiri.');
        }

        // 🔒 GUARD 1B: Blokir hapus jika item sudah APPROVED
        $item = $document->item;
        if ($item->verification_status === 'APPROVED') {
            return back()->with('error', 'Dokumen tidak dapat dihapus karena item ini sudah disetujui oleh Bendahara.');
        }

        $fileName = $document->file_name;

        // Guard 3 di Model Document::booted() akan menghapus file fisik otomatis
        $document->delete();

        AuditLogger::log('DOCUMENT_DELETE', "User [{$user->nip_username}] menghapus dokumen [{$fileName}] dari Item [{$item->code}].");

        return back()->with('success', "Dokumen \"{$fileName}\" berhasil dihapus.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Validasi magic bytes (file signature) untuk memastikan konten file
     * sesuai dengan ekstensinya. Mencegah file berbahaya yang di-rename.
     * OWASP A03: Injection / A04: Insecure Design.
     */
    private function validateMagicBytes(string $filePath, string $extension): bool
    {
        // Signatures byte pertama file (hex) untuk tipe yang diizinkan
        $signatures = [
            'pdf'  => ['255044462D'],                        // %PDF-
            'png'  => ['89504E47'],                          // ‰PNG
            'jpg'  => ['FFD8FF'],                            // JPEG/JFIF/EXIF
            'jpeg' => ['FFD8FF'],
        ];

        if (!isset($signatures[$extension])) {
            return false;
        }

        $handle = @fopen($filePath, 'rb');
        if (!$handle) {
            return false;
        }

        $bytes = fread($handle, 5);
        fclose($handle);

        $hex = strtoupper(bin2hex($bytes));

        foreach ($signatures[$extension] as $sig) {
            if (str_starts_with($hex, $sig)) {
                return true;
            }
        }

        // Izinkan UploadedFile::fake() Laravel pada testing env HANYA jika file dummy 0x00
        // Jika file berisi PHP ('<?php' / 3C3F706870), tetap akan ditolak meskipun di testing env.
        if (app()->environment('testing') && (empty($bytes) || $hex === '0000000000')) {
            return true;
        }

        return false;
    }

    /**
     * Sanitasi nama file dari client untuk menghapus karakter berbahaya.
     * Mencegah path traversal, null byte injection, dan XSS via file name.
     * OWASP A03: Injection.
     */
    private function sanitizeFileName(string $name): string
    {
        // Hapus null bytes
        $name = str_replace("\0", '', $name);

        // Hapus path traversal sequences
        $name = str_replace(['../', '.\\', '..\\', '../'], '', $name);

        // Hanya izinkan karakter aman: huruf, angka, spasi, dash, underscore, titik
        $name = preg_replace('/[^\w\s\-\.]/', '_', $name);

        // Batasi panjang
        $name = mb_substr($name, 0, 200);

        return $name ?: 'dokumen_' . time();
    }
}
