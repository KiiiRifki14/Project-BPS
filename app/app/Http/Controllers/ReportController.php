<?php

namespace App\Http\Controllers;

use App\Models\FiscalYear;
use App\Models\Item;
use App\Models\SubOutput;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year  = $request->input('year', date('Y'));
        $month = $request->input('month');

        $fiscalYears = FiscalYear::orderBy('year', 'desc')->get();

        // Base query for items filtered by fiscal year and month
        $itemsQuery = Item::whereHas('account.subComponent.component.subOutput.output.program.fiscalYear', function ($q) use ($year) {
            $q->where('year', $year);
        });

        if ($month) {
            $itemsQuery->whereMonth('updated_at', $month);
        }

        $totalItems    = (clone $itemsQuery)->count();
        $totalPagu     = (clone $itemsQuery)->sum('pagu');
        $approvedItems = (clone $itemsQuery)->where('verification_status', 'APPROVED')->count();
        $approvedPagu  = (clone $itemsQuery)->where('verification_status', 'APPROVED')->sum('pagu');
        $pendingItems  = (clone $itemsQuery)->where('verification_status', 'PENDING')->count();
        $rejectedItems = (clone $itemsQuery)->where('verification_status', 'REJECTED')->count();

        $summary = [
            'total_items'    => $totalItems,
            'total_pagu'     => $totalPagu,
            'approved_items' => $approvedItems,
            'approved_pagu'  => $approvedPagu,
            'pending_items'  => $pendingItems,
            'rejected_items' => $rejectedItems,
        ];

        $subOutputs = SubOutput::whereHas('output.program.fiscalYear', function ($q) use ($year) {
            $q->where('year', $year);
        })->with(['components.subComponents.accounts.items' => function ($q) use ($month) {
            if ($month) {
                $q->whereMonth('updated_at', $month);
            }
        }])->paginate(10);

        return view('reports.index', compact('subOutputs', 'summary', 'fiscalYears', 'year', 'month'));
    }
}

