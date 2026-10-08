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
        $allAccountsList = Account::with('subComponent.component.subOutput')->orderBy('code')->get()->map(function ($a) {
            return [
                'id'         => $a->id,
                'code'       => $a->code,
                'name'       => $a->name,
                'sub_output' => $a->subComponent->component->subOutput->code ?? '',
                'label'      => "[{$a->code}] {$a->name}",
            ];
        });
        $items       = Item::with('account.subComponent.component.subOutput')->orderBy('code')->paginate(10, ['*'], 'items_page');

        return view('master.index', compact(
            'fiscalYears', 'programs', 'outputs', 'subOutputs',
            'components', 'subComponents', 'accounts', 'allAccountsList', 'items'
        ));
    }

    // ── FISCAL YEAR ──────────────────────────────────
    public function storeFiscalYear(Request $request)
    {
        $request->validate(['year' => 'required|integer|min:2024|max:2099|unique:fiscal_years,year']);

        $isActive = $request->boolean('is_active', false);
        if ($isActive) {
            FiscalYear::where('is_active', true)->update(['is_active' => false]);
        }

        FiscalYear::create([
            'year'      => $request->year,
            'is_active' => $isActive,
        ]);

        return back()->with('success', "Tahun Anggaran {$request->year} berhasil ditambahkan.");
    }

    public function toggleFiscalYear(FiscalYear $fiscalYear)
    {
        if (!$fiscalYear->is_active) {
            FiscalYear::where('is_active', true)->update(['is_active' => false]);
            $fiscalYear->update(['is_active' => true]);
            $message = "Tahun Anggaran {$fiscalYear->year} kini aktif sebagai Tahun Anggaran Berjalan.";
        } else {
            $activeCount = FiscalYear::where('is_active', true)->count();
            if ($activeCount <= 1) {
                return back()->with('error', 'Gagal: Minimal harus ada 1 Tahun Anggaran yang aktif.');
            }
            $fiscalYear->update(['is_active' => false]);
            $message = "Tahun Anggaran {$fiscalYear->year} telah dinonaktifkan (Arsip Lampau).";
        }

        return back()->with('success', $message);
    }

    public function cloneFiscalYear(Request $request)
    {
        $request->validate([
            'source_fiscal_year_id' => 'required|exists:fiscal_years,id',
            'target_year'           => 'required|integer|min:2024|max:2099',
            'copy_pagu'             => 'nullable|boolean',
            'set_active'            => 'nullable|boolean',
        ]);

        $sourceFy = FiscalYear::with([
            'programs.outputs.subOutputs.components.subComponents.accounts.items'
        ])->findOrFail($request->source_fiscal_year_id);

        $targetYear = (int) $request->target_year;

        if ($sourceFy->year === $targetYear) {
            return back()->with('error', "Gagal: Tahun sumber dan tahun tujuan tidak boleh sama ({$targetYear}).");
        }

        $targetFy = FiscalYear::firstOrCreate(
            ['year' => $targetYear],
            ['is_active' => false]
        );

        if ($targetFy->programs()->exists()) {
            return back()->with('error', "Gagal: Tahun Anggaran {$targetYear} sudah memiliki {$targetFy->programs()->count()} Program terdaftar. Untuk mencegah duplikasi data ganda, salin hanya dapat dilakukan ke tahun yang belum memiliki struktur POK.");
        }

        $copyPagu = $request->boolean('copy_pagu', true);
        $setActive = $request->boolean('set_active', false);

        $stats = [
            'programs'       => 0,
            'outputs'        => 0,
            'sub_outputs'    => 0,
            'components'     => 0,
            'sub_components' => 0,
            'accounts'       => 0,
            'items'          => 0,
        ];

        \Illuminate\Support\Facades\DB::transaction(function () use ($sourceFy, $targetFy, $copyPagu, $setActive, &$stats) {
            if ($setActive) {
                FiscalYear::where('id', '!=', $targetFy->id)->update(['is_active' => false]);
                $targetFy->update(['is_active' => true]);
            }

            foreach ($sourceFy->programs as $srcProg) {
                $newProg = Program::create([
                    'fiscal_year_id' => $targetFy->id,
                    'code'           => $srcProg->code,
                    'name'           => $srcProg->name,
                ]);
                $stats['programs']++;

                foreach ($srcProg->outputs as $srcOut) {
                    $newOut = Output::create([
                        'program_id' => $newProg->id,
                        'code'       => $srcOut->code,
                        'name'       => $srcOut->name,
                    ]);
                    $stats['outputs']++;

                    foreach ($srcOut->subOutputs as $srcSubOut) {
                        $newSubOut = SubOutput::create([
                            'output_id' => $newOut->id,
                            'code'      => $srcSubOut->code,
                            'name'      => $srcSubOut->name,
                        ]);
                        $stats['sub_outputs']++;

                        foreach ($srcSubOut->components as $srcComp) {
                            $newComp = Component::create([
                                'sub_output_id' => $newSubOut->id,
                                'code'          => $srcComp->code,
                                'name'          => $srcComp->name,
                            ]);
                            $stats['components']++;

                            foreach ($srcComp->subComponents as $srcSubComp) {
                                $newSubComp = SubComponent::create([
                                    'component_id' => $newComp->id,
                                    'code'         => $srcSubComp->code,
                                    'name'         => $srcSubComp->name,
                                ]);
                                $stats['sub_components']++;

                                foreach ($srcSubComp->accounts as $srcAcc) {
                                    $newAcc = Account::create([
                                        'sub_component_id' => $newSubComp->id,
                                        'code'             => $srcAcc->code,
                                        'name'             => $srcAcc->name,
                                    ]);
                                    $stats['accounts']++;

                                    foreach ($srcAcc->items as $srcItem) {
                                        Item::create([
                                            'account_id'          => $newAcc->id,
                                            'code'                => $srcItem->code,
                                            'name'                => $srcItem->name,
                                            'pagu'                => $copyPagu ? $srcItem->pagu : 0,
                                            'verification_status' => 'PENDING',
                                            'rejection_note'      => null,
                                        ]);
                                        $stats['items']++;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        });

        $activeStatusMsg = $setActive ? "dan telah diset sebagai Tahun Anggaran Aktif." : "Status TA saat ini: Non-aktif (dapat diaktifkan kapan saja).";

        return back()->with('success', "Berhasil menyalin struktur POK dari TA {$sourceFy->year} ke TA {$targetYear}! Total disalin: {$stats['programs']} Program, {$stats['outputs']} Output, {$stats['sub_outputs']} Sub-Output, {$stats['components']} Komponen, {$stats['sub_components']} Sub-Komponen, {$stats['accounts']} Akun, {$stats['items']} Item Kegiatan ({$activeStatusMsg}). Dokumen SPJ dimulai dari kondisi bersih (kosong).");
    }

    // ── PROGRAM ──────────────────────────────────────
    public function storeProgram(Request $request)
    {
        $request->validate([
            'fiscal_year_id' => 'required|exists:fiscal_years,id',
            'code'           => [
                'required',
                'string',
                'max:20',
                \Illuminate\Validation\Rule::unique('programs', 'code')->where('fiscal_year_id', $request->fiscal_year_id),
            ],
            'name'           => 'required|string|max:255',
        ], [
            'code.unique' => 'Gagal: Kode Program [:input] sudah terdaftar pada tahun anggaran ini. Kode Program wajib unik per tahun.',
        ]);
        Program::create($request->only('fiscal_year_id', 'code', 'name'));
        return back()->with('success', "Program [{$request->code}] berhasil ditambahkan.");
    }

    public function updateProgram(Request $request, Program $program)
    {
        $request->validate(['code' => 'required|string|max:20|unique:programs,code,' . $program->id, 'name' => 'required|string|max:255']);
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
            'code'       => 'required|string|max:20|unique:outputs,code',
            'name'       => 'required|string|max:255',
        ], [
            'code.unique' => 'Gagal: Kode Output [:input] sudah terdaftar. Kode Output wajib unik.',
        ]);
        Output::create($request->only('program_id', 'code', 'name'));
        return back()->with('success', "Output [{$request->code}] berhasil ditambahkan.");
    }

    // ── SUB-OUTPUT ───────────────────────────────────
    public function storeSubOutput(Request $request)
    {
        $request->validate([
            'output_id' => 'required|exists:outputs,id',
            'code'      => 'required|string|max:30|unique:sub_outputs,code',
            'name'      => 'required|string|max:255',
        ], [
            'code.unique' => 'Gagal: Kode Sub-Output [:input] sudah terdaftar. Kode Sub-Output wajib unik.',
        ]);
        SubOutput::create($request->only('output_id', 'code', 'name'));
        return back()->with('success', "Sub-Output [{$request->code}] berhasil ditambahkan.");
    }

    // ── COMPONENT ────────────────────────────────────
    public function storeComponent(Request $request)
    {
        $request->validate([
            'sub_output_id' => 'required|exists:sub_outputs,id',
            'code'          => 'required|string|max:20|unique:components,code',
            'name'          => 'required|string|max:255',
        ], [
            'code.unique' => 'Gagal: Kode Komponen [:input] sudah terdaftar. Kode Komponen wajib unik.',
        ]);
        Component::create($request->only('sub_output_id', 'code', 'name'));
        return back()->with('success', "Komponen [{$request->code}] berhasil ditambahkan.");
    }

    // ── SUB-COMPONENT ─────────────────────────────────
    public function storeSubComponent(Request $request)
    {
        $request->validate([
            'component_id' => 'required|exists:components,id',
            'code'         => 'required|string|max:20|unique:sub_components,code',
            'name'         => 'required|string|max:255',
        ], [
            'code.unique' => 'Gagal: Kode Sub-Komponen [:input] sudah terdaftar. Kode Sub-Komponen wajib unik.',
        ]);
        SubComponent::create($request->only('component_id', 'code', 'name'));
        return back()->with('success', "Sub-Komponen [{$request->code}] berhasil ditambahkan.");
    }

    // ── ACCOUNT ───────────────────────────────────────
    public function storeAccount(Request $request)
    {
        $request->validate([
            'sub_component_id' => 'required|exists:sub_components,id',
            'code'             => 'required|string|max:10|unique:accounts,code',
            'name'             => 'required|string|max:255',
        ], [
            'code.unique' => 'Gagal: Kode Akun [:input] sudah terdaftar. Kode Akun wajib unik.',
        ]);
        Account::create($request->only('sub_component_id', 'code', 'name'));
        return back()->with('success', "Akun [{$request->code}] berhasil ditambahkan.");
    }

    public function destroyAccount(Account $account)
    {
        $account->delete();
        return back()->with('success', "Akun [{$account->code}] berhasil dihapus.");
    }

    public function destroySubComponent(SubComponent $subComponent)
    {
        $subComponent->delete();
        return back()->with('success', "Sub-Komponen [{$subComponent->code}] berhasil dihapus.");
    }

    public function destroyComponent(Component $component)
    {
        $component->delete();
        return back()->with('success', "Komponen [{$component->code}] berhasil dihapus.");
    }

    public function destroySubOutput(SubOutput $subOutput)
    {
        $subOutput->delete();
        return back()->with('success', "Sub-Output [{$subOutput->code}] berhasil dihapus.");
    }

    public function destroyOutput(Output $output)
    {
        $output->delete();
        return back()->with('success', "Output [{$output->code}] berhasil dihapus.");
    }

    // ── ITEM ──────────────────────────────────────────
    public function storeItem(Request $request)
    {
        if ($request->has('pagu')) {
            $request->merge([
                'pagu' => (float) preg_replace('/[^0-9]/', '', (string)$request->input('pagu'))
            ]);
        }

        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'code'       => 'required|string|max:10|unique:items,code',
            'name'       => 'required|string|max:255',
            'pagu'       => 'required|numeric|min:0',
        ], [
            'code.unique'        => 'Gagal menambahkan: Kode Item [:input] sudah terdaftar. Kode item kegiatan wajib unik (tidak boleh ada kode duplikat).',
            'code.required'      => 'Kode item wajib diisi.',
            'account_id.required'=> 'Akun POK wajib dipilih.',
            'name.required'      => 'Nama item kegiatan wajib diisi.',
            'pagu.required'      => 'Pagu anggaran wajib diisi.',
        ]);
        Item::create($request->only('account_id', 'code', 'name', 'pagu'));
        return back()->with('success', "Item [{$request->code}] berhasil ditambahkan. Kini tersedia di sidebar navigasi.");
    }

    public function updateItem(Request $request, Item $item)
    {
        if ($request->has('pagu')) {
            $request->merge([
                'pagu' => (float) preg_replace('/[^0-9]/', '', (string)$request->input('pagu'))
            ]);
        }

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
