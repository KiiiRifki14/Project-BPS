<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Item;
use Illuminate\Http\Request;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter'); // null | 'pending' | 'rejected' | 'approved'
        $search = $request->get('search');

        // Load full POK hierarchy
        $programs = Program::with([
            'outputs.subOutputs.components.subComponents.accounts.items.documents'
        ])->get();

        // Quick stats for header cards
        $stats = [
            'total'    => Item::count(),
            'approved' => Item::where('verification_status', 'APPROVED')->count(),
            'pending'  => Item::where('verification_status', 'PENDING')->count(),
            'rejected' => Item::where('verification_status', 'REJECTED')->count(),
        ];

        // If there's a filter or search, load flat item list
        $filteredItems = null;
        if ($filter || $search) {
            $query = Item::with(['account.subComponent.component.subOutput.output.program', 'documents']);

            if ($filter === 'pending')  $query->where('verification_status', 'PENDING');
            if ($filter === 'approved') $query->where('verification_status', 'APPROVED');
            if ($filter === 'rejected') $query->where('verification_status', 'REJECTED');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%");
                });
            }

            $filteredItems = $query->orderBy('code')->get();
        }

        return view('arsip.index', compact('programs', 'stats', 'filter', 'search', 'filteredItems'));
    }
}
