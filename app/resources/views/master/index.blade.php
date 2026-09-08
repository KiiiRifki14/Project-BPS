@extends('layouts.app')
@section('title', 'Manajemen Master Data POK')

@section('content')
<div class="space-y-8" x-data="{ tab: 'items' }">

    {{-- Page Header --}}
    <div class="sakdi-card w-full p-8 flex items-center justify-between flex-wrap gap-6"
         style="border-left: 4px solid var(--color-primary);">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg mb-2 text-xs font-extrabold"
                 style="background: var(--color-primary-50); border: 1px solid var(--color-primary-100); color: var(--color-primary-900);">
                <span>⚙️ KHUSUS SUPERVISOR &amp; ADMIN</span>
            </div>
            <h1 class="text-2xl font-black tracking-tight" style="color: var(--color-neutral-900);">
                Manajemen Master Data POK
            </h1>
            <p class="text-xs sm:text-sm font-medium mt-1" style="color: var(--color-neutral-500);">
                Kelola hirarki 8-level POK (Program, Output, Sub-Output, Komponen, Sub-Komponen, Akun, Item, &amp; Tahun Anggaran).
            </p>
        </div>
    </div>

    {{-- TABS BAR (BESAR & PROMINENT) --}}
    <div class="sakdi-card p-3 w-full">
        <div class="flex items-center gap-2 overflow-x-auto flex-wrap sm:flex-nowrap p-1">
            @foreach([
                ['id' => 'items', 'label' => '📋 Item Kegiatan', 'color' => '#0057A8'],
                ['id' => 'accounts', 'label' => '💳 Akun', 'color' => '#0284C7'],
                ['id' => 'subcomponents', 'label' => '🔷 Sub-Komponen', 'color' => '#2563EB'],
                ['id' => 'components', 'label' => '🔶 Komponen', 'color' => '#D97706'],
                ['id' => 'suboutputs', 'label' => '📦 Sub-Output', 'color' => '#7C3AED'],
                ['id' => 'outputs', 'label' => '📁 Output', 'color' => '#059669'],
                ['id' => 'fiscal', 'label' => '📅 Tahun Anggaran', 'color' => '#475569'],
            ] as $t)
            <button @click="tab = '{{ $t['id'] }}'"
                    type="button"
                    class="px-5 py-3.5 rounded-xl font-extrabold text-sm sm:text-base transition-all whitespace-nowrap flex items-center gap-2.5 border-2 cursor-pointer"
                    :style="tab === '{{ $t['id'] }}' 
                        ? 'background: {{ $t['color'] }}; color: #ffffff; border-color: {{ $t['color'] }}; box-shadow: 0 4px 14px rgba(0,0,0,0.18); font-weight:900; transform: scale(1.02);' 
                        : 'background: var(--color-white); color: var(--color-neutral-700); border-color: var(--color-neutral-200);'">
                <span>{{ $t['label'] }}</span>
                <span x-show="tab === '{{ $t['id'] }}'" class="w-2.5 h-2.5 rounded-full bg-white animate-pulse"></span>
            </button>
            @endforeach
        </div>
    </div>

    {{-- 1. ITEMS TAB --}}
    <div x-show="tab === 'items'" x-transition>
        <div class="sakdi-card p-4 mb-6 flex items-center justify-between" style="background: var(--color-primary-50); border: 1px solid var(--color-primary-100);">
            <div class="flex items-center gap-3">
                <span class="text-xl">📋</span>
                <div>
                    <h2 class="text-sm font-extrabold" style="color: var(--color-primary-900);">TAB AKTIF: ITEM KEGIATAN POK</h2>
                    <p class="text-xs font-medium" style="color: var(--color-neutral-600);">Mengelola rincian unit terujung POK (tempat dokumen SPJ diunggah).</p>
                </div>
            </div>
            <span class="sakdi-badge sakdi-badge-primary font-mono text-xs">{{ $items->total() }} Total Item</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 sakdi-table-wrapper">
                <div class="px-6 py-4 border-b" style="background: var(--color-neutral-50);">
                    <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900);">Daftar Item Kegiatan</h2>
                </div>
                <table class="sakdi-table">
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
                                <span class="num-mono text-xs font-bold px-2.5 py-1 rounded-lg"
                                      style="color: var(--color-primary-900); background: var(--color-primary-50); border: 1px solid var(--color-primary-100);">
                                    {{ $item->code }}
                                </span>
                            </td>
                            <td>
                                <div class="font-extrabold text-xs" style="color: var(--color-neutral-900);">
                                    {{ Str::limit($item->name, 50) }}
                                </div>
                            </td>
                            <td class="text-xs">
                                <div class="font-bold num-mono" style="color: var(--color-neutral-700);">[{{ $item->account->code ?? '-' }}]</div>
                                <div class="text-[10px] num-mono mt-0.5" style="color: var(--color-primary);">
                                    {{ Str::limit($item->account->subComponent->component->subOutput->code ?? '', 15) }}
                                </div>
                            </td>
                            <td class="text-right num-mono font-bold text-xs whitespace-nowrap" style="color: var(--color-positive-700);">
                                Rp {{ number_format($item->pagu, 0, ',', '.') }}
                            </td>
                            <td class="text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm"
                                            @click="$dispatch('open-edit-item', {{ $item->toJson() }})" title="Edit Item">
                                        ✏️ Edit
                                    </button>
                                    <form action="{{ route('master.items.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Hapus item {{ $item->code }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="sakdi-btn sakdi-btn-danger sakdi-btn-sm" title="Hapus Item">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t" style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
                    {{ $items->appends(request()->query())->links() }}
                </div>
            </div>

            {{-- Item Form Column --}}
            <div class="sakdi-card p-6 lg:sticky lg:top-24"
                 x-data="{ editMode: false, editItem: null }"
                 @open-edit-item.window="editItem = $event.detail; editMode = true">
                <h3 class="text-sm font-extrabold mb-4" style="color: var(--color-neutral-900);" x-text="editMode ? 'Edit Item Kegiatan' : 'Tambah Item Baru'"></h3>
                <form :action="editMode ? '/master/items/' + editItem.id : '{{ route('master.items.store') }}'" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PATCH">
                    </template>

                    <div>
                        <label class="sakdi-label sakdi-label-required">Akun POK</label>
                        <select name="account_id" class="sakdi-select" required x-model="editItem ? editItem.account_id : ''">
                            <option value="" disabled selected>-- Pilih Akun --</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">[{{ $acc->code }}] {{ Str::limit($acc->name, 35) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="sakdi-label sakdi-label-required">Kode Item (6 Digit)</label>
                        <input type="text" name="code" class="sakdi-input num-mono" placeholder="001366" maxlength="10" required x-model="editItem ? editItem.code : ''">
                    </div>

                    <div>
                        <label class="sakdi-label sakdi-label-required">Nama Item Kegiatan</label>
                        <input type="text" name="name" class="sakdi-input" placeholder="Nama item kegiatan..." required x-model="editItem ? editItem.name : ''">
                    </div>

                    <div>
                        <label class="sakdi-label sakdi-label-required">Pagu Anggaran (Rp)</label>
                        <input type="number" name="pagu" class="sakdi-input num-mono" placeholder="925600000" step="1" required x-model="editItem ? editItem.pagu : ''">
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="sakdi-btn sakdi-btn-primary flex-1">
                            <span x-text="editMode ? 'Simpan Perubahan' : 'Tambah Item'"></span>
                        </button>
                        <button type="button" x-show="editMode" @click="editMode = false; editItem = null" class="sakdi-btn sakdi-btn-secondary">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 2. ACCOUNTS TAB --}}
    <div x-show="tab === 'accounts'" x-transition>
        <div class="sakdi-card p-4 mb-6 flex items-center justify-between" style="background: #F0F9FF; border: 1px solid #BAE6FD;">
            <div class="flex items-center gap-3">
                <span class="text-xl">💳</span>
                <div>
                    <h2 class="text-sm font-extrabold" style="color: #0369A1;">TAB AKTIF: AKUN POK (MATA ANGGARAN)</h2>
                    <p class="text-xs font-medium" style="color: var(--color-neutral-600);">Mengelola daftar Kode Akun 6-digit (seperti 521213, 521211, 524114).</p>
                </div>
            </div>
            <span class="sakdi-badge sakdi-badge-primary font-mono text-xs">{{ $accounts->total() }} Total Akun</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 sakdi-table-wrapper">
                <div class="px-6 py-4 border-b flex items-center justify-between" style="background: var(--color-neutral-50);">
                    <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900);">Daftar Akun POK</h2>
                </div>
                <table class="sakdi-table">
                    <thead>
                        <tr>
                            <th class="w-28 text-center">Kode Akun</th>
                            <th>Nama Akun</th>
                            <th>Sub-Komponen</th>
                            <th class="text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accounts as $acc)
                        <tr>
                            <td class="text-center whitespace-nowrap">
                                <span class="num-mono text-xs font-bold px-2.5 py-1 rounded-lg" style="color: var(--color-primary); background: var(--color-primary-50);">
                                    {{ $acc->code }}
                                </span>
                            </td>
                            <td class="font-extrabold text-xs" style="color: var(--color-neutral-900);">
                                {{ $acc->name }}
                            </td>
                            <td class="text-xs num-mono" style="color: var(--color-neutral-600);">
                                [{{ $acc->subComponent->code ?? '-' }}] {{ Str::limit($acc->subComponent->name ?? '', 30) }}
                            </td>
                            <td class="text-center whitespace-nowrap">
                                <form action="{{ route('master.accounts.destroy', $acc) }}" method="POST"
                                      onsubmit="return confirm('Hapus akun {{ $acc->code }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="sakdi-btn sakdi-btn-danger sakdi-btn-sm" title="Hapus Akun">🗑️ Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t" style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
                    {{ $accounts->appends(request()->query())->links() }}
                </div>
            </div>

            <div class="sakdi-card p-6 lg:sticky lg:top-24">
                <h3 class="text-sm font-extrabold mb-4" style="color: var(--color-neutral-900);">Tambah Akun Baru</h3>
                <form action="{{ route('master.accounts.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="sakdi-label sakdi-label-required">Sub-Komponen POK</label>
                        <select name="sub_component_id" class="sakdi-select" required>
                            <option value="" disabled selected>-- Pilih Sub-Komponen --</option>
                            @foreach($subComponents as $sc)
                                <option value="{{ $sc->id }}">[{{ $sc->code }}] {{ Str::limit($sc->name, 35) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="sakdi-label sakdi-label-required">Kode Akun (6 Digit)</label>
                        <input type="text" name="code" class="sakdi-input num-mono" placeholder="521213" maxlength="10" required>
                    </div>
                    <div>
                        <label class="sakdi-label sakdi-label-required">Nama Akun</label>
                        <input type="text" name="name" class="sakdi-input" placeholder="Honorarium Operasional..." required>
                    </div>
                    <button type="submit" class="sakdi-btn sakdi-btn-primary w-full">Tambah Akun Baru</button>
                </form>
            </div>
        </div>
    </div>

    {{-- 3. SUB-COMPONENTS TAB --}}
    <div x-show="tab === 'subcomponents'" x-transition>
        <div class="sakdi-card p-4 mb-6 flex items-center justify-between" style="background: #EFF6FF; border: 1px solid #BFDBFE;">
            <div class="flex items-center gap-3">
                <span class="text-xl">🔷</span>
                <div>
                    <h2 class="text-sm font-extrabold" style="color: #1D4ED8;">TAB AKTIF: SUB-KOMPONEN</h2>
                    <p class="text-xs font-medium" style="color: var(--color-neutral-600);">Mengelola daftar Sub-Komponen POK (seperti 051.0A, 005.0B).</p>
                </div>
            </div>
            <span class="sakdi-badge sakdi-badge-primary font-mono text-xs">{{ count($subComponents) }} Total Sub-Komponen</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 sakdi-table-wrapper">
                <div class="px-6 py-4 border-b" style="background: var(--color-neutral-50);">
                    <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900);">Daftar Sub-Komponen</h2>
                </div>
                <table class="sakdi-table">
                    <thead>
                        <tr>
                            <th class="w-28 text-center">Kode</th>
                            <th>Nama Sub-Komponen</th>
                            <th>Komponen</th>
                            <th class="text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subComponents as $sc)
                        <tr>
                            <td class="text-center font-bold num-mono text-xs">{{ $sc->code }}</td>
                            <td class="font-extrabold text-xs">{{ $sc->name }}</td>
                            <td class="text-xs font-mono">[{{ $sc->component->code ?? '-' }}] {{ $sc->component->name ?? '-' }}</td>
                            <td class="text-center whitespace-nowrap">
                                <form action="{{ route('master.sub-components.destroy', $sc) }}" method="POST"
                                      onsubmit="return confirm('Hapus sub-komponen {{ $sc->code }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="sakdi-btn sakdi-btn-danger sakdi-btn-sm">🗑️ Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="sakdi-card p-6">
                <h3 class="text-sm font-extrabold mb-4" style="color: var(--color-neutral-900);">Tambah Sub-Komponen</h3>
                <form action="{{ route('master.sub-components.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="sakdi-label sakdi-label-required">Komponen POK</label>
                        <select name="component_id" class="sakdi-select" required>
                            <option value="" disabled selected>-- Pilih Komponen --</option>
                            @foreach($components as $comp)
                                <option value="{{ $comp->id }}">[{{ $comp->code }}] {{ $comp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="sakdi-label sakdi-label-required">Kode Sub-Komponen</label>
                        <input type="text" name="code" class="sakdi-input num-mono" placeholder="051.0A" required>
                    </div>
                    <div>
                        <label class="sakdi-label sakdi-label-required">Nama Sub-Komponen</label>
                        <input type="text" name="name" class="sakdi-input" placeholder="Tanpa Sub Komponen" required>
                    </div>
                    <button type="submit" class="sakdi-btn sakdi-btn-primary w-full">Tambah Sub-Komponen</button>
                </form>
            </div>
        </div>
    </div>

    {{-- 4. COMPONENTS TAB --}}
    <div x-show="tab === 'components'" x-transition>
        <div class="sakdi-card p-4 mb-6 flex items-center justify-between" style="background: #FFFBEB; border: 1px solid #FDE68A;">
            <div class="flex items-center gap-3">
                <span class="text-xl">🔶</span>
                <div>
                    <h2 class="text-sm font-extrabold" style="color: #B45309;">TAB AKTIF: KOMPONEN POK</h2>
                    <p class="text-xs font-medium" style="color: var(--color-neutral-600);">Mengelola kelompok kegiatan POK (seperti 051, 005, 530).</p>
                </div>
            </div>
            <span class="sakdi-badge sakdi-badge-primary font-mono text-xs">{{ count($components) }} Total Komponen</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 sakdi-table-wrapper">
                <div class="px-6 py-4 border-b" style="background: var(--color-neutral-50);">
                    <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900);">Daftar Komponen</h2>
                </div>
                <table class="sakdi-table">
                    <thead>
                        <tr>
                            <th class="w-28 text-center">Kode</th>
                            <th>Nama Komponen</th>
                            <th>Sub-Output</th>
                            <th class="text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($components as $comp)
                        <tr>
                            <td class="text-center font-bold num-mono text-xs">{{ $comp->code }}</td>
                            <td class="font-extrabold text-xs">{{ $comp->name }}</td>
                            <td class="text-xs font-mono">[{{ $comp->subOutput->code ?? '-' }}] {{ $comp->subOutput->name ?? '-' }}</td>
                            <td class="text-center whitespace-nowrap">
                                <form action="{{ route('master.components.destroy', $comp) }}" method="POST"
                                      onsubmit="return confirm('Hapus komponen {{ $comp->code }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="sakdi-btn sakdi-btn-danger sakdi-btn-sm">🗑️ Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="sakdi-card p-6">
                <h3 class="text-sm font-extrabold mb-4" style="color: var(--color-neutral-900);">Tambah Komponen</h3>
                <form action="{{ route('master.components.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="sakdi-label sakdi-label-required">Sub-Output</label>
                        <select name="sub_output_id" class="sakdi-select" required>
                            <option value="" disabled selected>-- Pilih Sub-Output --</option>
                            @foreach($subOutputs as $so)
                                <option value="{{ $so->id }}">[{{ $so->code }}] {{ $so->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="sakdi-label sakdi-label-required">Kode Komponen</label>
                        <input type="text" name="code" class="sakdi-input num-mono" placeholder="051" required>
                    </div>
                    <div>
                        <label class="sakdi-label sakdi-label-required">Nama Komponen</label>
                        <input type="text" name="name" class="sakdi-input" placeholder="Pelaksanaan Sensus SE2026..." required>
                    </div>
                    <button type="submit" class="sakdi-btn sakdi-btn-primary w-full">Tambah Komponen</button>
                </form>
            </div>
        </div>
    </div>

    {{-- 5. SUB-OUTPUTS TAB --}}
    <div x-show="tab === 'suboutputs'" x-transition>
        <div class="sakdi-card p-4 mb-6 flex items-center justify-between" style="background: #F3E8FF; border: 1px solid #E9D5FF;">
            <div class="flex items-center gap-3">
                <span class="text-xl">📦</span>
                <div>
                    <h2 class="text-sm font-extrabold" style="color: #6B21A8;">TAB AKTIF: SUB-OUTPUT POK</h2>
                    <p class="text-xs font-medium" style="color: var(--color-neutral-600);">Mengelola kegiatan spesifik unit kerja (seperti BMA.006 Sensus Ekonomi).</p>
                </div>
            </div>
            <span class="sakdi-badge sakdi-badge-primary font-mono text-xs">{{ count($subOutputs) }} Total Sub-Output</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 sakdi-table-wrapper">
                <div class="px-6 py-4 border-b" style="background: var(--color-neutral-50);">
                    <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900);">Daftar Sub-Output</h2>
                </div>
                <table class="sakdi-table">
                    <thead>
                        <tr>
                            <th class="w-28 text-center">Kode</th>
                            <th>Nama Sub-Output</th>
                            <th>Output</th>
                            <th class="text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subOutputs as $so)
                        <tr>
                            <td class="text-center font-bold num-mono text-xs">{{ $so->code }}</td>
                            <td class="font-extrabold text-xs">{{ $so->name }}</td>
                            <td class="text-xs font-mono">[{{ $so->output->code ?? '-' }}] {{ $so->output->name ?? '-' }}</td>
                            <td class="text-center whitespace-nowrap">
                                <form action="{{ route('master.sub-outputs.destroy', $so) }}" method="POST"
                                      onsubmit="return confirm('Hapus sub-output {{ $so->code }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="sakdi-btn sakdi-btn-danger sakdi-btn-sm">🗑️ Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="sakdi-card p-6">
                <h3 class="text-sm font-extrabold mb-4" style="color: var(--color-neutral-900);">Tambah Sub-Output</h3>
                <form action="{{ route('master.sub-outputs.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="sakdi-label sakdi-label-required">Output</label>
                        <select name="output_id" class="sakdi-select" required>
                            <option value="" disabled selected>-- Pilih Output --</option>
                            @foreach($outputs as $out)
                                <option value="{{ $out->id }}">[{{ $out->code }}] {{ $out->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="sakdi-label sakdi-label-required">Kode Sub-Output</label>
                        <input type="text" name="code" class="sakdi-input num-mono" placeholder="BMA.006" required>
                    </div>
                    <div>
                        <label class="sakdi-label sakdi-label-required">Nama Sub-Output</label>
                        <input type="text" name="name" class="sakdi-input" placeholder="Layanan Sensus Ekonomi 2026..." required>
                    </div>
                    <button type="submit" class="sakdi-btn sakdi-btn-primary w-full">Tambah Sub-Output</button>
                </form>
            </div>
        </div>
    </div>

    {{-- 6. OUTPUTS TAB --}}
    <div x-show="tab === 'outputs'" x-transition>
        <div class="sakdi-card p-4 mb-6 flex items-center justify-between" style="background: #ECFDF5; border: 1px solid #A7F3D0;">
            <div class="flex items-center gap-3">
                <span class="text-xl">📁</span>
                <div>
                    <h2 class="text-sm font-extrabold" style="color: #047857;">TAB AKTIF: OUTPUT POK</h2>
                    <p class="text-xs font-medium" style="color: var(--color-neutral-600);">Mengelola keluaran program utama (seperti BMA, FAN, KAN).</p>
                </div>
            </div>
            <span class="sakdi-badge sakdi-badge-primary font-mono text-xs">{{ count($outputs) }} Total Output</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 sakdi-table-wrapper">
                <div class="px-6 py-4 border-b" style="background: var(--color-neutral-50);">
                    <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900);">Daftar Output</h2>
                </div>
                <table class="sakdi-table">
                    <thead>
                        <tr>
                            <th class="w-28 text-center">Kode</th>
                            <th>Nama Output</th>
                            <th>Program</th>
                            <th class="text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($outputs as $out)
                        <tr>
                            <td class="text-center font-bold num-mono text-xs">{{ $out->code }}</td>
                            <td class="font-extrabold text-xs">{{ $out->name }}</td>
                            <td class="text-xs font-mono">[{{ $out->program->code ?? '-' }}] {{ $out->program->name ?? '-' }}</td>
                            <td class="text-center whitespace-nowrap">
                                <form action="{{ route('master.outputs.destroy', $out) }}" method="POST"
                                      onsubmit="return confirm('Hapus output {{ $out->code }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="sakdi-btn sakdi-btn-danger sakdi-btn-sm">🗑️ Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="sakdi-card p-6">
                <h3 class="text-sm font-extrabold mb-4" style="color: var(--color-neutral-900);">Tambah Output</h3>
                <form action="{{ route('master.outputs.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="sakdi-label sakdi-label-required">Program</label>
                        <select name="program_id" class="sakdi-select" required>
                            <option value="" disabled selected>-- Pilih Program --</option>
                            @foreach($programs as $prog)
                                <option value="{{ $prog->id }}">[{{ $prog->code }}] {{ $prog->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="sakdi-label sakdi-label-required">Kode Output</label>
                        <input type="text" name="code" class="sakdi-input num-mono" placeholder="BMA" required>
                    </div>
                    <div>
                        <label class="sakdi-label sakdi-label-required">Nama Output</label>
                        <input type="text" name="name" class="sakdi-input" placeholder="Dukungan Manajemen..." required>
                    </div>
                    <button type="submit" class="sakdi-btn sakdi-btn-primary w-full">Tambah Output</button>
                </form>
            </div>
        </div>
    </div>

    {{-- 7. FISCAL YEAR TAB --}}
    <div x-show="tab === 'fiscal'" x-transition>
        <div class="sakdi-card p-4 mb-6 flex items-center justify-between" style="background: #F8FAFC; border: 1px solid #CBD5E1;">
            <div class="flex items-center gap-3">
                <span class="text-xl">📅</span>
                <div>
                    <h2 class="text-sm font-extrabold" style="color: #334155;">TAB AKTIF: TAHUN ANGGARAN DIPA</h2>
                    <p class="text-xs font-medium" style="color: var(--color-neutral-600);">Mengelola tahun pelaksanaan anggaran DIPA BPS Kabupaten Subang.</p>
                </div>
            </div>
            <span class="sakdi-badge sakdi-badge-primary font-mono text-xs">{{ count($fiscalYears) }} Total Tahun</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 sakdi-table-wrapper">
                <div class="px-6 py-4 border-b" style="background: var(--color-neutral-50);">
                    <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900);">Daftar Tahun Anggaran DIPA</h2>
                </div>
                <table class="sakdi-table">
                    <thead>
                        <tr>
                            <th class="w-28 text-center">Tahun</th>
                            <th>Status DIPA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fiscalYears as $fy)
                        <tr>
                            <td class="text-center font-black num-mono text-sm">{{ $fy->year }}</td>
                            <td>
                                <span class="sakdi-badge {{ $fy->is_active ? 'sakdi-badge-success' : 'sakdi-badge-neutral' }}">
                                    {{ $fy->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="sakdi-card p-6">
                <h3 class="text-sm font-extrabold mb-4" style="color: var(--color-neutral-900);">Tambah Tahun Anggaran</h3>
                <form action="{{ route('master.fiscal-years.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="sakdi-label sakdi-label-required">Tahun Anggaran</label>
                        <input type="number" name="year" class="sakdi-input num-mono" placeholder="2027" min="2024" max="2099" required>
                    </div>
                    <button type="submit" class="sakdi-btn sakdi-btn-primary w-full">Tambah Tahun Anggaran</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
