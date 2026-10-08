<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->isBendahara() && !$user->isAdmin()) {
            abort(403, 'Akses ditolak: Hanya Bendahara Pengeluaran atau Admin yang berwenang melakukan verifikasi pencairan.');
        }

        $status = $request->query('status', 'PENDING');
        $params = $request->except('status');

        if ($status !== 'ALL') {
            $params['filter'] = strtolower($status);
        }

        return redirect()->route('items.index', $params);
    }

    /**
     * Return item summary + paginated activity logs as JSON for the pre-verification popup.
     */
    public function itemSummary(Request $request, Item $item)
    {
        $user = auth()->user();
        if (!$user->isBendahara() && !$user->isAdmin()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $page = (int) $request->query('page', 1);
        $perPage = 5;

        $item->load(['documents.uploadedBy', 'account.subComponent.component.subOutput']);

        $logs = $item->activityLogs()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'item' => [
                'id'                  => $item->id,
                'code'                => $item->code,
                'name'                => $item->name,
                'pagu_formatted'      => $item->pagu_formatted,
                'verification_status' => $item->verification_status,
                'rejection_note'      => $item->rejection_note,
                'documents_count'     => $item->documents->count(),
                'checked_count'       => $item->documents->where('is_checked', true)->count(),
            ],
            'documents' => $item->documents->map(fn($doc) => [
                'id'         => $doc->id,
                'file_name'  => $doc->file_name,
                'label'      => $doc->label ?? 'Dokumen',
                'file_type'  => $doc->file_type,
                'is_checked' => (bool) $doc->is_checked,
                'uploader'   => $doc->uploadedBy->name ?? '-',
            ]),
            'logs' => [
                'data' => $logs->map(fn($log) => [
                    'action'      => $log->action,
                    'description' => $log->description,
                    'user_name'   => $log->user->name ?? 'System',
                    'user_role'   => $log->user->role ?? 'SYS',
                    'created_at'  => $log->created_at->format('d/m/Y H:i') . ' WIB',
                ]),
                'current_page' => $logs->currentPage(),
                'last_page'    => $logs->lastPage(),
                'total'        => $logs->total(),
            ],
        ]);
    }
}
