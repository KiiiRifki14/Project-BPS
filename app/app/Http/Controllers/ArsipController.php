<?php

namespace App\Http\Controllers;

use App\Models\Output;
use App\Models\Program;
use App\Models\SubOutput;
use App\Models\Item;
use Illuminate\Http\Request;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter'); // null | 'pending' | 'rejected' | 'approved'
        $search = $request->get('search');
        $programId = $request->get('program_id');
        $outputId = $request->get('output_id');
        $subOutputId = $request->get('sub_output_id');

        $programs = Program::all();
        $outputs = $programId ? Output::where('program_id', $programId)->get() : Output::all();
        $subOutputs = $outputId ? SubOutput::where('output_id', $outputId)->get() : SubOutput::all();

        // Quick stats
        $stats = [
            'total'    => Item::count(),
            'approved' => Item::where('verification_status', 'APPROVED')->count(),
            'pending'  => Item::where('verification_status', 'PENDING')->count(),
            'rejected' => Item::where('verification_status', 'REJECTED')->count(),
        ];

        // Query items
        $query = Item::with(['account.subComponent.component.subOutput.output.program', 'documents']);

        if ($filter === 'pending')  $query->where('verification_status', 'PENDING');
        if ($filter === 'approved') $query->where('verification_status', 'APPROVED');
        if ($filter === 'rejected') $query->where('verification_status', 'REJECTED');

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

        if ($subOutputId) {
            $query->whereHas('account.subComponent.component.subOutput', function ($qSo) use ($subOutputId) {
                $qSo->where('id', $subOutputId);
            });
        } elseif ($outputId) {
            $query->whereHas('account.subComponent.component.subOutput.output', function ($qOut) use ($outputId) {
                $qOut->where('id', $outputId);
            });
        } elseif ($programId) {
            $query->whereHas('account.subComponent.component.subOutput.output.program', function ($qProg) use ($programId) {
                $qProg->where('id', $programId);
            });
        }

        $items = $query->orderBy('code')->paginate(20)->withQueryString();

        // Active SubOutput details if selected or defaulted to BMA.006 when requested
        $selectedSubOutput = $subOutputId ? SubOutput::find($subOutputId) : null;

        return view('arsip.index', compact(
            'programs',
            'outputs',
            'subOutputs',
            'stats',
            'filter',
            'search',
            'programId',
            'outputId',
            'subOutputId',
            'selectedSubOutput',
            'items'
        ));
    }
}
