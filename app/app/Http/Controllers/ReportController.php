<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\SubOutput;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $subOutputs = SubOutput::with(['components.subComponents.accounts.items.documents'])->paginate(5);

        $summary = [
            'total_items' => Item::count(),
            'total_pagu' => Item::sum('pagu'),
            'approved_items' => Item::where('verification_status', 'APPROVED')->count(),
            'approved_pagu' => Item::where('verification_status', 'APPROVED')->sum('pagu'),
            'pending_items' => Item::where('verification_status', 'PENDING')->count(),
            'rejected_items' => Item::where('verification_status', 'REJECTED')->count(),
        ];

        return view('reports.index', compact('subOutputs', 'summary'));
    }
}
