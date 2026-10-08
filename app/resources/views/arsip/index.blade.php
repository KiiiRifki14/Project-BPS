@extends('layouts.app')
@section('title', 'Arsip Keuangan POK')

@section('content')
<div class="space-y-8"
     x-data="{
         hierarchy: {{ json_encode($hierarchy) }},
         programId: '{{ $programId }}',
         outputId: '{{ $outputId }}',
         subOutputId: '{{ $subOutputId }}',

         get availableOutputs() {
             if (!this.programId) return [];
             const prog = this.hierarchy.find(p => p.id == this.programId);
             return prog ? (prog.outputs || []) : [];
         },

         get availableSubOutputs() {
             if (!this.outputId) return [];
             for (let prog of this.hierarchy) {
                 const out = (prog.outputs || []).find(o => o.id == this.outputId);
                 if (out) return out.sub_outputs || [];
             }
             return [];
         },

         onProgramChange() {
             this.outputId = '';
             this.subOutputId = '';
             $refs.filterForm.submit();
         },

         onOutputChange() {
             this.subOutputId = '';
             $refs.filterForm.submit();
         },

         onSubOutputChange() {
             $refs.filterForm.submit();
         }
     }">

    {{-- ── TOP HERO SECTION: INSTANT SEARCH (SEARCH-FIRST DIRECTORY) ── --}}
    <div class="relative overflow-hidden rounded-2xl p-5 sm:p-7 md:p-8 text-white shadow-lg w-full"
         style="background: var(--color-primary-900);">
        {{-- Decorative glow --}}
        <div class="absolute top-0 right-0 w-48 h-48 rounded-full pointer-events-none"
             style="background: radial-gradient(circle, rgba(61,135,204,0.2) 0%, transparent 70%); transform: translate(30%, -30%);"></div>
        <div class="w-full relative z-10">

            @if(auth()->user()->isBendahara())
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg mb-2 sm:mb-3 text-[11px] sm:text-xs font-extrabold"
                 style="background: rgba(245,158,11,0.2); border: 1px solid rgba(245,158,11,0.4); color: #FCD34D;">
                <span>🏦 PORTAL VERIFIKASI &amp; ARSIP BENDAHARA</span>
            </div>

            <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-white tracking-tight leading-tight">
                Verifikasi &amp; Arsip Keuangan POK
            </h1>
            <p class="text-xs sm:text-sm font-medium mt-1 mb-4 sm:mb-6 leading-relaxed" style="color: rgba(255,255,255,0.85);">
                Periksa kelengkapan berkas SPJ kegiatan, gunakan filter cascading POK, dan lakukan verifikasi pencairan anggaran.
            </p>
            @else
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg mb-2 sm:mb-3 text-[11px] sm:text-xs font-extrabold"
                 style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: rgba(255,255,255,0.85);">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span>SEARCH-FIRST DIRECTORY BROWSER</span>
            </div>

            <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-white tracking-tight leading-tight">
                Browser Arsip Keuangan POK
            </h1>
            <p class="text-xs sm:text-sm font-medium mt-1 mb-4 sm:mb-6 leading-relaxed" style="color: rgba(255,255,255,0.8);">
                Temukan cepat dokumen SPJ &amp; kegiatan berdasarkan Kode Item (misal: <span class="num-mono font-bold" style="color: #FBD063;">001366</span>), Kode Akun (<span class="num-mono font-bold" style="color: #FBD063;">521213</span>), atau Kata Kunci Kegiatan.
            </p>
            @endif

            <form method="GET" action="{{ route('items.index') }}" class="flex flex-col sm:flex-row gap-2.5 sm:gap-3">
                @if($filter) <input type="hidden" name="filter" value="{{ $filter }}"> @endif
                @if($programId) <input type="hidden" name="program_id" value="{{ $programId }}"> @endif
                @if($outputId) <input type="hidden" name="output_id" value="{{ $outputId }}"> @endif
                @if($subOutputId) <input type="hidden" name="sub_output_id" value="{{ $subOutputId }}"> @endif

                <div class="relative flex-1 min-w-0">
                    <input type="text"
                           name="search"
                           id="search-input"
                           value="{{ $search }}"
                           placeholder="Ketik Kode Item (001366), Kode Akun, atau Nama Kegiatan..."
                           class="w-full pl-11 pr-4 py-2.5 sm:py-3.5 rounded-xl text-xs sm:text-sm font-medium outline-none"
                           style="background: #fff; color: var(--color-neutral-700); border: 1.5px solid var(--color-neutral-300); box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);"
                           aria-label="Cari item kegiatan">
                    <span class="absolute left-3.5 top-2.5 sm:top-3.5" style="color: var(--color-neutral-500);" aria-hidden="true">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                </div>

                <div class="flex gap-2 flex-shrink-0">
                    <button type="submit" class="sakdi-btn font-extrabold text-xs sm:text-sm px-4 sm:px-6 py-2.5 sm:py-3.5 flex-1 sm:flex-initial"
                            style="background: var(--color-accent); color: #fff; border-color: var(--color-accent); min-height: 42px;">
                        <span>Cari Item</span>
                    </button>
                    @if($search || $subOutputId || $outputId || $programId || $filter)
                        <a href="{{ route('items.index') }}"
                           class="sakdi-btn text-xs sm:text-sm px-3.5 sm:px-5 py-2.5 sm:py-3.5"
                           style="background: rgba(255,255,255,0.1); color: #fff; border-color: rgba(255,255,255,0.25); min-height: 42px;">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>


    {{-- ── MIDDLE SECTION: CASCADING FILTER DROPDOWNS & STATUS PILLS ── --}}
    <div class="sakdi-card w-full p-4 sm:p-6">
        <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full" style="background: var(--color-primary);"></span>
                <h2 class="text-xs font-black uppercase tracking-wider" style="color: var(--color-neutral-700);">CASCADING DIRECTORY FILTER (NAVIGASI 3 TINGKAT)</h2>
            </div>
            @if($selectedSubOutput)
                <div class="inline-flex items-center gap-2 text-xs font-extrabold sakdi-badge sakdi-badge-primary px-3 py-1">
                    <span>📌 Sub-Output Aktif:</span>
                    <span class="num-mono">{{ $selectedSubOutput->code }}</span>
                    <span>{{ Str::limit($selectedSubOutput->name, 25) }}</span>
                </div>
            @endif
        </div>

        <form method="GET" action="{{ route('items.index') }}" x-ref="filterForm" class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4">
            @if($search) <input type="hidden" name="search" value="{{ $search }}"> @endif
            @if($filter) <input type="hidden" name="filter" value="{{ $filter }}"> @endif

            {{-- Dropdown 1: Program --}}
            <div>
                <label class="sakdi-label">Pilih Program</label>
                <select name="program_id" x-model="programId" @change="onProgramChange()" class="sakdi-select text-xs sm:text-sm">
                    <option value="">-- Semua Program --</option>
                    @foreach($programs as $p)
                        <option value="{{ $p->id }}">
                            [{{ $p->code }}] {{ Str::limit($p->name, 35) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown 2: Output (Dynamically updated via Alpine.js) --}}
            <div>
                <label class="sakdi-label">Pilih Output</label>
                <select name="output_id" x-model="outputId" @change="onOutputChange()" class="sakdi-select text-xs sm:text-sm">
                    <option value="">-- Semua Output --</option>
                    <template x-for="out in (programId ? availableOutputs : {{ json_encode($outputs) }})" :key="out.id">
                        <option :value="out.id" :selected="out.id == outputId" x-text="'[' + out.code + '] ' + out.name"></option>
                    </template>
                </select>
            </div>

            {{-- Dropdown 3: Sub-Output (Dynamically updated via Alpine.js) --}}
            <div>
                <label class="sakdi-label">Pilih Sub-Output</label>
                <select name="sub_output_id" x-model="subOutputId" @change="onSubOutputChange()" class="sakdi-select text-xs sm:text-sm">
                    <option value="">-- Semua Sub-Output --</option>
                    <template x-for="so in (outputId ? availableSubOutputs : {{ json_encode($subOutputs) }})" :key="so.id">
                        <option :value="so.id" :selected="so.id == subOutputId" x-text="'[' + so.code + '] ' + so.name"></option>
                    </template>
                </select>
            </div>
        </form>

        {{-- Filter Status Verifikasi Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-5 pt-4"
             style="border-top: 1px solid var(--color-neutral-300);">
            <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider mr-1" style="color: var(--color-neutral-500);">
                    Status:
                </span>

                <a href="{{ route('items.index', request()->except('filter')) }}"
                   class="sakdi-btn sakdi-btn-sm py-1.5 px-2.5 text-xs {{ !$filter ? 'sakdi-btn-primary' : 'sakdi-btn-secondary' }}">
                    <span>Semua</span>
                    <span class="sakdi-badge sakdi-badge-neutral ml-1 text-[10px]">{{ $stats['total'] }}</span>
                </a>

                <a href="{{ route('items.index', array_merge(request()->query(), ['filter' => 'pending'])) }}"
                   class="sakdi-btn sakdi-btn-sm py-1.5 px-2.5 text-xs {{ $filter === 'pending' ? 'sakdi-btn-accent' : 'sakdi-btn-secondary' }}">
                    <span>⏳ Pending</span>
                    <span class="sakdi-badge {{ $filter === 'pending' ? 'sakdi-badge-warning' : 'sakdi-badge-neutral' }} ml-1 text-[10px]">{{ $stats['pending'] }}</span>
                </a>

                <a href="{{ route('items.index', array_merge(request()->query(), ['filter' => 'approved'])) }}"
                   class="sakdi-btn sakdi-btn-sm py-1.5 px-2.5 text-xs {{ $filter === 'approved' ? 'sakdi-btn-success' : 'sakdi-btn-secondary' }}">
                    <span>✅ Siap Cair</span>
                    <span class="sakdi-badge {{ $filter === 'approved' ? 'sakdi-badge-success' : 'sakdi-badge-neutral' }} ml-1 text-[10px]">{{ $stats['approved'] }}</span>
                </a>

                <a href="{{ route('items.index', array_merge(request()->query(), ['filter' => 'rejected'])) }}"
                   class="sakdi-btn sakdi-btn-sm py-1.5 px-2.5 text-xs {{ $filter === 'rejected' ? 'sakdi-btn-danger' : 'sakdi-btn-secondary' }}">
                    <span>❌ Ditolak</span>
                    <span class="sakdi-badge {{ $filter === 'rejected' ? 'sakdi-badge-error' : 'sakdi-badge-neutral' }} ml-1 text-[10px]">{{ $stats['rejected'] }}</span>
                </a>
            </div>

            @if($filter || $search || $subOutputId || $outputId || $programId)
                <a href="{{ route('items.index') }}" class="text-xs font-extrabold hover:underline self-start sm:self-auto" style="color: var(--color-error);">
                    ✕ Reset Filter
                </a>
            @endif
        </div>
    </div>
    </div>

    {{-- ── MAIN SECTION: DATA TABLE ── --}}
    <div class="sakdi-table-wrapper w-full">
        <div class="px-6 py-5 flex items-center justify-between flex-wrap gap-4"
             style="background: var(--color-neutral-50); border-bottom: 1px solid var(--color-neutral-300);">
            <div class="flex items-center gap-3">
                <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900);">Daftar Item Kegiatan POK</h2>
                <span class="sakdi-badge sakdi-badge-primary font-mono">
                    {{ $items->total() }} Item Ditemukan
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="sakdi-table">
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
                            <span class="num-mono text-xs font-bold px-3 py-1.5 rounded-lg"
                                  style="color: var(--color-primary-900); background: var(--color-primary-50); border: 1px solid var(--color-primary-100);">
                                {{ $item->code }}
                            </span>
                        </td>
                        <td>
                            <div class="font-extrabold text-sm leading-snug mb-1" style="color: var(--color-neutral-900);">
                                {{ $item->name }}
                            </div>
                            @if(str_contains($item->code, '001366') || str_contains($item->code, '001211'))
                                <span class="inline-flex items-center gap-1 text-[10px] font-extrabold px-2.5 py-0.5 rounded"
                                      style="background: var(--color-accent-50); color: var(--color-accent-700); border: 1px solid var(--color-accent-200);">
                                    <span>⭐ MVP CORE FOCUS</span>
                                </span>
                            @endif
                        </td>
                        <td class="text-xs">
                            <div class="font-bold" style="color: var(--color-neutral-700);">Akun {{ $item->account->code }}</div>
                            <div class="text-[11px] truncate max-w-xs" style="color: var(--color-neutral-500);">{{ $item->account->name }}</div>
                            <div class="text-[10px] num-mono mt-0.5" style="color: var(--color-primary);">
                                {{ $item->account->subComponent->component->subOutput->code }}
                            </div>
                        </td>
                        <td class="text-right num-mono font-bold text-sm whitespace-nowrap" style="color: var(--color-positive-700);">
                            Rp {{ number_format($item->pagu, 0, ',', '.') }}
                        </td>
                        <td class="text-center whitespace-nowrap">
                            @if($item->documents->count() > 0)
                                <span class="sakdi-badge sakdi-badge-success">
                                    📄 {{ $item->documents->count() }} File
                                </span>
                            @else
                                <span class="sakdi-badge sakdi-badge-neutral">
                                    Belum ada
                                </span>
                            @endif
                        </td>
                        <td class="text-center whitespace-nowrap">
                            @if($item->verification_status === 'APPROVED')
                                <span class="sakdi-badge sakdi-badge-success">
                                    ✓ Siap Cair
                                </span>
                            @elseif($item->verification_status === 'REJECTED')
                                <span class="sakdi-badge sakdi-badge-error">
                                    ✕ Ditolak
                                </span>
                            @else
                                <span class="sakdi-badge sakdi-badge-warning">
                                    ⏳ Pending
                                </span>
                            @endif
                        </td>
                        <td class="text-center whitespace-nowrap">
                            @if(auth()->user()->isBendahara())
                                @if($item->verification_status === 'PENDING')
                                    <a href="{{ route('items.show', $item) }}"
                                       class="sakdi-btn sakdi-btn-sm font-extrabold shadow-sm"
                                       style="background: #F59E0B; color: #1C1917; border-color: #F59E0B;">
                                        <span>🔍 Tinjau & Verifikasi</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                @elseif($item->verification_status === 'APPROVED')
                                    <a href="{{ route('items.show', $item) }}"
                                       class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm font-bold">
                                        <span>✓ Berkas Cair</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                @else
                                    <a href="{{ route('items.show', $item) }}"
                                       class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm font-bold" style="color: #DC2626;">
                                        <span>✕ Revisi Berkas</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('items.show', $item) }}" class="sakdi-btn sakdi-btn-primary sakdi-btn-sm">
                                    <span>Workspace</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-16" style="color: var(--color-neutral-500);">
                            <div class="text-4xl mb-3">🔍</div>
                            <div class="font-extrabold text-base" style="color: var(--color-neutral-700);">Tidak ada item kegiatan yang cocok</div>
                            <div class="text-xs mt-1" style="color: var(--color-neutral-500);">Coba kata kunci lain atau reset filter cascading directory.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="px-6 py-4 border-t" style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
            {{ $items->appends(request()->query())->links() }}
        </div>
    </div>

</div>
@endsection
