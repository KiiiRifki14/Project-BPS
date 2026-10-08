@extends('layouts.app')
@section('title', 'Laporan & Rekapitulasi Digital')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- Page Header --}}
    <div class="sakdi-card w-full p-5 sm:p-7 md:p-8 flex items-center justify-between flex-wrap gap-4 sm:gap-6"
         style="border-left: 4px solid var(--color-primary);">
        <div class="min-w-0 flex-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-lg mb-2 text-xs font-extrabold"
                 style="background: var(--color-primary-50); border: 1px solid var(--color-primary-100); color: var(--color-primary-900);">
                <span>📈 LAPORAN DIGITAL SPJ BPS</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black tracking-tight" style="color: var(--color-neutral-900);">
                Rekapitulasi Kelengkapan Berkas POK
            </h1>
            <p class="text-xs sm:text-sm font-medium mt-1" style="color: var(--color-neutral-500);">
                Pemantauan status pertanggungjawaban digital per unit kerja &amp; sub-output DIPA BPS Kabupaten Subang.
            </p>
        </div>

        <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap">
            <a href="{{ route('reports.export', request()->query()) }}" class="sakdi-btn sakdi-btn-primary font-extrabold shadow-sm text-xs sm:text-sm">
                <span>📊 Export ke Excel (.csv)</span>
            </a>
            <button onclick="window.print()" class="sakdi-btn sakdi-btn-secondary text-xs sm:text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    {{-- FILTER TAHUN, BULAN & MINGGUAN REKAPITULASI --}}
    <div class="sakdi-card w-full p-4 sm:p-6">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-3.5 sm:gap-4">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background: var(--color-primary);"></span>
                <h2 class="text-xs font-black uppercase tracking-wider" style="color: var(--color-neutral-700);">FILTER PERIODE LAPORAN</h2>
            </div>

            <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap">
                <div>
                    <select name="year" onchange="this.form.submit()" class="sakdi-select text-xs font-bold py-2">
                        @foreach($fiscalYears as $fy)
                            <option value="{{ $fy->year }}" {{ $year == $fy->year ? 'selected' : '' }}>
                                📅 TA {{ $fy->year }}
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
                <div>
                    <select name="week" onchange="this.form.submit()" class="sakdi-select text-xs font-bold py-2">
                        <option value="">📆 Full 1 Bulan</option>
                        <option value="1" {{ request('week') == '1' ? 'selected' : '' }}>Minggu ke-1 (Tgl 1-7)</option>
                        <option value="2" {{ request('week') == '2' ? 'selected' : '' }}>Minggu ke-2 (Tgl 8-14)</option>
                        <option value="3" {{ request('week') == '3' ? 'selected' : '' }}>Minggu ke-3 (Tgl 15-21)</option>
                        <option value="4" {{ request('week') == '4' ? 'selected' : '' }}>Minggu ke-4 (Tgl 22-28)</option>
                        <option value="5" {{ request('week') == '5' ? 'selected' : '' }}>Minggu ke-5 (Tgl 29-31)</option>
                    </select>
                </div>
                @endif

                @if($month || request('week'))
                    <a href="{{ route('reports.index', ['year' => $year]) }}" class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm text-xs">
                        Reset Filter
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4 md:gap-5 w-full">
        <div class="sakdi-card-stat sakdi-card-stat-neutral p-4 sm:p-5 md:p-6">
            <div class="sakdi-overline mb-1.5">TOTAL KEGIATAN POK</div>
            <div class="text-xl sm:text-2xl font-black mt-1" style="color: var(--color-neutral-900);">{{ $summary['total_items'] }} Item</div>
            <div class="text-[11px] sm:text-xs font-mono font-bold mt-1" style="color: var(--color-neutral-500);">Pagu: Rp {{ number_format($summary['total_pagu'], 0, ',', '.') }}</div>
        </div>

        <div class="sakdi-card-stat sakdi-card-stat-positive p-4 sm:p-5 md:p-6">
            <div class="sakdi-overline mb-1.5" style="color: var(--color-positive-700);">✅ APPROVED (SIAP CAIR)</div>
            <div class="text-xl sm:text-2xl font-black mt-1" style="color: var(--color-positive-700);">{{ $summary['approved_items'] }} Item</div>
            <div class="text-[11px] sm:text-xs font-mono font-bold mt-1" style="color: var(--color-positive-700);">Rp {{ number_format($summary['approved_pagu'], 0, ',', '.') }}</div>
        </div>

        <div class="sakdi-card-stat sakdi-card-stat-warning p-4 sm:p-5 md:p-6">
            <div class="sakdi-overline mb-1.5" style="color: var(--color-accent-700);">⏳ PENDING VERIFIKASI</div>
            <div class="text-xl sm:text-2xl font-black mt-1" style="color: var(--color-accent-700);">{{ $summary['pending_items'] }} Item</div>
            <div class="text-[11px] sm:text-xs font-semibold mt-1" style="color: var(--color-accent);">Butuh pemeriksaan Bendahara</div>
        </div>

        <div class="sakdi-card-stat sakdi-card-stat-error p-4 sm:p-5 md:p-6">
            <div class="sakdi-overline mb-1.5" style="color: var(--color-error);">❌ REJECTED (REVISI)</div>
            <div class="text-xl sm:text-2xl font-black mt-1" style="color: var(--color-error);">{{ $summary['rejected_items'] }} Item</div>
            <div class="text-[11px] sm:text-xs font-semibold mt-1" style="color: var(--color-error);">Perlu perbaikan berkas Operator</div>
        </div>
    </div>

    {{-- Detailed Sub-Output Breakdown Table --}}
    <div class="sakdi-table-wrapper w-full">

        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b flex flex-col sm:flex-row sm:items-center justify-between gap-3"
             style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
            <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900);">
                    Rekapitulasi Berkas per Sub-Output (Periode {{ $month ? $months[(int)$month] : '1 Tahun Full' }} {{ $year }})
                </h2>
            </div>
            <span class="sakdi-badge sakdi-badge-primary font-mono text-xs self-start sm:self-auto">
                Total {{ $subOutputs->total() }} Sub-Output
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="sakdi-table">
                <thead>
                    <tr>
                        <th class="w-28 text-center whitespace-nowrap">Kode</th>
                        <th class="min-w-[220px]">Nama Sub-Output</th>
                        <th class="text-center whitespace-nowrap w-24">Item</th>
                        <th class="text-right whitespace-nowrap min-w-[130px]">Pagu DIPA</th>
                        <th class="text-right whitespace-nowrap min-w-[140px]">Disetujui (Cair)</th>
                        <th class="text-center whitespace-nowrap min-w-[130px]">Status SPJ</th>
                        <th class="text-center whitespace-nowrap w-28">Serapan (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subOutputs as $so)
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
                        $totalItemsCount = $allItems->count();
                        $totalPagu = $allItems->sum('pagu');

                        if ($range) {
                            $startTime = strtotime($range[0]);
                            $endTime   = strtotime($range[1]);

                            $approvedItems = $allItems->filter(function ($it) use ($startTime, $endTime) {
                                if ($it->verification_status !== 'APPROVED') return false;
                                $t = strtotime($it->updated_at);
                                return $t >= $startTime && $t <= $endTime;
                            });

                            $rejectedItems = $allItems->filter(function ($it) use ($startTime, $endTime) {
                                if ($it->verification_status !== 'REJECTED') return false;
                                $t = strtotime($it->updated_at);
                                return $t >= $startTime && $t <= $endTime;
                            });
                        } else {
                            $approvedItems = $allItems->where('verification_status', 'APPROVED');
                            $rejectedItems = $allItems->where('verification_status', 'REJECTED');
                        }

                        $approved = $approvedItems->count();
                        $approvedPagu = $approvedItems->sum('pagu');
                        $rejected = $rejectedItems->count();
                        $pending = max(0, $totalItemsCount - $approved - $rejected);
                        $percent = $totalPagu > 0 ? round(($approvedPagu / $totalPagu) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td class="text-center whitespace-nowrap">
                            <span class="num-mono text-xs font-bold px-3 py-1.5 rounded-lg"
                                  style="color: var(--color-primary-900); background: var(--color-primary-50); border: 1px solid var(--color-primary-100);">
                                {{ $so->code }}
                            </span>
                        </td>
                        <td>
                            <div class="font-extrabold text-sm leading-snug" style="color: var(--color-neutral-900);">
                                {{ $so->name }}
                            </div>
                        </td>
                        <td class="text-center whitespace-nowrap num-mono font-bold text-xs">
                            {{ $totalItemsCount }}
                        </td>
                        <td class="text-right whitespace-nowrap num-mono font-bold text-xs" style="color: var(--color-neutral-900);">
                            Rp {{ number_format($totalPagu, 0, ',', '.') }}
                        </td>
                        <td class="text-right whitespace-nowrap num-mono font-bold text-xs" style="color: var(--color-positive-700);">
                            Rp {{ number_format($approvedPagu, 0, ',', '.') }}
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5 flex-nowrap">
                                <span class="sakdi-badge sakdi-badge-success text-[11px]" title="Approved">
                                    ✓ {{ $approved }}
                                </span>
                                <span class="sakdi-badge sakdi-badge-warning text-[11px]" title="Pending">
                                    ⏳ {{ $pending }}
                                </span>
                                @if($rejected > 0)
                                <span class="sakdi-badge sakdi-badge-error text-[11px]" title="Rejected">
                                    ✕ {{ $rejected }}
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-1">
                                <span class="font-mono font-black text-xs {{ $percent >= 80 ? 'text-emerald-700' : ($percent >= 50 ? 'text-blue-700' : 'text-slate-700') }}">
                                    {{ $percent }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-slate-500 text-sm">
                            Tidak ada data sub-output untuk periode yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t" style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
            {{ $subOutputs->appends(request()->query())->links() }}
        </div>
    </div>

</div>
@endsection
