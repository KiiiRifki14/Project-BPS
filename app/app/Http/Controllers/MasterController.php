<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Component;
use App\Models\FiscalYear;
use App\Models\Item;
use App\Models\Output;
use App\Models\Program;
use App\Models\SubComponent;
use App\Models\SubOutput;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function index()
    {
        $fiscalYears = FiscalYear::orderBy('year', 'desc')->get();
        $programs    = Program::with('fiscalYear')->orderBy('code')->get();
        $outputs     = Output::with('program')->orderBy('code')->get();
        $subOutputs  = SubOutput::with('output.program')->orderBy('code')->get();
        $components  = Component::with('subOutput')->orderBy('code')->get();
        $subComponents = SubComponent::with('component')->orderBy('code')->get();
        $accounts    = Account::with('subComponent.component.subOutput')->orderBy('code')->paginate(10, ['*'], 'accounts_page');
        $items       = Item::with('account.subComponent.component.subOutput')->orderBy('code')->paginate(10, ['*'], 'items_page');

        return view('master.index', compact(
            'fiscalYears', 'programs', 'outputs', 'subOutputs',
            'components', 'subComponents', 'accounts', 'items'
        ));
    }

    // ── FISCAL YEAR ──────────────────────────────────
    public function storeFiscalYear(Request $request)
    {
        $request->validate(['year' => 'required|integer|min:2024|max:2099|unique:fiscal_years,year']);
        FiscalYear::create(['year' => $request->year, 'is_active' => $request->boolean('is_active', true)]);
        return back()->with('success', "Tahun Anggaran {$request->year} berhasil ditambahkan.");
    }

    // ── PROGRAM ──────────────────────────────────────
    public function storeProgram(Request $request)
    {
        $request->validate([
            'fiscal_year_id' => 'required|exists:fiscal_years,id',
            'code'           => 'required|string|max:20',
            'name'           => 'required|string|max:255',
        ]);
        Program::create($request->only('fiscal_year_id', 'code', 'name'));
        return back()->with('success', "Program [{$request->code}] berhasil ditambahkan.");
    }

    public function updateProgram(Request $request, Program $program)
    {
        $request->validate(['code' => 'required|string|max:20', 'name' => 'required|string|max:255']);
        $program->update($request->only('code', 'name'));
        return back()->with('success', "Program [{$program->code}] berhasil diperbarui.");
    }

    public function destroyProgram(Program $program)
    {
        $program->delete();
        return back()->with('success', "Program berhasil dihapus.");
    }

    // ── OUTPUT ───────────────────────────────────────
    public function storeOutput(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'code'       => 'required|string|max:20',
            'name'       => 'required|string|max:255',
        ]);
        Output::create($request->only('program_id', 'code', 'name'));
        return back()->with('success', "Output [{$request->code}] berhasil ditambahkan.");
    }

    // ── SUB-OUTPUT ───────────────────────────────────
    public function storeSubOutput(Request $request)
    {
        $request->validate([
            'output_id' => 'required|exists:outputs,id',
            'code'      => 'required|string|max:30',
            'name'      => 'required|string|max:255',
        ]);
        SubOutput::create($request->only('output_id', 'code', 'name'));
        return back()->with('success', "Sub-Output [{$request->code}] berhasil ditambahkan.");
    }

    // ── COMPONENT ────────────────────────────────────
    public function storeComponent(Request $request)
    {
        $request->validate([
            'sub_output_id' => 'required|exists:sub_outputs,id',
            'code'          => 'required|string|max:20',
            'name'          => 'required|string|max:255',
        ]);
        Component::create($request->only('sub_output_id', 'code', 'name'));
        return back()->with('success', "Komponen [{$request->code}] berhasil ditambahkan.");
    }

    // ── SUB-COMPONENT ─────────────────────────────────
    public function storeSubComponent(Request $request)
    {
        $request->validate([
            'component_id' => 'required|exists:components,id',
            'code'         => 'required|string|max:20',
            'name'         => 'required|string|max:255',
        ]);
        SubComponent::create($request->only('component_id', 'code', 'name'));
        return back()->with('success', "Sub-Komponen [{$request->code}] berhasil ditambahkan.");
    }

    // ── ACCOUNT ───────────────────────────────────────
    public function storeAccount(Request $request)
    {
        $request->validate([
            'sub_component_id' => 'required|exists:sub_components,id',
            'code'             => 'required|string|max:10',
            'name'             => 'required|string|max:255',
        ]);
        Account::create($request->only('sub_component_id', 'code', 'name'));
        return back()->with('success', "Akun [{$request->code}] berhasil ditambahkan.");
    }

    // ── ITEM ──────────────────────────────────────────
    public function storeItem(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'code'       => 'required|string|max:10',
            'name'       => 'required|string|max:255',
            'pagu'       => 'required|numeric|min:0',
        ]);
        Item::create($request->only('account_id', 'code', 'name', 'pagu'));
        return back()->with('success', "Item [{$request->code}] berhasil ditambahkan. Kini tersedia di sidebar navigasi.");
    }

    public function updateItem(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pagu' => 'required|numeric|min:0',
        ]);
        $item->update($request->only('name', 'pagu'));
        return back()->with('success', "Item [{$item->code}] berhasil diperbarui.");
    }

    public function destroyItem(Item $item)
    {
        $item->delete();
        return back()->with('success', "Item berhasil dihapus.");
    }
}
