@extends('layouts.app')
@section('title', 'Verifikasi Pencairan')

@section('content')
<div class="space-y-8" x-data="verificationPage()">

    {{-- Header Banner --}}
    <div class="sakdi-card w-full p-8 flex items-center justify-between flex-wrap gap-6"
         style="border-left: 4px solid var(--color-primary);">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg mb-2 text-xs font-extrabold"
                 style="background: var(--color-accent-50); border: 1px solid var(--color-accent-200); color: var(--color-accent-700);">
                <span>🏦 BENDAHARA INBOX VERIFIKASI</span>
            </div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-black tracking-tight" style="color: var(--color-neutral-900);">
                    Verifikasi Pencairan Dana Kegiatan
                </h1>
                {{-- Info Tooltip Icon --}}
                <div class="relative" x-data="{ showTip: false }">
                    <button type="button" @click="showTip = !showTip" @click.outside="showTip = false"
                            class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black transition-colors"
                            style="background: var(--color-primary-50); color: var(--color-primary); border: 1.5px solid var(--color-primary-200);"
                            title="Panduan Verifikasi">
                        ℹ️
                    </button>
                    <div x-show="showTip" x-transition
                         class="absolute left-1/2 -translate-x-1/2 top-full mt-2 w-80 p-4 rounded-xl shadow-xl z-50 text-xs leading-relaxed"
                         style="background: var(--color-white); border: 1px solid var(--color-neutral-300); color: var(--color-neutral-700);">
                        <div class="font-extrabold text-sm mb-2" style="color: var(--color-neutral-900);">📋 Panduan Verifikasi</div>
                        <ol class="space-y-1.5 list-decimal list-inside">
                            <li>Klik <strong>"🔍 Tinjau & Verifikasi"</strong> untuk melihat ringkasan item.</li>
                            <li>Pada popup, periksa daftar dokumen dan riwayat aktivitas.</li>
                            <li>Klik <strong>"Buka Halaman Verifikasi"</strong> untuk masuk ke detail.</li>
                            <li>Centang setiap dokumen yang sudah diperiksa kebenarannya.</li>
                            <li>Klik <strong>"Setujui Pencairan"</strong> jika semua lengkap, atau <strong>"Tolak"</strong> jika ada yang kurang.</li>
                        </ol>
                        <div class="mt-3 p-2 rounded-lg text-[11px]" style="background: var(--color-primary-50); color: var(--color-primary-900);">
                            💡 Status pencairan aktif setelah 100% dokumen terverifikasi oleh Bendahara.
                        </div>
                    </div>
                </div>
            </div>
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
                            <button type="button"
                                    @click="openSummaryModal({{ $item->id }})"
                                    class="sakdi-btn sakdi-btn-primary sakdi-btn-sm">
                                <span>🔍 Tinjau & Verifikasi</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
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

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- PRE-VERIFICATION SUMMARY POPUP MODAL              --}}
    {{-- ══════════════════════════════════════════════════ --}}
    {{-- ══════════════════════════════════════════════════ --}}
    {{-- PRE-VERIFICATION SUMMARY POPUP MODAL (TELEPORTED)  --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <template x-teleport="body">
        <div x-show="showModal"
             @keydown.escape.window="showModal = false"
             class="fixed inset-0 flex items-center justify-center p-3 sm:p-6"
             style="display: none; position: fixed; inset: 0; z-index: 99999;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-cloak>

            {{-- Full-screen dark backdrop with glass blur --}}
            <div class="fixed inset-0"
                 style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.72); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); z-index: 99999;"
                 @click="showModal = false"></div>

            {{-- Centered Dialog Box --}}
            <div class="relative flex flex-col overflow-hidden"
                 style="z-index: 100000; width: 100%; max-width: 680px; max-height: 86vh; background: #ffffff; border-radius: 20px; box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(0,0,0,0.06);"
                 @click.stop>

                {{-- Modal Header --}}
                <div class="px-6 py-4 shrink-0 flex items-center justify-between"
                     style="background: linear-gradient(135deg, #002D5C 0%, #0057A8 100%);">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                             style="background: rgba(255,255,255,0.15);">
                            <span class="text-white text-xl">📋</span>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-base font-extrabold text-white truncate">Ringkasan Item Verifikasi</h3>
                            <p class="text-xs" style="color: rgba(255,255,255,0.75);">Tinjau kelengkapan berkas dan riwayat sebelum verifikasi</p>
                        </div>
                    </div>
                    <button type="button" @click="showModal = false"
                            class="p-2 rounded-lg transition-colors"
                            style="color: rgba(255,255,255,0.8); background: rgba(255,255,255,0.12);"
                            onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.12)'"
                            title="Tutup (Esc)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Modal Body (Scrollable) --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-5" style="min-height: 0;">

                    {{-- Loading State --}}
                    <div x-show="modalLoading" class="flex flex-col items-center justify-center py-12">
                        <div class="w-9 h-9 rounded-full border-3 border-t-transparent animate-spin mb-3"
                             style="border-color: var(--color-primary); border-top-color: transparent;"></div>
                        <p class="text-xs font-semibold" style="color: var(--color-neutral-500);">Memuat ringkasan data item...</p>
                    </div>

                    <template x-if="!modalLoading && modalData">
                        <div class="space-y-5">

                            {{-- Item Info Card --}}
                            <div class="p-4 rounded-xl border" style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
                                <div class="flex items-start justify-between gap-4 flex-wrap">
                                    <div class="flex-1 min-w-[200px]">
                                        <div class="flex items-center gap-2 flex-wrap mb-2">
                                            <span class="num-mono text-xs font-black px-2.5 py-1 rounded-lg"
                                                  style="color: var(--color-primary-900); background: var(--color-primary-50); border: 1px solid var(--color-primary-100);"
                                                  x-text="'KODE: ' + modalData.item.code"></span>
                                            <span class="sakdi-badge text-xs"
                                                  :class="modalData.item.verification_status === 'APPROVED' ? 'sakdi-badge-success' : (modalData.item.verification_status === 'REJECTED' ? 'sakdi-badge-error' : 'sakdi-badge-warning')"
                                                  x-text="modalData.item.verification_status === 'APPROVED' ? '✓ Siap Cair' : (modalData.item.verification_status === 'REJECTED' ? '✕ Ditolak' : '⏳ Pending')"></span>
                                        </div>
                                        <h4 class="text-sm font-extrabold" style="color: var(--color-neutral-900);" x-text="modalData.item.name"></h4>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-[10px] font-bold uppercase tracking-wider" style="color: var(--color-neutral-500);">Pagu Anggaran</div>
                                        <div class="text-lg font-black num-mono" style="color: var(--color-positive-700);" x-text="modalData.item.pagu_formatted"></div>
                                    </div>
                                </div>

                                {{-- Rejection Note (if rejected) --}}
                                <template x-if="modalData.item.rejection_note">
                                    <div class="mt-3 p-3 rounded-lg text-xs leading-relaxed" style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B;">
                                        <strong>📝 Catatan Penolakan:</strong> <span x-text="modalData.item.rejection_note"></span>
                                    </div>
                                </template>
                            </div>

                            {{-- Documents Summary --}}
                            <div>
                                <div class="flex items-center justify-between mb-2.5">
                                    <h4 class="text-xs font-extrabold uppercase tracking-wider" style="color: var(--color-neutral-700);">📄 Berkas Dokumen SPJ</h4>
                                    <span class="sakdi-badge text-xs font-bold"
                                          :class="modalData.item.checked_count === modalData.item.documents_count && modalData.item.documents_count > 0 ? 'sakdi-badge-success' : 'sakdi-badge-warning'"
                                          x-text="modalData.item.checked_count + ' / ' + modalData.item.documents_count + ' Terverifikasi'"></span>
                                </div>
                                <div class="space-y-1.5 max-h-44 overflow-y-auto pr-0.5">
                                    <template x-for="doc in modalData.documents" :key="doc.id">
                                        <div class="flex items-center gap-2 p-2.5 rounded-lg border text-xs"
                                             style="background: var(--color-white); border-color: var(--color-neutral-300);">
                                            <span class="text-sm" x-text="doc.is_checked ? '✅' : '⏳'"></span>
                                            <span class="text-sm" x-text="doc.file_type === 'pdf' ? '📄' : '🖼️'"></span>
                                            <span class="font-bold truncate flex-1" style="color: var(--color-neutral-900);" x-text="doc.file_name"></span>
                                            <span class="sakdi-badge sakdi-badge-neutral text-[10px]" x-text="doc.label"></span>
                                            <span class="text-[10px]" style="color: var(--color-neutral-500);" x-text="doc.uploader"></span>
                                        </div>
                                    </template>
                                    <template x-if="modalData.documents.length === 0">
                                        <p class="text-xs italic text-center py-4" style="color: var(--color-neutral-500);">Belum ada dokumen yang diunggah.</p>
                                    </template>
                                </div>
                            </div>

                            {{-- Activity Logs with Pagination --}}
                            <div>
                                <div class="flex items-center justify-between mb-2.5">
                                    <h4 class="text-xs font-extrabold uppercase tracking-wider" style="color: var(--color-neutral-700);">📜 Riwayat &amp; Audit Log</h4>
                                    <span class="text-xs" style="color: var(--color-neutral-500);">
                                        <strong class="num-mono font-bold" style="color: var(--color-neutral-700);" x-text="modalData.logs.total"></strong> log tercatat
                                    </span>
                                </div>

                                <div class="space-y-2 max-h-52 overflow-y-auto pr-1">
                                    <template x-for="(log, idx) in modalData.logs.data" :key="idx">
                                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg border text-xs"
                                             style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
                                            <span class="w-2.5 h-2.5 rounded-full shrink-0 mt-1"
                                                  :style="'background:' + (log.action.includes('APPROVED') ? '#10B981' : (log.action.includes('REJECTED') ? '#EF4444' : (log.action.includes('UPLOAD') ? '#3B82F6' : (log.action.includes('DELETE') ? '#F59E0B' : '#6B7280'))))"></span>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-2 mb-0.5 flex-wrap">
                                                    <span class="font-bold uppercase text-[10px]" style="color: var(--color-neutral-600);"
                                                          x-text="log.user_name + ' (' + log.user_role + ')'"></span>
                                                    <span class="num-mono text-[10px] shrink-0" style="color: var(--color-neutral-500);" x-text="log.created_at"></span>
                                                </div>
                                                <p class="font-medium leading-relaxed" style="color: var(--color-neutral-800);" x-text="log.description"></p>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="modalData.logs.data.length === 0">
                                        <p class="text-xs italic text-center py-4" style="color: var(--color-neutral-500);">Belum ada riwayat aktivitas.</p>
                                    </template>
                                </div>

                                {{-- Pagination Controls --}}
                                <template x-if="modalData.logs.last_page > 1">
                                    <div class="flex items-center justify-center gap-2 mt-3 pt-3" style="border-top: 1px solid var(--color-neutral-200);">
                                        <button type="button"
                                                @click="loadLogPage(modalItemId, modalData.logs.current_page - 1)"
                                                :disabled="modalData.logs.current_page <= 1"
                                                :class="modalData.logs.current_page <= 1 ? 'opacity-40 cursor-not-allowed' : ''"
                                                class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm text-xs">
                                            ← Sebelumnya
                                        </button>
                                        <span class="text-xs font-semibold px-2" style="color: var(--color-neutral-600);">
                                            Halaman <strong class="num-mono" x-text="modalData.logs.current_page"></strong> dari <strong class="num-mono" x-text="modalData.logs.last_page"></strong>
                                        </span>
                                        <button type="button"
                                                @click="loadLogPage(modalItemId, modalData.logs.current_page + 1)"
                                                :disabled="modalData.logs.current_page >= modalData.logs.last_page"
                                                :class="modalData.logs.current_page >= modalData.logs.last_page ? 'opacity-40 cursor-not-allowed' : ''"
                                                class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm text-xs">
                                            Selanjutnya →
                                        </button>
                                    </div>
                                </template>
                            </div>

                        </div>
                    </template>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-3.5 shrink-0 flex items-center justify-between gap-3"
                     style="background: var(--color-neutral-50); border-top: 1px solid var(--color-neutral-300);">
                    <button type="button" @click="showModal = false" class="sakdi-btn sakdi-btn-secondary">
                        Tutup
                    </button>
                    <a :href="modalItemId ? '/items/' + modalItemId + '?from=verification' : '#'"
                       class="sakdi-btn sakdi-btn-primary font-extrabold flex items-center gap-2">
                        <span>🔍 Buka Halaman Verifikasi</span>
                        <span>→</span>
                    </a>
                </div>
            </div>
        </div>
    </template>

</div>

<script>
function verificationPage() {
    return {
        showModal: false,
        modalLoading: false,
        modalData: null,
        modalItemId: null,

        async openSummaryModal(itemId) {
            this.modalItemId = itemId;
            this.showModal = true;
            this.modalLoading = true;
            this.modalData = null;

            try {
                const res = await fetch('/verification/items/' + itemId + '/summary', {
                    headers: { 'Accept': 'application/json' }
                });
                this.modalData = await res.json();
            } catch (e) {
                console.error('Failed to load item summary:', e);
            } finally {
                this.modalLoading = false;
            }
        },

        async loadLogPage(itemId, page) {
            if (page < 1 || (this.modalData && page > this.modalData.logs.last_page)) return;
            try {
                const res = await fetch('/verification/items/' + itemId + '/summary?page=' + page, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                // Only update logs, keep item & documents same
                this.modalData.logs = data.logs;
            } catch (e) {
                console.error('Failed to load log page:', e);
            }
        }
    };
}
</script>
@endsection

