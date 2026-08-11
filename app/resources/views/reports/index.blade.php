@extends('layouts.app')
@section('title', 'Laporan & Rekapitulasi Digital')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <span>📈 Laporan & Rekapitulasi Digital SPJ BPS</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Pemantauan status kelengkapan berkas pertanggungjawaban per unit dan kegiatan POK.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="btn btn-secondary text-xs">
                🖨️ Cetak Laporan
            </button>
        </div>
    </div>

    {{-- KPI Cards Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500">Total Item Kegiatan</div>
            <div class="text-2xl font-extrabold text-slate-900 mt-2">{{ $summary['total_items'] }} Item</div>
            <div class="text-xs text-slate-500 mt-1">Pagu: Rp {{ number_format($summary['total_pagu'], 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm border-l-4 border-l-emerald-600">
            <div class="text-xs font-semibold text-emerald-700">Disetujui / Siap Cair</div>
            <div class="text-2xl font-extrabold text-emerald-800 mt-2">{{ $summary['approved_items'] }} Item</div>
            <div class="text-xs text-emerald-600 mt-1">Pagu Cair: Rp {{ number_format($summary['approved_pagu'], 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm border-l-4 border-l-amber-500">
            <div class="text-xs font-semibold text-amber-700">Menunggu Verifikasi</div>
            <div class="text-2xl font-extrabold text-amber-800 mt-2">{{ $summary['pending_items'] }} Item</div>
            <div class="text-xs text-amber-600 mt-1">Butuh pemeriksaan Bendahara</div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm border-l-4 border-l-red-600">
            <div class="text-xs font-semibold text-red-700">Ditolak / Revisi</div>
            <div class="text-2xl font-extrabold text-red-800 mt-2">{{ $summary['rejected_items'] }} Item</div>
            <div class="text-xs text-red-600 mt-1">Perlu perbaikan berkas Operator</div>
        </div>
    </div>

    {{-- Detailed Sub-Output Breakdown --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-200">
            <h2 class="text-sm font-bold text-slate-800">Rekapitulasi Kelengkapan Berkas per Sub-Output</h2>
        </div>

        <div class="divide-y divide-slate-200">
            @foreach($subOutputs as $so)
            @php
                $allItems = collect();
                foreach($so->components as $c) {
                    foreach($c->subComponents as $sc) {
                        foreach($sc->accounts as $a) {
                            foreach($a->items as $i) {
                                $allItems->push($i);
                            }
                        }
                    }
                }
                $approved = $allItems->where('verification_status', 'APPROVED')->count();
                $pending = $allItems->where('verification_status', 'PENDING')->count();
                $rejected = $allItems->where('verification_status', 'REJECTED')->count();
                $totalPagu = $allItems->sum('pagu');
            @endphp
            <div class="p-5 hover:bg-slate-50/80 transition-colors">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-800">
                                {{ $so->code }}
                            </span>
                            <span class="text-sm font-bold text-slate-900">{{ $so->name }}</span>
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Total {{ $allItems->count() }} item kegiatan • Total Pagu: <strong>Rp {{ number_format($totalPagu, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                            ✅ {{ $approved }} Approved
                        </span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                            ⏳ {{ $pending }} Pending
                        </span>
                        @if($rejected > 0)
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                            ❌ {{ $rejected }} Rejected
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
