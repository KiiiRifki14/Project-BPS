<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display item detail with all documents.
     */
    public function show(Item $item)
    {
        $item->load([
            'documents.uploadedBy',
            'account.subComponent.component.subOutput.output.program.fiscalYear',
        ]);

        $breadcrumb = $item->breadcrumb;

        return view('items.show', compact('item', 'breadcrumb'));
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

        $statusLabel = $validated['action'] === 'APPROVED' ? 'Disetujui (Siap Cair)' : 'Ditolak';
        return back()->with('success', "Status item [{$item->code}] berhasil diubah menjadi: {$statusLabel}.");
    }

}
