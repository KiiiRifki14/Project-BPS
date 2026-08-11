@extends('layouts.app')
@section('title', 'Verifikasi Pencairan')

@section('content')
<div class="space-y-8">

    {{-- Header Banner --}}
    <div class="card-corporate p-8 flex items-center justify-between flex-wrap gap-6">
        <div>
            <div class="inline-flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-900 font-extrabold text-xs px-3.5 py-1.5 rounded-lg mb-2">
                <span>🏦 BENDAHARA INBOX VERIFIKASI</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Verifikasi Pencairan Dana Kegiatan</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                Tinjau kelengkapan dokumen SPJ, BAPP, dan Kuitansi sebelum menyetujui pencairan anggaran BPS Subang.
            </p>
        </div>

        {{-- Status Filter Buttons --}}
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('verification.index', ['status' => 'PENDING']) }}"
               class="btn-bps btn-bps-sm {{ $status === 'PENDING' ? 'bg-amber-500 text-slate-950 font-black' : 'btn-bps-secondary' }}">
                ⏳ Antrean Pending ({{ $pendingCount }})
            </a>
            <a href="{{ route('verification.index', ['status' => 'APPROVED']) }}"
               class="btn-bps btn-bps-sm {{ $status === 'APPROVED' ? 'btn-bps-success' : 'btn-bps-secondary' }}">
                ✅ Siap Cair ({{ $approvedCount }})
            </a>
            <a href="{{ route('verification.index', ['status' => 'REJECTED']) }}"
               class="btn-bps btn-bps-sm {{ $status === 'REJECTED' ? 'btn-bps-danger' : 'btn-bps-secondary' }}">
                ❌ Ditolak ({{ $rejectedCount }})
            </a>
            <a href="{{ route('verification.index', ['status' => 'ALL']) }}"
               class="btn-bps btn-bps-sm {{ $status === 'ALL' ? 'btn-bps-primary' : 'btn-bps-secondary' }}">
                Semua Status
            </a>
        </div>
    </div>

    {{-- Items Verification Table --}}
    <div class="table-container-v4">
        <div class="px-6 py-5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-sm font-extrabold text-slate-900">
                Daftar Antrean Verifikasi — Filter: <span class="text-blue-900 font-mono">{{ $status }}</span>
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="table-v4">
                <thead>
                    <tr>
                        <th class="w-32 text-center">Kode Item</th>
                        <th>Nama Kegiatan / Item POK</th>
                        <th>Sub-Output / Akun</th>
                        <th class="text-right">Pagu</th>
                        <th class="text-center">Dokumen</th>
                        <th class="text-center">Status</th>
                        <th class="text-center w-48">Aksi</th>
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
                        <td class="font-extrabold text-slate-900 text-sm">
                            {{ $item->name }}
                        </td>
                        <td class="text-xs">
                            <div class="font-bold text-slate-800">Akun {{ $item->account->code }}</div>
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
                            <a href="{{ route('items.show', [$item, 'from' => 'verification']) }}" class="btn-bps btn-bps-primary btn-bps-sm">
                                <span>🔍 Tinjau & Verifikasi</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-16 text-slate-400">
                            <div class="text-4xl mb-3">🎉</div>
                            <div class="font-extrabold text-slate-700 text-base">Tidak ada antrean verifikasi</div>
                            <div class="text-xs text-slate-400 mt-1">Semua dokumen SPJ telah ditinjau atau belum diunggah operator.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $items->appends(request()->query())->links() }}
        </div>
    </div>

</div>
@endsection
