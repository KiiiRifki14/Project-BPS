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
        $user = $request->user();

        if (!$user->canVerify()) {
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

        $item->update([
            'verification_status' => $validated['action'],
            'rejection_note'      => $validated['action'] === 'REJECTED' ? $validated['rejection_note'] : null,
        ]);

        $statusLabel = $validated['action'] === 'APPROVED' ? 'Disetujui (Siap Cair)' : 'Ditolak';
        return back()->with('success', "Status item [{$item->code}] berhasil diubah menjadi: {$statusLabel}.");
    }
}
