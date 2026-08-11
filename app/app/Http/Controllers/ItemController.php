<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\AuditLogger;
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
     * OWASP A09: Audit log on every verification decision.
     */
    public function verify(Request $request, Item $item)
    {
        $user = $request->user();

        if (!$user->canVerify()) {
            AuditLogger::denied("items/{$item->id}/verify", 'Role tidak memiliki hak verifikasi');
            abort(403, 'Hanya Bendahara atau Admin yang dapat melakukan verifikasi pencairan.');
        }

        $validated = $request->validate([
            'action'         => 'required|in:APPROVED,REJECTED',
            'rejection_note' => 'required_if:action,REJECTED|nullable|string|max:500',
        ], [
            'action.required'              => 'Tindakan verifikasi harus dipilih.',
            'action.in'                    => 'Tindakan tidak valid.',
            'rejection_note.required_if'   => 'Catatan penolakan wajib diisi jika status Ditolak.',
        ]);

        // 🛑 GUARD 2: Minimum 1 document required before APPROVED
        if ($validated['action'] === 'APPROVED' && $item->documents()->count() === 0) {
            return back()->with('error', 'Gagal menyetujui pencairan: Minimal harus ada 1 dokumen SPJ/BAPP yang terunggah sebelum dapat disetujui.');
        }

        $item->update([
            'verification_status' => $validated['action'],
            'rejection_note'      => $validated['action'] === 'REJECTED' ? $validated['rejection_note'] : null,
        ]);

        // ─── Audit Log: Verification Decision ────────────────────────────
        $auditAction = $validated['action'] === 'APPROVED' ? 'ITEM_APPROVE' : 'ITEM_REJECT';
        $note        = $validated['action'] === 'REJECTED' ? " | Catatan: {$validated['rejection_note']}" : '';
        AuditLogger::log($auditAction, "Item [{$item->code}] diubah statusnya menjadi {$validated['action']} oleh [{$user->nip_username}].{$note}");

        $statusLabel = $validated['action'] === 'APPROVED' ? 'Disetujui (Siap Cair)' : 'Ditolak';
        return back()->with('success', "Status item [{$item->code}] berhasil diubah menjadi: {$statusLabel}.");
    }
}
