@extends('layouts.app')
@section('title', 'Manajemen Master Data POK')

@section('content')
<div class="space-y-8" x-data="{ tab: 'items' }">

    {{-- Page Header --}}
    <div class="card-corporate p-8 flex items-center justify-between flex-wrap gap-6">
        <div>
            <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-900 font-extrabold text-xs px-3.5 py-1.5 rounded-lg mb-2">
                <span>⚙️ KHUSUS SUPERVISOR & ADMIN</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Master Data POK</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                Kelola hirarki 8-level POK (Program, Output, Sub-Output, Komponen, Sub-Komponen, Akun, Item, & Tahun Anggaran).
            </p>
        </div>
    </div>

    {{-- TABS BAR --}}
    <div class="flex gap-2 p-1.5 bg-slate-200/70 rounded-xl overflow-x-auto">
        @foreach([
            ['id' => 'items', 'label' => '📋 Item Kegiatan'],
            ['id' => 'accounts', 'label' => '💳 Akun'],
            ['id' => 'subcomponents', 'label' => '🔷 Sub-Komponen'],
            ['id' => 'components', 'label' => '🔶 Komponen'],
            ['id' => 'suboutputs', 'label' => '📦 Sub-Output'],
            ['id' => 'outputs', 'label' => '📁 Output'],
            ['id' => 'fiscal', 'label' => '📅 Tahun Anggaran'],
        ] as $t)
        <button @click="tab = '{{ $t['id'] }}'"
                :class="tab === '{{ $t['id'] }}' ? 'btn-bps btn-bps-primary text-xs' : 'btn-bps btn-bps-secondary text-xs'"
                class="whitespace-nowrap">
            {{ $t['label'] }}
        </button>
        @endforeach
    </div>

    {{-- ITEMS TAB --}}
    <div x-show="tab === 'items'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 table-container-v4">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <h2 class="text-sm font-extrabold text-slate-900">Daftar Item Kegiatan ({{ $items->total() }} Total Item)</h2>
                </div>
                <table class="table-v4">
                    <thead>
                        <tr>
                            <th class="w-28 text-center">Kode</th>
                            <th>Nama Item Kegiatan</th>
                            <th>Akun / Sub-Output</th>
                            <th class="text-right">Pagu</th>
                            <th class="text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td class="text-center whitespace-nowrap">
                                <span class="font-mono text-xs font-bold text-blue-900 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-lg">
                                    {{ $item->code }}
                                </span>
                            </td>
                            <td>
                                <div class="font-extrabold text-slate-900 text-xs">{{ Str::limit($item->name, 50) }}</div>
                            </td>
                            <td class="text-xs">
                                <div class="font-bold text-slate-800">[{{ $item->account->code }}]</div>
                                <div class="text-[10px] font-mono text-blue-800">{{ Str::limit($item->account->subComponent->component->subOutput->code, 15) }}</div>
                            </td>
                            <td class="text-right font-mono font-bold text-emerald-700 text-xs whitespace-nowrap">
                                Rp {{ number_format($item->pagu, 0, ',', '.') }}
                            </td>
                            <td class="text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" class="btn-bps btn-bps-secondary btn-bps-sm"
                                            @click="$dispatch('open-edit-item', {{ $item->toJson() }})">
                                        ✏️
                                    </button>
                                    <form action="{{ route('master.items.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Hapus item {{ $item->code }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-bps btn-bps-danger btn-bps-sm">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Pagination Links for Items --}}
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                    {{ $items->appends(request()->query())->links() }}
                </div>
            </div>

            {{-- Form Column --}}
            <div class="card-corporate p-6 lg:sticky lg:top-24"
                 x-data="{ editMode: false, editItem: null }"
                 @open-edit-item.window="editItem = $event.detail; editMode = true">

                <h2 class="text-sm font-extrabold text-slate-900 mb-4">
                    <span x-show="!editMode">➕ Tambah Item Baru</span>
                    <span x-show="editMode">✏️ Edit Item</span>
                </h2>

                <form x-show="!editMode" action="{{ route('master.items.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label-custom">Akun POK *</label>
                        <select name="account_id" class="form-input-v4 text-xs" required>
                            <option value="">— Pilih Akun —</option>
                            @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">[{{ $acc->code }}] {{ Str::limit($acc->name, 35) }} ({{ $acc->subComponent->component->subOutput->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label-custom">Kode Item *</label>
                        <input type="text" name="code" class="form-input-v4" placeholder="contoh: 001366" maxlength="10" required>
                    </div>
                    <div>
                        <label class="form-label-custom">Nama Item Kegiatan *</label>
                        <input type="text" name="name" class="form-input-v4" placeholder="Nama lengkap kegiatan" required>
                    </div>
                    <div>
                        <label class="form-label-custom">Pagu Anggaran (Rp) *</label>
                        <input type="number" name="pagu" class="form-input-v4 font-mono" placeholder="925600000" min="0" step="1" required>
                    </div>
                    <button type="submit" class="btn-bps btn-bps-primary w-full py-3">➕ Tambah Item</button>
                </form>

                <template x-if="editMode && editItem">
                    <form :action="'/master/items/' + editItem.id" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div>
                            <label class="form-label-custom">Kode Item</label>
                            <input type="text" class="form-input-v4 bg-slate-100 font-mono" :value="editItem.code" disabled>
                        </div>
                        <div>
                            <label class="form-label-custom">Nama Item Kegiatan *</label>
                            <input type="text" name="name" class="form-input-v4" :value="editItem.name" required>
                        </div>
                        <div>
                            <label class="form-label-custom">Pagu Anggaran (Rp) *</label>
                            <input type="number" name="pagu" class="form-input-v4 font-mono" :value="editItem.pagu" min="0" step="1" required>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="btn-bps btn-bps-primary flex-1">💾 Simpan</button>
                            <button type="button" @click="editMode = false; editItem = null" class="btn-bps btn-bps-secondary">Batal</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>

    {{-- ACCOUNTS TAB --}}
    <div x-show="tab === 'accounts'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 table-container-v4">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h2 class="text-sm font-extrabold text-slate-900">Daftar Akun ({{ $accounts->total() }} Total Akun)</h2>
                </div>
                <table class="table-v4">
                    <thead><tr><th class="w-28 text-center">Kode</th><th>Nama Akun</th><th>Sub-Komponen</th></tr></thead>
                    <tbody>
                        @foreach($accounts as $acc)
                        <tr>
                            <td class="text-center whitespace-nowrap"><span class="font-mono text-xs font-bold text-blue-900 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-lg">{{ $acc->code }}</span></td>
                            <td class="font-bold text-xs text-slate-900">{{ $acc->name }}</td>
                            <td class="text-xs text-slate-500">{{ $acc->subComponent->code }} — {{ Str::limit($acc->subComponent->component->subOutput->code, 15) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Pagination Links for Accounts --}}
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                    {{ $accounts->appends(request()->query())->links() }}
                </div>
            </div>

            <div class="card-corporate p-6">
                <h2 class="text-sm font-extrabold text-slate-900 mb-4">➕ Tambah Akun</h2>
                <form action="{{ route('master.accounts.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label-custom">Sub-Komponen *</label>
                        <select name="sub_component_id" class="form-input-v4 text-xs" required>
                            <option value="">— Pilih Sub-Komponen —</option>
                            @foreach($subComponents as $sc)
                            <option value="{{ $sc->id }}">[{{ $sc->code }}] {{ Str::limit($sc->name, 30) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label-custom">Kode Akun *</label>
                        <input type="text" name="code" class="form-input-v4 font-mono" placeholder="521213" maxlength="10" required>
                    </div>
                    <div>
                        <label class="form-label-custom">Nama Akun *</label>
                        <input type="text" name="name" class="form-input-v4" placeholder="Belanja Honor Output Kegiatan" required>
                    </div>
                    <button type="submit" class="btn-bps btn-bps-primary w-full py-3">➕ Tambah Akun</button>
                </form>
            </div>
        </div>
    </div>

    {{-- FISCAL YEAR TAB --}}
    <div x-show="tab === 'fiscal'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 table-container-v4">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h2 class="text-sm font-extrabold text-slate-900">Tahun Anggaran DIPA</h2>
                </div>
                <table class="table-v4">
                    <thead><tr><th class="w-32">Tahun</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($fiscalYears as $fy)
                        <tr>
                            <td class="font-mono text-base font-black text-blue-900">{{ $fy->year }}</td>
                            <td>
                                @if($fy->is_active)
                                    <span class="badge-corp badge-corp-approved">
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="badge-corp badge-corp-pending">
                                        <span>Nonaktif</span>
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-corporate p-6">
                <h2 class="text-sm font-extrabold text-slate-900 mb-4">➕ Tambah Tahun Anggaran</h2>
                <form action="{{ route('master.fiscal-years.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label-custom">Tahun Anggaran *</label>
                        <input type="number" name="year" class="form-input-v4 font-mono" placeholder="2027" min="2024" max="2099" required>
                    </div>
                    <label class="flex items-center gap-3 text-xs font-bold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded text-blue-600">
                        <span>Jadikan Tahun Anggaran Aktif</span>
                    </label>
                    <button type="submit" class="btn-bps btn-bps-primary w-full py-3">➕ Tambah Tahun</button>
                </form>
            </div>
        </div>
    </div>

    {{-- SUB-OUTPUTS TAB --}}
    <div x-show="tab === 'suboutputs'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 table-container-v4">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h2 class="text-sm font-extrabold text-slate-900">Sub-Output ({{ $subOutputs->count() }})</h2>
                </div>
                <table class="table-v4">
                    <thead><tr><th class="w-32 text-center">Kode</th><th>Nama Sub-Output</th><th>Output</th></tr></thead>
                    <tbody>
                        @foreach($subOutputs as $so)
                        <tr>
                            <td class="text-center whitespace-nowrap"><span class="font-mono text-xs font-bold text-blue-900 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-lg">{{ $so->code }}</span></td>
                            <td class="font-bold text-xs text-slate-900">{{ Str::limit($so->name, 45) }}</td>
                            <td class="text-xs text-slate-500 font-mono">{{ $so->output->code }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-corporate p-6">
                <h2 class="text-sm font-extrabold text-slate-900 mb-4">➕ Tambah Sub-Output</h2>
                <form action="{{ route('master.sub-outputs.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label-custom">Output *</label>
                        <select name="output_id" class="form-input-v4 text-xs" required>
                            @foreach($outputs as $out)<option value="{{ $out->id }}">[{{ $out->code }}] {{ $out->name }}</option>@endforeach
                        </select>
                    </div>
                    <div><label class="form-label-custom">Kode *</label><input type="text" name="code" class="form-input-v4 font-mono" placeholder="BMA.006" required></div>
                    <div><label class="form-label-custom">Nama *</label><input type="text" name="name" class="form-input-v4" required></div>
                    <button type="submit" class="btn-bps btn-bps-primary w-full py-3">➕ Tambah</button>
                </form>
            </div>
        </div>
    </div>

    {{-- COMPONENTS TAB --}}
    <div x-show="tab === 'components'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 table-container-v4">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h2 class="text-sm font-extrabold text-slate-900">Komponen ({{ $components->count() }})</h2>
                </div>
                <table class="table-v4">
                    <thead><tr><th class="w-28 text-center">Kode</th><th>Nama Komponen</th><th>Sub-Output</th></tr></thead>
                    <tbody>
                        @foreach($components as $comp)
                        <tr>
                            <td class="text-center whitespace-nowrap"><span class="font-mono text-xs font-bold text-blue-900 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-lg">{{ $comp->code }}</span></td>
                            <td class="font-bold text-xs text-slate-900">{{ Str::limit($comp->name, 40) }}</td>
                            <td class="text-xs text-slate-500 font-mono">{{ $comp->subOutput->code }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-corporate p-6">
                <h2 class="text-sm font-extrabold text-slate-900 mb-4">➕ Tambah Komponen</h2>
                <form action="{{ route('master.components.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label-custom">Sub-Output *</label>
                        <select name="sub_output_id" class="form-input-v4 text-xs" required>
                            @foreach($subOutputs as $so)<option value="{{ $so->id }}">[{{ $so->code }}] {{ Str::limit($so->name, 35) }}</option>@endforeach
                        </select>
                    </div>
                    <div><label class="form-label-custom">Kode *</label><input type="text" name="code" class="form-input-v4 font-mono" placeholder="005" required></div>
                    <div><label class="form-label-custom">Nama *</label><input type="text" name="name" class="form-input-v4" required></div>
                    <button type="submit" class="btn-bps btn-bps-primary w-full py-3">➕ Tambah</button>
                </form>
            </div>
        </div>
    </div>

    {{-- SUB-COMPONENTS TAB --}}
    <div x-show="tab === 'subcomponents'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 table-container-v4">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h2 class="text-sm font-extrabold text-slate-900">Sub-Komponen ({{ $subComponents->count() }})</h2>
                </div>
                <table class="table-v4">
                    <thead><tr><th class="w-28 text-center">Kode</th><th>Nama Sub-Komponen</th><th>Komponen</th></tr></thead>
                    <tbody>
                        @foreach($subComponents as $sc)
                        <tr>
                            <td class="text-center whitespace-nowrap"><span class="font-mono text-xs font-bold text-blue-900 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-lg">{{ $sc->code }}</span></td>
                            <td class="font-bold text-xs text-slate-900">{{ Str::limit($sc->name, 35) }}</td>
                            <td class="text-xs text-slate-500 font-mono">{{ $sc->component->code }} / {{ $sc->component->subOutput->code }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-corporate p-6">
                <h2 class="text-sm font-extrabold text-slate-900 mb-4">➕ Tambah Sub-Komponen</h2>
                <form action="{{ route('master.sub-components.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label-custom">Komponen *</label>
                        <select name="component_id" class="form-input-v4 text-xs" required>
                            @foreach($components as $c)<option value="{{ $c->id }}">[{{ $c->code }}] {{ Str::limit($c->name, 30) }} ({{ $c->subOutput->code }})</option>@endforeach
                        </select>
                    </div>
                    <div><label class="form-label-custom">Kode *</label><input type="text" name="code" class="form-input-v4 font-mono" placeholder="005.0A" required></div>
                    <div><label class="form-label-custom">Nama *</label><input type="text" name="name" class="form-input-v4" placeholder="TANPA SUB KOMPONEN" required></div>
                    <button type="submit" class="btn-bps btn-bps-primary w-full py-3">➕ Tambah</button>
                </form>
            </div>
        </div>
    </div>

    {{-- OUTPUTS TAB --}}
    <div x-show="tab === 'outputs'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 table-container-v4">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h2 class="text-sm font-extrabold text-slate-900">Output ({{ $outputs->count() }})</h2>
                </div>
                <table class="table-v4">
                    <thead><tr><th class="w-28 text-center">Kode</th><th>Nama Output</th><th>Program</th></tr></thead>
                    <tbody>
                        @foreach($outputs as $out)
                        <tr>
                            <td class="text-center whitespace-nowrap"><span class="font-mono text-xs font-bold text-blue-900 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-lg">{{ $out->code }}</span></td>
                            <td class="font-bold text-xs text-slate-900">{{ $out->name }}</td>
                            <td class="text-xs text-slate-500 font-mono">{{ $out->program->code }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-corporate p-6">
                <h2 class="text-sm font-extrabold text-slate-900 mb-4">➕ Tambah Output</h2>
                <form action="{{ route('master.outputs.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label-custom">Program *</label>
                        <select name="program_id" class="form-input-v4 text-xs" required>
                            @foreach($programs as $p)<option value="{{ $p->id }}">[{{ $p->code }}] {{ Str::limit($p->name, 35) }}</option>@endforeach
                        </select>
                    </div>
                    <div><label class="form-label-custom">Kode *</label><input type="text" name="code" class="form-input-v4 font-mono" placeholder="BMA" required></div>
                    <div><label class="form-label-custom">Nama *</label><input type="text" name="name" class="form-input-v4" required></div>
                    <button type="submit" class="btn-bps btn-bps-primary w-full py-3">➕ Tambah</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
