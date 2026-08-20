@extends('layouts.app')
@section('title', 'Arsip Keuangan POK')

@section('content')
<div class="space-y-8">

    {{-- ── TOP HERO SECTION: INSTANT SEARCH (SEARCH-FIRST DIRECTORY) ── --}}
    <div class="relative overflow-hidden rounded-2xl p-8 text-white shadow-lg"
         style="background: var(--color-primary-900);">
        {{-- Decorative glow --}}
        <div class="absolute top-0 right-0 w-48 h-48 rounded-full pointer-events-none"
             style="background: radial-gradient(circle, rgba(61,135,204,0.2) 0%, transparent 70%); transform: translate(30%, -30%);"></div>
        <div class="max-w-3xl relative z-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg mb-3 text-xs font-extrabold"
                 style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: rgba(255,255,255,0.85);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span>SEARCH-FIRST DIRECTORY BROWSER</span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight">
                Browser Arsip Keuangan POK
            </h1>
            <p class="text-xs sm:text-sm font-medium mt-1 mb-6 leading-relaxed" style="color: rgba(255,255,255,0.8);">
                Temukan cepat dokumen SPJ &amp; kegiatan berdasarkan Kode Item (misal: <span class="font-mono font-bold" style="color: #FBD063;">001366</span>), Kode Akun (<span class="font-mono font-bold" style="color: #FBD063;">521213</span>), atau Kata Kunci Kegiatan.
            </p>

            <form method="GET" action="{{ route('items.index') }}" class="flex flex-col sm:flex-row gap-3">
                @if($filter) <input type="hidden" name="filter" value="{{ $filter }}"> @endif
                @if($programId) <input type="hidden" name="program_id" value="{{ $programId }}"> @endif
                @if($outputId) <input type="hidden" name="output_id" value="{{ $outputId }}"> @endif
                @if($subOutputId) <input type="hidden" name="sub_output_id" value="{{ $subOutputId }}"> @endif

                <div class="relative flex-1">
                    <input type="text"
                           name="search"
                           id="search-input"
                           value="{{ $search }}"
                           placeholder="Ketik Kode Item (001366), Kode Akun, atau Nama Kegiatan..."
                           class="w-full pl-12 pr-4 py-3.5 rounded-xl text-sm font-medium outline-none"
                           style="background: #fff; color: var(--color-neutral-700); border: 1.5px solid var(--color-neutral-300); box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);"
                           aria-label="Cari item kegiatan">
                    <span class="absolute left-4 top-3.5" style="color: var(--color-neutral-500);" aria-hidden="true">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="sakdi-btn font-extrabold text-sm px-6 py-3.5"
                            style="background: var(--color-accent); color: #fff; border-color: var(--color-accent); min-height: 48px;">
                        Cari Item
                    </button>
                    @if($search || $subOutputId || $outputId || $programId || $filter)
                        <a href="{{ route('items.index') }}"
                           class="sakdi-btn text-sm px-5 py-3.5"
                           style="background: rgba(255,255,255,0.1); color: #fff; border-color: rgba(255,255,255,0.25); min-height: 48px;">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>


    {{-- ── MIDDLE SECTION: CASCADING FILTER DROPDOWNS ── --}}
    <div class="sakdi-card p-6">
        <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full" style="background: var(--color-primary);"></span>
                <h2 class="text-xs font-black uppercase tracking-wider" style="color: var(--color-neutral-700);">CASCADING DIRECTORY FILTER (NAVIGASI 3 TINGKAT)</h2>
            </div>
            @if($selectedSubOutput)
                <div class="inline-flex items-center gap-2 text-xs font-extrabold sakdi-badge sakdi-badge-primary px-3.5 py-1.5">
                    <span>📌 Sub-Output Aktif:</span>
                    <span class="num-mono">{{ $selectedSubOutput->code }}</span>
                    <span>{{ Str::limit($selectedSubOutput->name, 30) }}</span>
                </div>
            @endif
        </div>


        <form method="GET" action="{{ route('items.index') }}" id="cascadingFilterForm" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @if($search) <input type="hidden" name="search" value="{{ $search }}"> @endif
            @if($filter) <input type="hidden" name="filter" value="{{ $filter }}"> @endif

            {{-- Dropdown 1: Program --}}
            <div>
                <label class="form-label-custom">Pilih Program</label>
                <select name="program_id" onchange="onProgramChange(this)" class="form-input-v4 text-xs font-semibold">
                    <option value="">-- Semua Program --</option>
                    @foreach($programs as $p)
                        <option value="{{ $p->id }}" {{ $programId == $p->id ? 'selected' : '' }}>
                            [{{ $p->code }}] {{ Str::limit($p->name, 35) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown 2: Output --}}
            <div>
                <label class="form-label-custom">Pilih Output</label>
                <select name="output_id" onchange="onOutputChange(this)" class="form-input-v4 text-xs font-semibold">
                    <option value="">-- Semua Output --</option>
                    @foreach($outputs as $o)
                        <option value="{{ $o->id }}" {{ $outputId == $o->id ? 'selected' : '' }}>
                            [{{ $o->code }}] {{ Str::limit($o->name, 35) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown 3: Sub-Output --}}
            <div>
                <label class="form-label-custom">Pilih Sub-Output</label>
                <select name="sub_output_id" onchange="this.form.submit()" class="form-input-v4 text-xs font-semibold">
                    <option value="">-- Semua Sub-Output --</option>
                    @foreach($subOutputs as $so)
                        <option value="{{ $so->id }}" {{ $subOutputId == $so->id ? 'selected' : '' }}>
                            [{{ $so->code }}] {{ Str::limit($so->name, 35) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <script>
        function onProgramChange(select) {
            const form = select.form;
            const outputSelect = form.querySelector('[name="output_id"]');
            const subOutputSelect = form.querySelector('[name="sub_output_id"]');
            if (outputSelect) outputSelect.value = '';
            if (subOutputSelect) subOutputSelect.value = '';
            form.submit();
        }

        function onOutputChange(select) {
            const form = select.form;
            const subOutputSelect = form.querySelector('[name="sub_output_id"]');
            if (subOutputSelect) subOutputSelect.value = '';
            form.submit();
        }
        </script>

        {{-- Quick Filter Pills --}}
        <div class="flex items-center justify-between flex-wrap gap-3 mt-6 pt-5 border-t border-slate-100">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-xs font-bold text-slate-500 mr-1">Filter Status Verifikasi:</span>

                <a href="{{ route('items.index', request()->except('filter')) }}"
                   class="btn-bps btn-bps-sm {{ !$filter ? 'btn-bps-primary' : 'btn-bps-secondary' }}">
                    Semua ({{ $stats['total'] }})
                </a>

                <a href="{{ route('items.index', array_merge(request()->query(), ['filter' => 'pending'])) }}"
                   class="btn-bps btn-bps-sm {{ $filter === 'pending' ? 'bg-amber-500 text-slate-950 font-black' : 'bg-amber-50 text-amber-900 border border-amber-200' }}">
                    ⏳ Pending ({{ $stats['pending'] }})
                </a>

                <a href="{{ route('items.index', array_merge(request()->query(), ['filter' => 'approved'])) }}"
                   class="btn-bps btn-bps-sm {{ $filter === 'approved' ? 'btn-bps-success' : 'bg-emerald-50 text-emerald-900 border border-emerald-200' }}">
                    ✅ Siap Cair ({{ $stats['approved'] }})
                </a>

                <a href="{{ route('items.index', array_merge(request()->query(), ['filter' => 'rejected'])) }}"
                   class="btn-bps btn-bps-sm {{ $filter === 'rejected' ? 'btn-bps-danger' : 'bg-red-50 text-red-900 border border-red-200' }}">
                    ❌ Ditolak ({{ $stats['rejected'] }})
                </a>
            </div>
        </div>
    </div>

    {{-- ── MAIN SECTION: DATA TABLE ── --}}
    <div class="table-container-v4">
        <div class="px-6 py-5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="text-sm font-extrabold text-slate-900">Daftar Item Kegiatan POK</h2>
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-blue-100 border border-blue-200 text-blue-900 font-mono">
                    {{ $items->total() }} Item Ditemukan
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table-v4">
                <thead>
                    <tr>
                        <th class="w-32 text-center">Kode Item</th>
                        <th>Nama Kegiatan / Item POK</th>
                        <th>Akun / Sub-Output</th>
                        <th class="text-right">Pagu Anggaran</th>
                        <th class="text-center">Berkas SPJ</th>
                        <th class="text-center">Status Verifikasi</th>
                        <th class="text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td class="text-center whitespace-nowrap">
                            <span class="font-mono text-xs font-bold text-blue-900 bg-blue-50 border border-blue-200 px-3 py-1.5 rounded-lg">
                                {{ $item->code }}
                            </span>
                        </td>
                        <td>
                            <div class="font-extrabold text-slate-900 text-sm leading-snug mb-0.5">{{ $item->name }}</div>
                            @if(str_contains($item->code, '001366') || str_contains($item->code, '001211'))
                                <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-900 border border-amber-300 text-[10px] font-black px-2.5 py-0.5 rounded">
                                    <span>⭐ MVP CORE FOCUS</span>
                                </span>
                            @endif
                        </td>
                        <td class="text-xs">
                            <div class="font-bold text-slate-800">Akun {{ $item->account->code }}</div>
                            <div class="text-[11px] text-slate-500 truncate max-w-xs">{{ $item->account->name }}</div>
                            <div class="text-[10px] font-mono text-blue-800 mt-0.5">
                                {{ $item->account->subComponent->component->subOutput->code }}
                            </div>
                        </td>
                        <td class="text-right font-mono font-bold text-emerald-800 text-sm whitespace-nowrap">
                            Rp {{ number_format($item->pagu, 0, ',', '.') }}
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <span class="text-xs font-extrabold px-3 py-1 rounded-full {{ $item->documents->count() > 0 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-400' }}">
                                📄 {{ $item->documents->count() }} File
                            </span>
                        </td>
                        <td class="text-center whitespace-nowrap">
                            @if($item->verification_status === 'APPROVED')
                                <span class="badge-corp badge-corp-approved">
                                    <span>Siap Cair</span>
                                </span>
                            @elseif($item->verification_status === 'REJECTED')
                                <span class="badge-corp badge-corp-rejected">
                                    <span>Ditolak</span>
                                </span>
                            @else
                                <span class="badge-corp badge-corp-pending">
                                    <span>Pending</span>
                                </span>
                            @endif
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <a href="{{ route('items.show', $item) }}" class="btn-bps btn-bps-primary btn-bps-sm">
                                <span>Workspace</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-16 text-slate-400">
                            <div class="text-4xl mb-3">🔍</div>
                            <div class="font-extrabold text-slate-700 text-base">Tidak ada item kegiatan yang cocok</div>
                            <div class="text-xs text-slate-400 mt-1">Coba kata kunci lain atau reset filter cascading directory.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $items->appends(request()->query())->links() }}
        </div>
    </div>

</div>
@endsection
