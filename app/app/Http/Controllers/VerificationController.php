<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'PENDING');

        $query = Item::with(['account.subComponent.component.subOutput.output.program', 'documents']);

        if ($status !== 'ALL') {
            $query->where('verification_status', $status);
        }

        $items = $query->orderBy('updated_at', 'desc')->paginate(15);

        $pendingCount = Item::where('verification_status', 'PENDING')->count();
        $approvedCount = Item::where('verification_status', 'APPROVED')->count();
        $rejectedCount = Item::where('verification_status', 'REJECTED')->count();

        return view('verification.index', compact('items', 'status', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }
}
