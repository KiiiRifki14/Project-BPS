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

        $statsRaw = Item::selectRaw("
            COUNT(*) as total_items,
            COALESCE(SUM(pagu), 0) as total_pagu,
            COUNT(CASE WHEN verification_status = 'APPROVED' THEN 1 END) as approved,
            COUNT(CASE WHEN verification_status = 'PENDING' THEN 1 END) as pending,
            COUNT(CASE WHEN verification_status = 'REJECTED' THEN 1 END) as rejected,
            COALESCE(SUM(CASE WHEN verification_status = 'APPROVED' THEN pagu ELSE 0 END), 0) as pagu_approved
        ")->first();

        $stats = [
            'total_items'    => (int) ($statsRaw->total_items ?? 0),
            'total_pagu'     => (float) ($statsRaw->total_pagu ?? 0),
            'approved'       => (int) ($statsRaw->approved ?? 0),
            'pending'        => (int) ($statsRaw->pending ?? 0),
            'rejected'       => (int) ($statsRaw->rejected ?? 0),
            'pagu_approved'  => (float) ($statsRaw->pagu_approved ?? 0),
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

        $bma006 = \App\Models\SubOutput::where('code', 'BMA.006')->first();

        return view('dashboard', compact('stats', 'recentItems', 'fy', 'bma006'));
    }
}
