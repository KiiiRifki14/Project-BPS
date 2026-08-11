@extends('layouts.app')
@section('title', 'Laporan & Rekapitulasi Digital')

@section('content')
<div class="space-y-8">

    {{-- Page Header --}}
    <div class="card-corporate p-8 flex items-center justify-between flex-wrap gap-6">
        <div>
            <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-900 font-extrabold text-xs px-3.5 py-1.5 rounded-lg mb-2">
                <span>📈 LAPORAN DIGITAL SPJ BPS</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Rekapitulasi Kelengkapan Berkas POK</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                Pemantauan status pertanggungjawaban digital per unit kerja & sub-output DIPA BPS Kabupaten Subang.
            </p>
        </div>

        <div>
            <button onclick="window.print()" class="btn-bps btn-bps-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="card-corporate p-6">
            <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">TOTAL KEGIATAN POK</div>
            <div class="text-2xl font-black text-slate-900 mt-2">{{ $summary['total_items'] }} Item</div>
            <div class="text-xs font-mono font-bold text-slate-500 mt-1">Pagu: Rp {{ number_format($summary['total_pagu'], 0, ',', '.') }}</div>
        </div>

        <div class="card-corporate p-6 border-l-4 border-l-emerald-600">
            <div class="text-[11px] font-extrabold text-emerald-800 uppercase tracking-wider">✅ APPROVED (SIAP CAIR)</div>
            <div class="text-2xl font-black text-emerald-900 mt-2">{{ $summary['approved_items'] }} Item</div>
            <div class="text-xs font-mono font-bold text-emerald-700 mt-1">Rp {{ number_format($summary['approved_pagu'], 0, ',', '.') }}</div>
        </div>

        <div class="card-corporate p-6 border-l-4 border-l-amber-500">
            <div class="text-[11px] font-extrabold text-amber-800 uppercase tracking-wider">⏳ PENDING VERIFIKASI</div>
            <div class="text-2xl font-black text-amber-900 mt-2">{{ $summary['pending_items'] }} Item</div>
            <div class="text-xs font-semibold text-amber-700 mt-1">Butuh pemeriksaan Bendahara</div>
        </div>

        <div class="card-corporate p-6 border-l-4 border-l-red-600">
            <div class="text-[11px] font-extrabold text-red-800 uppercase tracking-wider">❌ REJECTED (REVISI)</div>
            <div class="text-2xl font-black text-red-900 mt-2">{{ $summary['rejected_items'] }} Item</div>
            <div class="text-xs font-semibold text-red-700 mt-1">Perlu perbaikan berkas Operator</div>
        </div>
    </div>

    {{-- Detailed Sub-Output Breakdown List --}}
    <div class="card-corporate overflow-hidden">
        <div class="px-6 py-5 bg-slate-50 border-b border-slate-200">
            <h2 class="text-sm font-extrabold text-slate-900">Rekapitulasi Berkas per Sub-Output (Total {{ $subOutputs->total() }} Sub-Output)</h2>
        </div>

        <div class="divide-y divide-slate-100">
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
            <div class="p-6 hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-xs font-bold text-blue-900 bg-blue-50 border border-blue-200 px-3 py-1 rounded-lg">
                                {{ $so->code }}
                            </span>
                            <span class="text-base font-extrabold text-slate-900">{{ $so->name }}</span>
                        </div>
                        <div class="text-xs text-slate-500 mt-2">
                            Total {{ $allItems->count() }} item kegiatan • Total Pagu: <strong class="font-mono text-emerald-800">Rp {{ number_format($totalPagu, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="badge-corp badge-corp-approved">
                            <span>{{ $approved }} Approved</span>
                        </span>

                        <span class="badge-corp badge-corp-pending">
                            <span>{{ $pending }} Pending</span>
                        </span>

                        @if($rejected > 0)
                        <span class="badge-corp badge-corp-rejected">
                            <span>{{ $rejected }} Rejected</span>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination Links for SubOutputs --}}
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $subOutputs->links() }}
        </div>
    </div>

</div>
@endsection
