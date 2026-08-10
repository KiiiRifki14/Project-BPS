<?php

namespace App\Http\Controllers;

use App\Models\FiscalYear;
use App\Models\Item;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $fy = FiscalYear::where('is_active', true)->first();

        $stats = [
            'total_items'    => Item::count(),
            'total_pagu'     => Item::sum('pagu'),
            'approved'       => Item::where('verification_status', 'APPROVED')->count(),
            'pending'        => Item::where('verification_status', 'PENDING')->count(),
            'rejected'       => Item::where('verification_status', 'REJECTED')->count(),
            'pagu_approved'  => Item::where('verification_status', 'APPROVED')->sum('pagu'),
        ];

        // Recent items for BMA.006 — MVP focus
        $recentItems = Item::with([
                'account.subComponent.component.subOutput.output.program.fiscalYear'
            ])
            ->whereHas('account.subComponent.component.subOutput', function ($q) {
                $q->where('code', 'BMA.006');
            })
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'recentItems', 'fy'));
    }
}
