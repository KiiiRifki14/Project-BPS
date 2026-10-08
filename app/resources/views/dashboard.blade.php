@extends('layouts.app')
@section('title', 'Dashboard Utama')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- ── SMART NEW YEAR REMINDER BANNER (SUPERVISOR & ADMIN) ── --}}
    @if(isset($newYearNotice) && $newYearNotice)
    <div x-data="{ showCloneModal: false, dismissed: false }" x-show="!dismissed" class="relative">
        <div class="relative overflow-hidden rounded-2xl p-5 sm:p-6 md:p-7 text-white shadow-lg transition-all"
             style="{{ $newYearNotice['type'] === 'warning' ? 'background: linear-gradient(135deg, #78350F 0%, #B45309 100%); border: 2px solid #F59E0B;' : 'background: linear-gradient(135deg, #0F2B48 0%, #1E3A8A 100%); border: 2px solid rgba(59, 130, 246, 0.4);' }}">
            
            {{-- Radial accent light --}}
            <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full pointer-events-none opacity-20"
                 style="background: radial-gradient(circle, #ffffff 0%, transparent 70%);"></div>

            <div class="relative z-10 flex items-start justify-between flex-wrap gap-4 sm:gap-6">
                <div class="flex-1 min-w-[280px]">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg mb-2 text-xs font-black uppercase tracking-wider"
                         style="{{ $newYearNotice['type'] === 'warning' ? 'background: rgba(254, 243, 199, 0.2); color: #FEF3C7; border: 1px solid rgba(254, 243, 199, 0.3);' : 'background: rgba(219, 234, 254, 0.15); color: #93C5FD; border: 1px solid rgba(147, 197, 253, 0.25);' }}">
                        <span>{{ $newYearNotice['type'] === 'warning' ? '⚠️' : '📅' }} {{ $newYearNotice['badge'] }}</span>
                    </div>

                    <h2 class="text-lg sm:text-xl md:text-2xl font-black text-white tracking-tight leading-snug">
                        {{ $newYearNotice['title'] }}
                    </h2>
                    <p class="text-xs sm:text-sm font-medium mt-1.5 leading-relaxed text-slate-200">
                        {{ $newYearNotice['message'] }}
                    </p>
                </div>

                <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap w-full sm:w-auto">
                    @if(in_array(auth()->user()->role, ['SUPERVISOR', 'ADMIN']))
                        @if(!empty($newYearNotice['target_has_structure']))
                            <a href="{{ route('master.index', ['tab' => 'fiscal']) }}"
                               class="sakdi-btn font-black text-xs sm:text-sm px-5 py-3 shadow-md w-full sm:w-auto text-center justify-center rounded-xl"
                               style="background: #ffffff; color: #0F172A; border-color: #ffffff;">
                                <span>⭐ Kelola di Master Data</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @else
                            <button type="button"
                                    @click="showCloneModal = true"
                                    class="sakdi-btn font-black text-xs sm:text-sm px-5 py-3 shadow-md w-full sm:w-auto text-center justify-center rounded-xl cursor-pointer"
                                    style="{{ $newYearNotice['type'] === 'warning' ? 'background: #F59E0B; color: #FFFFFF; border-color: #D97706;' : 'background: var(--color-accent); color: #FFFFFF; border-color: var(--color-accent);' }}">
                                <span>📋 Salin Struktur POK ke {{ $newYearNotice['target_year'] }}</span>
                            </button>
                            <a href="{{ route('master.index', ['tab' => 'fiscal']) }}"
                               class="sakdi-btn sakdi-btn-ghost text-xs sm:text-sm px-4 py-3 text-white border border-white/20 hover:bg-white/10 rounded-xl">
                                <span>Buka Master Data</span>
                            </a>
                        @endif
                    @endif

                    {{-- Dismiss button --}}
                    <button type="button" @click="dismissed = true" class="text-white/60 hover:text-white p-2 rounded-lg hover:bg-white/10 transition-colors ml-1 cursor-pointer" title="Tutup sementara pemberitahuan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- QUICK MODAL: SALIN STRUKTUR POK --}}
        @if(in_array(auth()->user()->role, ['SUPERVISOR', 'ADMIN']))
        <div x-show="showCloneModal"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             @keydown.escape.window="showCloneModal = false">
            <div class="sakdi-card max-w-lg w-full p-6 sm:p-7 shadow-2xl relative bg-white"
                 @click.away="showCloneModal = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">
                
                <div class="flex items-start justify-between gap-4 mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-extrabold bg-blue-50 text-blue-700 mb-1">
                            <span>🚀 1-KLIK ROLLOVER POK</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-slate-900">
                            Salin Struktur POK ke TA {{ $newYearNotice['target_year'] }}
                        </h3>
                    </div>
                    <button type="button" @click="showCloneModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                        ✕
                    </button>
                </div>

                <form action="{{ route('master.fiscal-years.clone') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="source_fiscal_year_id" value="{{ $newYearNotice['source_fy_id'] }}">

                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 text-xs space-y-1.5">
                        <div class="flex justify-between font-bold text-slate-700">
                            <span>Tahun Sumber:</span>
                            <span class="num-mono font-black text-blue-700">TA {{ $newYearNotice['source_year'] }} ({{ $stats['total_items'] }} Item)</span>
                        </div>
                        <div class="flex justify-between font-bold text-slate-700">
                            <span>Tahun Tujuan:</span>
                            <span class="num-mono font-black text-emerald-700">TA {{ $newYearNotice['target_year'] }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="sakdi-label sakdi-label-required">Tahun Anggaran Tujuan</label>
                        <input type="number" name="target_year" value="{{ $newYearNotice['target_year'] }}" min="2024" max="2099" class="sakdi-input num-mono font-bold" required>
                    </div>

                    <div class="space-y-2 pt-1">
                        <label class="flex items-start gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" name="copy_pagu" value="1" checked class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-xs font-semibold text-slate-700">
                                Salin nominal pagu anggaran (sebagai baseline awal acuan)
                            </span>
                        </label>
                        <label class="flex items-start gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" name="set_active" value="1" {{ $newYearNotice['type'] === 'warning' ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-xs font-semibold text-slate-700">
                                Langsung jadikan TA {{ $newYearNotice['target_year'] }} sebagai Tahun Anggaran Aktif
                            </span>
                        </label>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-[11px] text-amber-800 leading-relaxed font-medium">
                        💡 <strong>Keamanan Data:</strong> Struktur seluruh 8-level POK (Program s.d Item) akan diduplikasi secara utuh. Dokumen fisik SPJ dan status verifikasi akan <em>bersih (fresh start)</em> untuk tahun anggaran baru.
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
                        <button type="button" @click="showCloneModal = false" class="sakdi-btn sakdi-btn-secondary text-xs px-4 py-2.5">
                            Batal
                        </button>
                        <button type="submit" class="sakdi-btn sakdi-btn-primary font-black text-xs px-5 py-2.5 shadow-md">
                            🚀 Mulai Salin Struktur POK
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- ── HERO MVP BANNER (BMA.006 SENSUS EKONOMI 2026) ── --}}
    @if(isset($bma006) && $bma006)
    <div class="relative overflow-hidden rounded-2xl p-5 sm:p-7 md:p-8 text-white shadow-lg"
         style="background: var(--color-primary-900);">
        {{-- Decorative radial glow --}}
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full pointer-events-none"
             style="background: radial-gradient(circle, rgba(77,158,224,0.18) 0%, transparent 70%); transform: translate(30%, -30%);"></div>
        <div class="relative z-10 flex items-center justify-between flex-wrap gap-4 sm:gap-6">
            <div class="flex-1 min-w-[260px]">

                <div class="inline-flex items-center gap-2 px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-lg mb-2.5 text-xs font-extrabold"
                     style="background: rgba(232,96,28,0.2); border: 1px solid rgba(232,96,28,0.3); color: var(--color-accent-200);">
                    <span>⭐ MODUL UTAMA MVP CORE FOCUS</span>
                </div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-white tracking-tight leading-tight">
                    BMA.006 PUBLIKASI/LAPORAN SENSUS EKONOMI
                </h1>
                <p class="text-xs sm:text-sm font-medium mt-2 leading-relaxed" style="color: rgba(255,255,255,0.8);">
                    Modul prioritas verifikasi &amp; pencairan honor petugas pendataan sensus (001366, 001211) serta pengarsipan berkas pertanggungjawaban BAPP &amp; Kuitansi BPS.
                </p>
            </div>

            <a href="{{ route('items.index', ['sub_output_id' => $bma006->id]) }}"
               class="sakdi-btn font-extrabold text-xs sm:text-sm px-5 sm:px-6 py-3 sm:py-3.5 shadow-md w-full sm:w-auto text-center justify-center"
               style="background: var(--color-accent); color: #fff; border-color: var(--color-accent); min-height: 44px;">
                <span>Buka Kegiatan Sensus Ekonomi</span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </div>
    @endif

    {{-- ── STATS KPI SUMMARY CARDS ── --}}
    {{-- Skeleton loader: ditampilkan via Alpine.js saat data belum ready --}}
    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 0)">

        {{-- Skeleton state --}}
        <div x-show="!loaded" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3.5 sm:gap-4 md:gap-5">
            @for($i = 0; $i < 5; $i++)
            <div class="sakdi-card p-4 sm:p-5 md:p-6 space-y-3">
                <div class="sakdi-skeleton sakdi-skeleton-text" style="width: 60%;"></div>
                <div class="sakdi-skeleton" style="height: 2rem; width: 80%;"></div>
                <div class="sakdi-skeleton sakdi-skeleton-text" style="width: 50%;"></div>
            </div>
            @endfor
        </div>

        {{-- Data state --}}
        <div x-show="loaded" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3.5 sm:gap-4 md:gap-5">

            {{-- Card 1: Total Pagu --}}
            <div class="sakdi-card-stat p-4 sm:p-5 md:p-6">
                <div class="sakdi-overline mb-1.5">TOTAL PAGU ANGGARAN</div>
                <div class="text-base sm:text-lg font-black num-mono mt-1 truncate"
                     style="color: var(--color-neutral-900);">
                    Rp {{ number_format($stats['total_pagu'], 0, ',', '.') }}
                </div>
                <div class="text-[11px] sm:text-xs font-semibold mt-1" style="color: var(--color-neutral-500);">Seluruh POK GG.2902</div>
            </div>

            {{-- Card 2: Total Items --}}
            <div class="sakdi-card-stat sakdi-card-stat-neutral p-4 sm:p-5 md:p-6">
                <div class="sakdi-overline mb-1.5">TOTAL ITEM KEGIATAN</div>
                <div class="text-xl sm:text-2xl font-black mt-1" style="color: var(--color-neutral-900);">
                    {{ number_format($stats['total_items']) }}
                    <span class="text-xs sm:text-sm font-bold" style="color: var(--color-neutral-500);">Item</span>
                </div>
                <div class="text-[11px] sm:text-xs font-semibold mt-1" style="color: var(--color-neutral-500);">Struktur 8-level POK</div>
            </div>

            {{-- Card 3: Approved --}}
            <div class="sakdi-card-stat sakdi-card-stat-positive p-4 sm:p-5 md:p-6">
                <div class="sakdi-overline mb-1.5" style="color: var(--color-positive-700);">✅ SIAP CAIR (APPROVED)</div>
                <div class="text-xl sm:text-2xl font-black mt-1" style="color: var(--color-positive-700);">
                    {{ $stats['approved'] }}
                    <span class="text-xs sm:text-sm font-bold">Item</span>
                </div>
                <div class="text-[11px] sm:text-xs font-extrabold num-mono mt-1" style="color: var(--color-positive);">
                    Rp {{ number_format($stats['pagu_approved'], 0, ',', '.') }}
                </div>
            </div>

            {{-- Card 4: Pending --}}
            <div class="sakdi-card-stat sakdi-card-stat-warning p-4 sm:p-5 md:p-6">
                <div class="sakdi-overline mb-1.5" style="color: var(--color-accent-700);">⏳ PENDING VERIFIKASI</div>
                <div class="text-xl sm:text-2xl font-black mt-1" style="color: var(--color-accent-700);">
                    {{ $stats['pending'] }}
                    <span class="text-xs sm:text-sm font-bold">Item</span>
                </div>
                <div class="text-[11px] sm:text-xs font-semibold mt-1" style="color: var(--color-accent);">Menunggu review Bendahara</div>
            </div>

            {{-- Card 5: Rejected --}}
            <div class="sakdi-card-stat sakdi-card-stat-error p-4 sm:p-5 md:p-6">
                <div class="sakdi-overline mb-1.5" style="color: var(--color-error);">❌ DITOLAK / REVISI</div>
                <div class="text-xl sm:text-2xl font-black mt-1" style="color: var(--color-error);">
                    {{ $stats['rejected'] }}
                    <span class="text-xs sm:text-sm font-bold">Item</span>
                </div>
                <div class="text-[11px] sm:text-xs font-semibold mt-1" style="color: var(--color-error);">Perlu perbaikan operator</div>
            </div>
        </div>
    </div>

    {{-- ── RECENT ITEMS TABLE ── --}}
    <div class="sakdi-table-wrapper">
        <div class="px-4 sm:px-6 py-4 sm:py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5"
             style="background: var(--color-neutral-50); border-bottom: 1px solid var(--color-neutral-300);">
            <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900); font-size: var(--text-sm);">
                    Item Kegiatan Terbaru
                </h2>
                <span class="text-xs" style="color: var(--color-neutral-500);">
                    Sorotan modul BMA.006 Sensus Ekonomi &amp; kegiatan POK
                </span>
            </div>
            <a href="{{ route('items.index') }}"
               class="text-xs font-extrabold flex items-center gap-1 self-start sm:self-auto"
               style="color: var(--color-primary);">
                <span>Lihat Semua Directory POK</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="sakdi-table">
                <thead>
                    <tr>
                        <th class="w-28 text-center whitespace-nowrap">Kode Item</th>
                        <th class="min-w-[220px]">Nama Kegiatan / Item POK</th>
                        <th class="min-w-[150px]">Sub-Output / Akun</th>
                        <th class="text-right whitespace-nowrap min-w-[120px]">
                            <div class="sakdi-tooltip-wrapper inline-block">
                                Pagu Anggaran
                                <span class="sakdi-tooltip-content">Nilai anggaran tercantum dalam DIPA/POK. Klik item untuk detail.</span>
                            </div>
                        </th>
                        <th class="text-center whitespace-nowrap min-w-[90px]">Dokumen</th>
                        <th class="text-center whitespace-nowrap min-w-[110px]">Status</th>
                        <th class="text-center w-36 whitespace-nowrap min-w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentItems as $item)
                    <tr>
                        <td class="text-center whitespace-nowrap">
                            <span class="num-mono text-xs font-bold px-3 py-1.5 rounded-lg"
                                  style="color: var(--color-primary-900); background: var(--color-primary-50); border: 1px solid var(--color-primary-100);">
                                {{ $item->code }}
                            </span>
                        </td>
                        <td>
                            <div class="font-extrabold text-sm leading-snug" style="color: var(--color-neutral-900);">
                                {{ $item->name }}
                            </div>
                            @if(str_contains($item->code, '001366') || str_contains($item->code, '001211'))
                                <span class="sakdi-badge sakdi-badge-warning mt-1">⭐ MVP CORE FOCUS</span>
                            @endif
                        </td>
                        <td class="text-xs">
                            <div class="font-bold num-mono" style="color: var(--color-neutral-900);">{{ $item->account->code }}</div>
                            <div class="text-[11px] truncate max-w-xs" style="color: var(--color-neutral-500);">{{ $item->account->name }}</div>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="sakdi-tooltip-wrapper">
                                <span class="num-mono font-bold text-sm cursor-help"
                                      style="color: var(--color-positive-700);">
                                    Rp {{ number_format($item->pagu, 0, ',', '.') }}
                                </span>
                                <span class="sakdi-tooltip-content">Pagu: Rp {{ number_format($item->pagu, 2, ',', '.') }}</span>
                            </div>
                        </td>
                        <td class="text-center whitespace-nowrap">
                            @if($item->documents->count() > 0)
                                <span class="sakdi-badge sakdi-badge-success">
                                    📄 {{ $item->documents->count() }} File
                                </span>
                            @else
                                <span class="sakdi-badge sakdi-badge-neutral">Belum ada</span>
                            @endif
                        </td>
                        <td class="text-center whitespace-nowrap">
                            @if($item->verification_status === 'APPROVED')
                                <span class="sakdi-badge sakdi-badge-success">✓ Siap Cair</span>
                            @elseif($item->verification_status === 'REJECTED')
                                <span class="sakdi-badge sakdi-badge-error">✕ Ditolak</span>
                            @else
                                <span class="sakdi-badge sakdi-badge-warning">⏳ Pending</span>
                            @endif
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <a href="{{ route('items.show', $item) }}" class="sakdi-btn sakdi-btn-primary sakdi-btn-sm">
                                <span>Workspace</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12" style="color: var(--color-neutral-500);">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-neutral-300);" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                </svg>
                                <span class="text-sm font-semibold">Belum ada data kegiatan.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
