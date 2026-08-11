@extends('layouts.app')
@section('title', 'Verifikasi Pencairan')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <span>✅ Inbox Verifikasi Pencairan (Bendahara)</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Tinjau kelengkapan dokumen SPJ, BAPP, dan Kuitansi sebelum menyetujui pencairan dana kegiatan BPS.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('verification.index', ['status' => 'PENDING']) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'PENDING' ? 'bg-amber-500 text-slate-950 shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                ⏳ Antrean Pending ({{ $pendingCount }})
            </a>
            <a href="{{ route('verification.index', ['status' => 'APPROVED']) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'APPROVED' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                ✅ Siap Cair ({{ $approvedCount }})
            </a>
            <a href="{{ route('verification.index', ['status' => 'REJECTED']) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'REJECTED' ? 'bg-red-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                ❌ Ditolak ({{ $rejectedCount }})
            </a>
            <a href="{{ route('verification.index', ['status' => 'ALL']) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'ALL' ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Status
            </a>
        </div>
    </div>

    {{-- Items Verification Table --}}
    <div class="table-card-v2">
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800">
                <span>Daftar Item Kegiatan — Filter: {{ $status }}</span>
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Kode Item</th>
                        <th>Nama Kegiatan</th>
                        <th>Sub-Output / Akun</th>
                        <th class="text-right">Pagu</th>
                        <th class="text-center">Dokumen</th>
                        <th class="text-center">Status</th>
                        <th class="text-center w-48">Verifikasi / Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td class="font-mono text-xs font-bold text-blue-900 bg-blue-50/60 px-3 py-2 rounded text-center">
                            {{ $item->code }}
                        </td>
                        <td class="font-semibold text-slate-900 text-sm">
                            {{ $item->name }}
                        </td>
                        <td class="text-xs text-slate-600">
                            <div class="font-semibold">Akun {{ $item->account->code }}</div>
                            <div class="text-[11px] text-slate-500 font-mono">
                                {{ $item->account->subComponent->component->subOutput->code }}
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
                                🔍 Tinjau & Verifikasi →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-slate-400">
                            <div class="text-3xl mb-2">🎉</div>
                            <div class="font-medium text-slate-600">Tidak ada antrean kegiatan untuk status ini.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-3 bg-slate-50 border-t border-slate-200">
            {{ $items->links() }}
        </div>
    </div>

</div>
@endsection
