@extends('layouts.app')
@section('title', 'Dashboard Utama')

@section('content')
<div class="space-y-8">

    {{-- ── HERO MVP BANNER (BMA.006 SENSUS EKONOMI 2026) ── --}}
    @php
        $bma006 = \App\Models\SubOutput::where('code', 'BMA.006')->first();
    @endphp
    @if($bma006)
    <div class="relative overflow-hidden rounded-2xl bg-[#001F54] p-8 text-white shadow-lg">
        <div class="relative z-10 flex items-center justify-between flex-wrap gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 bg-amber-500/20 border border-amber-400/30 text-amber-300 font-extrabold text-xs px-3.5 py-1.5 rounded-lg mb-3">
                    <span>⭐ MODUL UTAMA MVP CORE FOCUS</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight">
                    BMA.006 PUBLIKASI/LAPORAN SENSUS EKONOMI
                </h1>
                <p class="text-xs sm:text-sm text-slate-200 font-medium mt-2 leading-relaxed opacity-90">
                    Modul prioritas verifikasi & pencairan honor petugas pendataan sensus (001366, 001211) serta pengarsipan berkas pertanggungjawaban BAPP & Kuitansi BPS.
                </p>
            </div>

            <a href="{{ route('items.index', ['sub_output_id' => $bma006->id]) }}"
               class="btn-bps bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-sm px-6 py-3.5 shadow-md">
                <span>Buka Kegiatan Sensus Ekonomi</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>
    @endif

    {{-- ── STATS KPI SUMMARY CARDS ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
        {{-- Card 1: Total Pagu --}}
        <div class="card-corporate p-6">
            <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">TOTAL PAGU ANGGARAN</div>
            <div class="text-lg font-black text-slate-900 font-mono mt-2 truncate">Rp {{ number_format($stats['total_pagu'], 0, ',', '.') }}</div>
            <div class="text-[11px] font-semibold text-slate-500 mt-1">Seluruh POK GG.2902</div>
        </div>

        {{-- Card 2: Total Items --}}
        <div class="card-corporate p-6">
            <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">TOTAL ITEM KEGIATAN</div>
            <div class="text-2xl font-black text-slate-900 mt-2">{{ number_format($stats['total_items']) }} <span class="text-sm font-bold text-slate-500">Item</span></div>
            <div class="text-[11px] font-semibold text-slate-500 mt-1">Struktur 8-level POK</div>
        </div>

        {{-- Card 3: Approved --}}
        <div class="card-corporate p-6 border-l-4 border-l-emerald-600">
            <div class="text-[11px] font-extrabold text-emerald-800 uppercase tracking-wider">✅ SIAP CAIR (APPROVED)</div>
            <div class="text-2xl font-black text-emerald-900 mt-2">{{ $stats['approved'] }} <span class="text-sm font-bold text-emerald-700">Item</span></div>
            <div class="text-[11px] font-extrabold text-emerald-700 font-mono mt-1">Rp {{ number_format($stats['pagu_approved'], 0, ',', '.') }}</div>
        </div>

        {{-- Card 4: Pending --}}
        <div class="card-corporate p-6 border-l-4 border-l-amber-500">
            <div class="text-[11px] font-extrabold text-amber-800 uppercase tracking-wider">⏳ PENDING VERIFIKASI</div>
            <div class="text-2xl font-black text-amber-900 mt-2">{{ $stats['pending'] }} <span class="text-sm font-bold text-amber-700">Item</span></div>
            <div class="text-[11px] font-semibold text-amber-700 mt-1">Menunggu review Bendahara</div>
        </div>

        {{-- Card 5: Rejected --}}
        <div class="card-corporate p-6 border-l-4 border-l-red-600">
            <div class="text-[11px] font-extrabold text-red-800 uppercase tracking-wider">❌ DITOLAK / REVISI</div>
            <div class="text-2xl font-black text-red-900 mt-2">{{ $stats['rejected'] }} <span class="text-sm font-bold text-red-700">Item</span></div>
            <div class="text-[11px] font-semibold text-red-700 mt-1">Perlu perbaikan operator</div>
        </div>
    </div>

    {{-- ── RECENT ITEMS TABLE ── --}}
    <div class="table-container-v4">
        <div class="px-6 py-5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="text-sm font-extrabold text-slate-900">Item Kegiatan Terbaru</h2>
                <span class="text-xs font-semibold text-slate-500">Sorotan modul BMA.006 Sensus Ekonomi & kegiatan POK</span>
            </div>

            <a href="{{ route('items.index') }}" class="text-xs font-extrabold text-blue-900 hover:underline flex items-center gap-1">
                <span>Lihat Semua Directory POK</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="table-v4">
                <thead>
                    <tr>
                        <th class="w-32 text-center">Kode Item</th>
                        <th>Nama Kegiatan / Item POK</th>
                        <th>Sub-Output / Akun</th>
                        <th class="text-right">Pagu Anggaran</th>
                        <th class="text-center">Dokumen</th>
                        <th class="text-center">Status</th>
                        <th class="text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentItems as $item)
                    <tr>
                        <td class="text-center whitespace-nowrap">
                            <span class="font-mono text-xs font-bold text-blue-900 bg-blue-50 border border-blue-200 px-3 py-1.5 rounded-lg">
                                {{ $item->code }}
                            </span>
                        </td>
                        <td>
                            <div class="font-extrabold text-slate-900 text-sm leading-snug">{{ $item->name }}</div>
                            @if(str_contains($item->code, '001366') || str_contains($item->code, '001211'))
                                <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-900 border border-amber-300 text-[10px] font-black px-2.5 py-0.5 rounded mt-1">
                                    <span>⭐ MVP CORE FOCUS</span>
                                </span>
                            @endif
                        </td>
                        <td class="text-xs">
                            <div class="font-bold text-slate-800">{{ $item->account->code }}</div>
                            <div class="text-[11px] text-slate-500 truncate max-w-xs">{{ $item->account->name }}</div>
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
                        <td colspan="7" class="text-center py-12 text-slate-400">
                            Belum ada data kegiatan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
