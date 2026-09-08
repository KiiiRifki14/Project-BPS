<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ItemController extends Controller
{
    /**
     * Display item detail with all documents.
     */
    public function show(Item $item)
    {
        $item->load([
            'documents.uploadedBy',
            'activityLogs.user',
            'account.subComponent.component.subOutput.output.program.fiscalYear',
        ]);

        $breadcrumb = $item->breadcrumb;

        return view('items.show', compact('item', 'breadcrumb'));
    }

    /**
     * Download all item documents as a single ZIP archive.
     */
    public function downloadZip(Item $item)
    {
        if (!class_exists(\ZipArchive::class)) {
            return back()->with('error', 'Gagal mengunduh: Ekstensi PHP ZipArchive tidak aktif pada server PHP.');
        }

        $documents = $item->documents;
        if ($documents->isEmpty()) {
            return back()->with('error', 'Tidak ada dokumen untuk diunduh pada item ini.');
        }

        $zip = new ZipArchive();
        $zipFileName = 'SPJ_Item_' . $item->code . '_' . date('Ymd_His') . '.zip';
        $tempDir = storage_path('app/private/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $zipPath = $tempDir . '/' . $zipFileName;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($documents as $index => $doc) {
                $absolutePath = storage_path('app/' . $doc->file_path);
                if (file_exists($absolutePath)) {
                    // Prepend index to filename if duplicate names exist
                    $entryName = ($index + 1) . '_' . $doc->file_name;
                    $zip->addFile($absolutePath, $entryName);
                }
            }
            $zip->close();
        } else {
            return back()->with('error', 'Gagal membuat berkas terkompresi ZIP.');
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Bendahara / Admin: Approve or Reject item.
     */
    public function verify(Request $request, Item $item)
    {
        $user = auth()->user();
        if (!$user->isBendahara() && !$user->isAdmin()) {
            abort(403, 'Akses ditolak: Hanya Bendahara Pengeluaran atau Admin yang berwenang melakukan verifikasi pencairan.');
        }

        $validated = $request->validate([
            'action'         => 'required|in:APPROVED,REJECTED',
            'rejection_note' => 'required_if:action,REJECTED|nullable|string|max:500',
        ], [
            'action.required'              => 'Tindakan verifikasi harus dipilih.',
            'action.in'                    => 'Tindakan tidak valid.',
            'rejection_note.required_if'   => 'Catatan penolakan wajib diisi jika status Ditolak.',
        ]);

        // 🛑 GUARD 2 (REVISI): Minimum 1 document required AND ALL documents must be checked (is_checked = true)
        if ($validated['action'] === 'APPROVED') {
            $totalDocs = $item->documents()->count();
            $uncheckedCount = $item->documents()->where('is_checked', false)->count();

            if ($totalDocs === 0) {
                return back()->with('error', 'Gagal menyetujui pencairan: Minimal harus ada 1 dokumen SPJ/BAPP yang terunggah sebelum dapat disetujui.');
            }

            if ($uncheckedCount > 0) {
                return back()->with('error', "Gagal menyetujui pencairan: Masih ada {$uncheckedCount} dokumen yang belum dicentang/diperiksa oleh Bendahara.");
            }

            // 🛑 GUARD 6: Segregation of Duties (Prinsip Empat Mata Audit Keuangan)
            // Pengguna yang mengunggah dokumen tidak boleh memverifikasi/menyetujui item miliknya sendiri
            $hasSelfUploadedDocs = $item->documents()->where('uploaded_by_user_id', $user->id)->exists();
            if ($hasSelfUploadedDocs && !$user->isBendahara()) {
                return back()->with('error', 'Pelanggaran Prinsip Empat Mata (Segregation of Duties): Pengguna yang mengunggah dokumen tidak diperbolehkan memverifikasi/menyetujui item pencairannya sendiri.');
            }
        }

        // 🧹 GUARD 5: Jika status REJECTED, reset seluruh checklist dokumen item ke false
        if ($validated['action'] === 'REJECTED') {
            $item->documents()->update([
                'is_checked'         => false,
                'checked_by_user_id' => null,
                'checked_at'         => null,
            ]);
        }

        $item->update([
            'verification_status' => $validated['action'],
            'rejection_note'      => $validated['action'] === 'REJECTED' ? $validated['rejection_note'] : null,
        ]);

        // Catat Audit Log
        ActivityLog::create([
            'item_id'     => $item->id,
            'user_id'     => auth()->id(),
            'action'      => $validated['action'] === 'APPROVED' ? 'VERIFY_APPROVED' : 'VERIFY_REJECTED',
            'description' => $validated['action'] === 'APPROVED'
                ? "Pencairan disetujui oleh {$user->name}."
                : "Pencairan ditolak. Alasan: \"{$validated['rejection_note']}\".",
        ]);

        $statusLabel = $validated['action'] === 'APPROVED' ? 'Disetujui (Siap Cair)' : 'Ditolak';
        return back()->with('success', "Status item [{$item->code}] berhasil diubah menjadi: {$statusLabel}.");
    }
}
