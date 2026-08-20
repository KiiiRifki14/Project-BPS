@extends('layouts.app')
@section('title', 'Laporan & Rekapitulasi Digital')

@section('content')
<div class="space-y-8">

    {{-- Page Header --}}
    <div class="sakdi-card w-full p-8 flex items-center justify-between flex-wrap gap-6"
         style="border-left: 4px solid var(--color-primary);">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg mb-2 text-xs font-extrabold"
                 style="background: var(--color-primary-50); border: 1px solid var(--color-primary-100); color: var(--color-primary-900);">
                <span>📈 LAPORAN DIGITAL SPJ BPS</span>
            </div>
            <h1 class="text-2xl font-black tracking-tight" style="color: var(--color-neutral-900);">
                Rekapitulasi Kelengkapan Berkas POK
            </h1>
            <p class="text-xs sm:text-sm font-medium mt-1" style="color: var(--color-neutral-500);">
                Pemantauan status pertanggungjawaban digital per unit kerja &amp; sub-output DIPA BPS Kabupaten Subang.
            </p>
        </div>

        <div>
            <button onclick="window.print()" class="sakdi-btn sakdi-btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    {{-- FILTER TAHUN & BULAN REKAPITULASI --}}
    <div class="sakdi-card w-full p-6">
        <form method="GET" action="{{ route('reports.index') }}" class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full" style="background: var(--color-primary);"></span>
                <h2 class="text-xs font-black uppercase tracking-wider" style="color: var(--color-neutral-700);">FILTER PERIODE LAPORAN BULANAN &amp; TAHUNAN</h2>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <div>
                    <select name="year" onchange="this.form.submit()" class="sakdi-select text-xs font-bold py-2">
                        @foreach($fiscalYears as $fy)
                            <option value="{{ $fy->year }}" {{ $year == $fy->year ? 'selected' : '' }}>
                                📅 Tahun Anggaran {{ $fy->year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="month" onchange="this.form.submit()" class="sakdi-select text-xs font-bold py-2">
                        <option value="">🗓️ Semua Bulan (Tahunan)</option>
                        @php
                            $months = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                        @endphp
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>
                                Bulan {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($month)
                    <a href="{{ route('reports.index', ['year' => $year]) }}" class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm">
                        Reset Bulan
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 w-full">
        <div class="sakdi-card-stat sakdi-card-stat-neutral p-6">
            <div class="sakdi-overline mb-2">TOTAL KEGIATAN POK</div>
            <div class="text-2xl font-black mt-2" style="color: var(--color-neutral-900);">{{ $summary['total_items'] }} Item</div>
            <div class="text-xs font-mono font-bold mt-1" style="color: var(--color-neutral-500);">Pagu: Rp {{ number_format($summary['total_pagu'], 0, ',', '.') }}</div>
        </div>

        <div class="sakdi-card-stat sakdi-card-stat-positive p-6">
            <div class="sakdi-overline mb-2" style="color: var(--color-positive-700);">✅ APPROVED (SIAP CAIR)</div>
            <div class="text-2xl font-black mt-2" style="color: var(--color-positive-700);">{{ $summary['approved_items'] }} Item</div>
            <div class="text-xs font-mono font-bold mt-1" style="color: var(--color-positive-700);">Rp {{ number_format($summary['approved_pagu'], 0, ',', '.') }}</div>
        </div>

        <div class="sakdi-card-stat sakdi-card-stat-warning p-6">
            <div class="sakdi-overline mb-2" style="color: var(--color-accent-700);">⏳ PENDING VERIFIKASI</div>
            <div class="text-2xl font-black mt-2" style="color: var(--color-accent-700);">{{ $summary['pending_items'] }} Item</div>
            <div class="text-xs font-semibold mt-1" style="color: var(--color-accent);">Butuh pemeriksaan Bendahara</div>
        </div>

        <div class="sakdi-card-stat sakdi-card-stat-error p-6">
            <div class="sakdi-overline mb-2" style="color: var(--color-error);">❌ REJECTED (REVISI)</div>
            <div class="text-2xl font-black mt-2" style="color: var(--color-error);">{{ $summary['rejected_items'] }} Item</div>
            <div class="text-xs font-semibold mt-1" style="color: var(--color-error);">Perlu perbaikan berkas Operator</div>
        </div>
    </div>

    {{-- Detailed Sub-Output Breakdown List --}}
    <div class="sakdi-card w-full overflow-hidden p-0">

        <div class="px-6 py-5 border-b flex items-center justify-between flex-wrap gap-4"
             style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
            <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900);">
                Rekapitulasi Berkas per Sub-Output (Periode {{ $month ? $months[(int)$month] : '1 Tahun Full' }} {{ $year }})
            </h2>
            <span class="sakdi-badge sakdi-badge-neutral font-mono">
                Total {{ $subOutputs->total() }} Sub-Output
            </span>
        </div>

        <div class="divide-y" style="border-color: var(--color-neutral-100);">
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
                            <span class="num-mono text-xs font-bold px-3 py-1 rounded-lg"
                                  style="color: var(--color-primary-900); background: var(--color-primary-50); border: 1px solid var(--color-primary-100);">
                                {{ $so->code }}
                            </span>
                            <span class="text-base font-extrabold" style="color: var(--color-neutral-900);">{{ $so->name }}</span>
                        </div>
                        <div class="text-xs mt-2" style="color: var(--color-neutral-500);">
                            Total {{ $allItems->count() }} item kegiatan • Total Pagu: <strong class="num-mono" style="color: var(--color-positive-700);">Rp {{ number_format($totalPagu, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="sakdi-badge sakdi-badge-success">
                            <span>✓ {{ $approved }} Approved</span>
                        </span>

                        <span class="sakdi-badge sakdi-badge-warning">
                            <span>⏳ {{ $pending }} Pending</span>
                        </span>

                        @if($rejected > 0)
                            <span class="sakdi-badge sakdi-badge-error">
                                <span>✕ {{ $rejected }} Rejected</span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="px-6 py-4 border-t" style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
            {{ $subOutputs->appends(request()->query())->links() }}
        </div>
    </div>

</div>
@endsection
