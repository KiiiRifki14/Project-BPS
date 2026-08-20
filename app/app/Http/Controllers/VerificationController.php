<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'BENDAHARA') {
            abort(403, 'Akses ditolak: Hanya Bendahara Pengeluaran yang berwenang melakukan verifikasi pencairan.');
        }

        $status = $request->query('status', 'PENDING');
        $search = $request->query('search');

        $query = Item::with(['account.subComponent.component.subOutput.output.program', 'documents']);

        if ($status !== 'ALL') {
            $query->where('verification_status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhereHas('account', function ($qAcc) use ($search) {
                      $qAcc->where('code', 'like', "%{$search}%")
                           ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        $items = $query->orderBy('updated_at', 'desc')->paginate(15)->withQueryString();

        $pendingCount = Item::where('verification_status', 'PENDING')->count();
        $approvedCount = Item::where('verification_status', 'APPROVED')->count();
        $rejectedCount = Item::where('verification_status', 'REJECTED')->count();

        return view('verification.index', compact('items', 'status', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }
}
