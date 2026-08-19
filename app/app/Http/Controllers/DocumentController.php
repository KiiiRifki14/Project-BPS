<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Upload multiple files for an item.
     * OPERATOR, SUPERVISOR, ADMIN only.
     */
    public function store(Request $request, Item $item)
    {
        $user = $request->user();

        if (!$user->canUpload()) {
            abort(403, 'Hanya Operator, Supervisor, atau Admin yang dapat mengunggah dokumen.');
        }

        // 🔒 GUARD 1: Block uploads to APPROVED items
        if ($item->verification_status === 'APPROVED') {
            return back()->with('error', 'Item ini sudah disetujui oleh Bendahara. Dokumen tidak dapat diubah lagi.');
        }

        $request->validate([
            'files'          => 'required|array|min:1',
            'files.*'        => 'required|file|mimes:pdf,jpg,jpeg,png|max:15360', // 15MB
            'labels'         => 'required|array',
            'labels.*'       => 'required|string|max:100',
        ], [
            'files.required'    => 'Minimal 1 file harus diunggah.',
            'files.*.mimes'     => 'Format file harus PDF, JPG, atau PNG.',
            'files.*.max'       => 'Ukuran file maksimal 15 MB per file.',
            'labels.required'   => 'Setiap berkas yang diunggah wajib dipilihkan label kategorinya.',
            'labels.*.required' => 'Setiap berkas yang diunggah wajib dipilihkan label kategorinya.',
        ]);

        // Determine storage path
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
            $originalName = $file->getClientOriginalName();
            $extension    = strtolower($file->getClientOriginalExtension());
            $storedName   = Str::uuid() . '.' . $extension;

            // Store in private disk
            $filePath = $file->storeAs($storagePath, $storedName, 'private');

            Document::create([
                'item_id'            => $item->id,
                'file_name'          => $originalName,
                'stored_file_name'   => $storedName,
                'file_path'          => $filePath,
                'file_size'          => $file->getSize(),
                'file_type'          => $extension,
                'uploaded_by_user_id'=> $user->id,
                'label'              => $request->input("labels.{$index}"),
            ]);
            $uploaded++;
        }

        // 🔄 STATE MACHINE: Jika item sebelumnya REJECTED, reset ke PENDING
        // agar Bendahara dapat melakukan re-review atas dokumen perbaikan.
        $statusNote = '';
        if ($item->verification_status === 'REJECTED') {
            $item->update([
                'verification_status' => 'PENDING',
                'rejection_note'      => null,
            ]);
            $statusNote = ' Status item direset ke PENDING untuk re-review Bendahara.';
        }

        return back()->with('success', "{$uploaded} dokumen berhasil diunggah untuk item [{$item->code}].{$statusNote}");
    }

    /**
     * Securely stream a file — requires authenticated session.
     * Supports both inline preview (PDF/image) and download.
     */
    public function stream(Document $document, Request $request)
    {
        // Auth check: must be logged in
        if (!$request->user()) {
            abort(403, 'Autentikasi diperlukan untuk mengakses file ini.');
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

        return response()->file($path, [
            'Content-Type'        => $mime,
            'Content-Disposition' => "{$disposition}; filename=\"{$document->file_name}\"",
        ]);
    }

    /**
     * Download a file (forced download).
     */
    public function download(Document $document, Request $request)
    {
        if (!$request->user()) {
            abort(403, 'Autentikasi diperlukan.');
        }

        $path = Storage::disk('private')->path($document->file_path);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($path, $document->file_name);
    }

    /**
     * Delete a document.
     * Admin/Supervisor can delete any; Operator only their own uploads.
     */
    public function destroy(Document $document, Request $request)
    {
        $user = $request->user();

        // 1️⃣ Cek hak akses upload/delete secara umum
        if (!$user->canUpload()) {
            abort(403, 'Anda tidak memiliki hak untuk menghapus dokumen.');
        }

        // 2️⃣ Operator hanya boleh menghapus dokumen miliknya sendiri
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

        return back()->with('success', "Dokumen \"{$fileName}\" berhasil dihapus.");
    }
}
