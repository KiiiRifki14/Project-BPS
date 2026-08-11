@extends('layouts.app')
@section('title', 'Dashboard Utama')

@section('content')
<div class="space-y-6">

    {{-- ── MVP HIGHLIGHT BANNER FOR BMA.006 SENSUS EKONOMI ── --}}
    @php
        $bma006 = \App\Models\SubOutput::where('code', 'BMA.006')->first();
    @endphp
    @if($bma006)
    <div class="bg-gradient-to-r from-amber-500 via-amber-600 to-yellow-600 rounded-2xl p-6 text-slate-950 shadow-lg flex items-center justify-between flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-slate-950/20 text-slate-950 font-black text-xs px-3 py-1 rounded-full mb-2">
                ⭐ MODUL PRIORITAS MVP CORE FOCUS
            </div>
            <h1 class="text-xl font-black text-slate-950">BMA.006 PUBLIKASI/LAPORAN SENSUS EKONOMI</h1>
            <p class="text-xs text-slate-900 font-semibold mt-1">
                Pencairan honor petugas pendataan sensus (001366, 001211) & dokumen BAPP pertanggungjawaban.
            </p>
        </div>

        <a href="{{ route('items.index', ['sub_output_id' => $bma006->id]) }}"
           class="px-6 py-3 bg-slate-950 hover:bg-slate-900 text-white font-extrabold rounded-xl shadow-md transition-all text-sm flex items-center gap-2">
            <span>Buka Kegiatan Sensus Ekonomi</span>
            <span>→</span>
        </a>
    </div>
    @endif

    {{-- ── STAT CARDS KPI SUMMARY ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Pagu Anggaran</div>
            <div class="text-base font-extrabold text-slate-900 mt-2">Rp {{ number_format($stats['total_pagu'], 0, ',', '.') }}</div>
            <div class="text-[11px] text-slate-500 mt-1">Seluruh POK GG.2902</div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Item Kegiatan</div>
            <div class="text-2xl font-extrabold text-slate-900 mt-2">{{ number_format($stats['total_items']) }} Item</div>
            <div class="text-[11px] text-slate-500 mt-1">Struktur 8-level POK</div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm border-l-4 border-l-emerald-600">
            <div class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">✅ Siap Cair</div>
            <div class="text-2xl font-extrabold text-emerald-800 mt-2">{{ $stats['approved'] }} Item</div>
            <div class="text-[11px] text-emerald-600 mt-1">Rp {{ number_format($stats['pagu_approved'], 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm border-l-4 border-l-amber-500">
            <div class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">⏳ Pending</div>
            <div class="text-2xl font-extrabold text-amber-800 mt-2">{{ $stats['pending'] }} Item</div>
            <div class="text-[11px] text-amber-600 mt-1">Menunggu verifikasi</div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm border-l-4 border-l-red-600">
            <div class="text-[11px] font-bold text-red-700 uppercase tracking-wider">❌ Ditolak</div>
            <div class="text-2xl font-extrabold text-red-800 mt-2">{{ $stats['rejected'] }} Item</div>
            <div class="text-[11px] text-red-600 mt-1">Perlu perbaikan</div>
        </div>
    </div>

    {{-- ── RECENT DOCUMENT UPLOADS TABLE ── --}}
    <div class="table-card-v2">
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <span>📋 Item Kegiatan Terbaru (BMA.006 Sensus Ekonomi)</span>
            </h2>
            <a href="{{ route('items.index') }}" class="text-xs font-bold text-blue-800 hover:underline">
                Lihat Semua Item POK →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Kode Item</th>
                        <th>Nama Kegiatan</th>
                        <th class="text-right">Pagu Anggaran</th>
                        <th class="text-center">Dokumen</th>
                        <th class="text-center">Status</th>
                        <th class="text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentItems as $item)
                    <tr>
                        <td class="font-mono text-xs font-bold text-blue-900 bg-blue-50/60 px-3 py-2 rounded text-center">
                            {{ $item->code }}
                        </td>
                        <td>
                            <div class="font-semibold text-slate-900 text-sm">{{ Str::limit($item->name, 55) }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">
                                {{ $item->account->code }} — {{ Str::limit($item->account->name, 35) }}
                            </div>
                        </td>
                        <td class="text-right font-mono font-bold text-emerald-700 text-sm whitespace-nowrap">
                            Rp {{ number_format($item->pagu, 0, ',', '.') }}
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $item->documents->count() > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-400' }}">
                                📄 {{ $item->documents->count() }} File
                            </span>
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <span class="badge {{ $item->status_badge_class }}">
                                @if($item->verification_status === 'APPROVED') ✅ Siap Cair
                                @elseif($item->verification_status === 'REJECTED') ❌ Ditolak
                                @else ⏳ Pending
                                @endif
                            </span>
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <a href="{{ route('items.show', $item) }}" class="btn btn-primary btn-sm">
                                <span>Detail Workspace</span>
                                <span>→</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-slate-400">
                            Belum ada data.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
