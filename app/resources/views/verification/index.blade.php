@extends('layouts.app')
@section('title', 'Verifikasi Pencairan')

@section('content')
<div class="space-y-8">

    {{-- Header Banner --}}
    <div class="sakdi-card w-full p-8 flex items-center justify-between flex-wrap gap-6"
         style="border-left: 4px solid var(--color-primary);">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg mb-2 text-xs font-extrabold"
                 style="background: var(--color-accent-50); border: 1px solid var(--color-accent-200); color: var(--color-accent-700);">
                <span>🏦 BENDAHARA INBOX VERIFIKASI</span>
            </div>
            <h1 class="text-2xl font-black tracking-tight" style="color: var(--color-neutral-900);">
                Verifikasi Pencairan Dana Kegiatan
            </h1>
            <p class="text-xs sm:text-sm font-medium mt-1" style="color: var(--color-neutral-500);">
                Tinjau kelengkapan dokumen SPJ, BAPP, dan Kuitansi sebelum menyetujui pencairan anggaran BPS Subang.
            </p>
        </div>

        {{-- Status Filter Buttons / Tabs --}}
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('verification.index', array_merge(request()->query(), ['status' => 'PENDING'])) }}"
               class="sakdi-btn sakdi-btn-sm {{ $status === 'PENDING' ? 'sakdi-btn-accent' : 'sakdi-btn-secondary' }}">
                ⏳ Antrean Pending ({{ $pendingCount }})
            </a>
            <a href="{{ route('verification.index', array_merge(request()->query(), ['status' => 'APPROVED'])) }}"
               class="sakdi-btn sakdi-btn-sm {{ $status === 'APPROVED' ? 'sakdi-btn-success' : 'sakdi-btn-secondary' }}">
                ✅ Siap Cair ({{ $approvedCount }})
            </a>
            <a href="{{ route('verification.index', array_merge(request()->query(), ['status' => 'REJECTED'])) }}"
               class="sakdi-btn sakdi-btn-sm {{ $status === 'REJECTED' ? 'sakdi-btn-danger' : 'sakdi-btn-secondary' }}">
                ❌ Ditolak ({{ $rejectedCount }})
            </a>
            <a href="{{ route('verification.index', array_merge(request()->query(), ['status' => 'ALL'])) }}"
               class="sakdi-btn sakdi-btn-sm {{ $status === 'ALL' ? 'sakdi-btn-primary' : 'sakdi-btn-secondary' }}">
                Semua Status
            </a>
        </div>
    </div>

    {{-- Items Verification Table --}}
    <div class="sakdi-table-wrapper w-full">

        <div class="px-6 py-5 flex items-center justify-between flex-wrap gap-4"
             style="background: var(--color-neutral-50); border-bottom: 1px solid var(--color-neutral-300);">
            <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900);">
                Daftar Antrean Verifikasi — Filter: <span class="num-mono" style="color: var(--color-primary);">{{ $status }}</span>
            </h2>
        </div>

        {{-- Input Search Box Kode Item / Nama Kegiatan --}}
        <div class="p-6 border-b" style="border-color: var(--color-neutral-300); background: var(--color-bg-surface);">
            <form method="GET" action="{{ route('verification.index') }}" class="flex gap-3">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="relative flex-1">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="🔍 Ketik Kode Item (misal: 001366) atau Kata Kunci Kegiatan..."
                        class="sakdi-input pl-10"
                    >
                </div>
                <button type="submit" class="sakdi-btn sakdi-btn-primary">
                    Cari Item
                </button>
                @if(request('search'))
                    <a href="{{ route('verification.index', ['status' => $status]) }}" class="sakdi-btn sakdi-btn-secondary">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="sakdi-table">
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
                            <span class="num-mono text-xs font-bold px-3 py-1.5 rounded-lg"
                                  style="color: var(--color-primary-900); background: var(--color-primary-50); border: 1px solid var(--color-primary-100);">
                                {{ $item->code }}
                            </span>
                        </td>
                        <td class="font-extrabold text-sm" style="color: var(--color-neutral-900);">
                            {{ $item->name }}
                        </td>
                        <td class="text-xs">
                            <div class="font-bold" style="color: var(--color-neutral-700);">Akun {{ $item->account->code }}</div>
                            <div class="text-[10px] num-mono mt-0.5" style="color: var(--color-primary);">
                                {{ $item->account->subComponent->component->subOutput->code }}
                            </div>
                        </td>
                        <td class="text-right num-mono font-bold text-sm whitespace-nowrap" style="color: var(--color-positive-700);">
                            Rp {{ number_format($item->pagu, 0, ',', '.') }}
                        </td>
                        <td class="text-center whitespace-nowrap">
                            @if($item->documents->count() > 0)
                                <span class="sakdi-badge sakdi-badge-success">
                                    📄 {{ $item->documents->count() }} File
                                </span>
                            @else
                                <span class="sakdi-badge sakdi-badge-neutral">
                                    Belum ada
                                </span>
                            @endif
                        </td>
                        <td class="text-center whitespace-nowrap">
                            @if($item->verification_status === 'APPROVED')
                                <span class="sakdi-badge sakdi-badge-success">
                                    ✓ Siap Cair
                                </span>
                            @elseif($item->verification_status === 'REJECTED')
                                <span class="sakdi-badge sakdi-badge-error">
                                    ✕ Ditolak
                                </span>
                            @else
                                <span class="sakdi-badge sakdi-badge-warning">
                                    ⏳ Pending
                                </span>
                            @endif
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <a href="{{ route('items.show', [$item, 'from' => 'verification']) }}" class="sakdi-btn sakdi-btn-primary sakdi-btn-sm">
                                <span>🔍 Tinjau &amp; Verifikasi</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-16" style="color: var(--color-neutral-500);">
                            <div class="text-4xl mb-3">🎉</div>
                            <div class="font-extrabold text-base" style="color: var(--color-neutral-700);">Tidak ada antrean verifikasi</div>
                            <div class="text-xs mt-1" style="color: var(--color-neutral-500);">Semua dokumen SPJ telah ditinjau atau belum diunggah operator.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t" style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
            {{ $items->appends(request()->query())->links() }}
        </div>
    </div>

</div>
@endsection
