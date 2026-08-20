@extends('layouts.app')
@section('title', 'Dashboard Utama')

@section('content')
<div class="space-y-8">

    {{-- ── HERO MVP BANNER (BMA.006 SENSUS EKONOMI 2026) ── --}}
    @php
        $bma006 = \App\Models\SubOutput::where('code', 'BMA.006')->first();
    @endphp
    @if($bma006)
    <div class="relative overflow-hidden rounded-2xl p-8 text-white shadow-lg"
         style="background: var(--color-primary-900);">
        {{-- Decorative radial glow --}}
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full pointer-events-none"
             style="background: radial-gradient(circle, rgba(77,158,224,0.18) 0%, transparent 70%); transform: translate(30%, -30%);"></div>
        <div class="relative z-10 flex items-center justify-between flex-wrap gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg mb-3 text-xs font-extrabold"
                     style="background: rgba(232,96,28,0.2); border: 1px solid rgba(232,96,28,0.3); color: var(--color-accent-200);">
                    <span>⭐ MODUL UTAMA MVP CORE FOCUS</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight">
                    BMA.006 PUBLIKASI/LAPORAN SENSUS EKONOMI
                </h1>
                <p class="text-xs sm:text-sm font-medium mt-2 leading-relaxed" style="color: rgba(255,255,255,0.8);">
                    Modul prioritas verifikasi &amp; pencairan honor petugas pendataan sensus (001366, 001211) serta pengarsipan berkas pertanggungjawaban BAPP &amp; Kuitansi BPS.
                </p>
            </div>

            <a href="{{ route('items.index', ['sub_output_id' => $bma006->id]) }}"
               class="sakdi-btn font-extrabold text-sm px-6 py-3.5 shadow-md"
               style="background: var(--color-accent); color: #fff; border-color: var(--color-accent); min-height: 48px;">
                <span>Buka Kegiatan Sensus Ekonomi</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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
        <div x-show="!loaded" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
            @for($i = 0; $i < 5; $i++)
            <div class="sakdi-card p-6 space-y-3">
                <div class="sakdi-skeleton sakdi-skeleton-text" style="width: 60%;"></div>
                <div class="sakdi-skeleton" style="height: 2rem; width: 80%;"></div>
                <div class="sakdi-skeleton sakdi-skeleton-text" style="width: 50%;"></div>
            </div>
            @endfor
        </div>

        {{-- Data state --}}
        <div x-show="loaded" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">

            {{-- Card 1: Total Pagu --}}
            <div class="sakdi-card-stat p-6">
                <div class="sakdi-overline mb-2">TOTAL PAGU ANGGARAN</div>
                <div class="text-lg font-black num-mono mt-1 truncate"
                     style="color: var(--color-neutral-900);">
                    Rp {{ number_format($stats['total_pagu'], 0, ',', '.') }}
                </div>
                <div class="text-xs font-semibold mt-1" style="color: var(--color-neutral-500);">Seluruh POK GG.2902</div>
            </div>

            {{-- Card 2: Total Items --}}
            <div class="sakdi-card-stat sakdi-card-stat-neutral p-6">
                <div class="sakdi-overline mb-2">TOTAL ITEM KEGIATAN</div>
                <div class="text-2xl font-black mt-1" style="color: var(--color-neutral-900);">
                    {{ number_format($stats['total_items']) }}
                    <span class="text-sm font-bold" style="color: var(--color-neutral-500);">Item</span>
                </div>
                <div class="text-xs font-semibold mt-1" style="color: var(--color-neutral-500);">Struktur 8-level POK</div>
            </div>

            {{-- Card 3: Approved --}}
            <div class="sakdi-card-stat sakdi-card-stat-positive p-6">
                <div class="sakdi-overline mb-2" style="color: var(--color-positive-700);">✅ SIAP CAIR (APPROVED)</div>
                <div class="text-2xl font-black mt-1" style="color: var(--color-positive-700);">
                    {{ $stats['approved'] }}
                    <span class="text-sm font-bold">Item</span>
                </div>
                <div class="text-xs font-extrabold num-mono mt-1" style="color: var(--color-positive);">
                    Rp {{ number_format($stats['pagu_approved'], 0, ',', '.') }}
                </div>
            </div>

            {{-- Card 4: Pending --}}
            <div class="sakdi-card-stat sakdi-card-stat-warning p-6">
                <div class="sakdi-overline mb-2" style="color: var(--color-accent-700);">⏳ PENDING VERIFIKASI</div>
                <div class="text-2xl font-black mt-1" style="color: var(--color-accent-700);">
                    {{ $stats['pending'] }}
                    <span class="text-sm font-bold">Item</span>
                </div>
                <div class="text-xs font-semibold mt-1" style="color: var(--color-accent);">Menunggu review Bendahara</div>
            </div>

            {{-- Card 5: Rejected --}}
            <div class="sakdi-card-stat sakdi-card-stat-error p-6">
                <div class="sakdi-overline mb-2" style="color: var(--color-error);">❌ DITOLAK / REVISI</div>
                <div class="text-2xl font-black mt-1" style="color: var(--color-error);">
                    {{ $stats['rejected'] }}
                    <span class="text-sm font-bold">Item</span>
                </div>
                <div class="text-xs font-semibold mt-1" style="color: var(--color-error);">Perlu perbaikan operator</div>
            </div>
        </div>
    </div>

    {{-- ── RECENT ITEMS TABLE ── --}}
    <div class="sakdi-table-wrapper">
        <div class="px-6 py-5 flex items-center justify-between"
             style="background: var(--color-neutral-50); border-bottom: 1px solid var(--color-neutral-300);">
            <div class="flex items-center gap-3">
                <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900); font-size: var(--text-sm);">
                    Item Kegiatan Terbaru
                </h2>
                <span class="text-xs" style="color: var(--color-neutral-500);">
                    Sorotan modul BMA.006 Sensus Ekonomi &amp; kegiatan POK
                </span>
            </div>
            <a href="{{ route('items.index') }}"
               class="text-xs font-extrabold flex items-center gap-1"
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
                        <th class="w-32 text-center">Kode Item</th>
                        <th>Nama Kegiatan / Item POK</th>
                        <th>Sub-Output / Akun</th>
                        <th class="text-right">
                            <div class="sakdi-tooltip-wrapper inline-block">
                                Pagu Anggaran
                                <span class="sakdi-tooltip-content">Nilai anggaran tercantum dalam DIPA/POK. Klik item untuk detail.</span>
                            </div>
                        </th>
                        <th class="text-center">Dokumen</th>
                        <th class="text-center">Status</th>
                        <th class="text-center w-36">Aksi</th>
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
