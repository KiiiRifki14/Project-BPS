@extends('layouts.app')
@section('title', "Item {$item->code}")

@section('content')
<div class="space-y-8">

    {{-- ── BREADCRUMB TRAIL & BACK TO VERIFICATION ── --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <nav class="sakdi-breadcrumb sakdi-card px-5 py-3 flex-1">
            <a href="{{ route('dashboard') }}" class="hover:underline">🏠 Dashboard</a>
            <span class="sakdi-breadcrumb-sep">/</span>
            <span class="num-mono">[{{ $breadcrumb['program']->code }}]</span>
            <span class="sakdi-breadcrumb-sep">/</span>
            <span class="num-mono">[{{ $breadcrumb['output']->code }}]</span>
            <span class="sakdi-breadcrumb-sep">/</span>
            <span class="num-mono">[{{ $breadcrumb['sub_output']->code }}]</span>
            <span class="sakdi-breadcrumb-sep">/</span>
            <span class="num-mono">[{{ $breadcrumb['component']->code }}]</span>
            <span class="sakdi-breadcrumb-sep">/</span>
            <span class="num-mono">[{{ $breadcrumb['sub_component']->code }}]</span>
            <span class="sakdi-breadcrumb-sep">/</span>
            <span class="num-mono">[{{ $breadcrumb['account']->code }}]</span>
            <span class="sakdi-breadcrumb-sep">/</span>
            <span class="num-mono font-bold sakdi-badge sakdi-badge-primary">Item {{ $item->code }}</span>
        </nav>

        @if(request()->query('from') === 'verification' || auth()->user()->isBendahara())
            <a href="{{ route('verification.index') }}" class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm font-extrabold">
                ← Kembali ke Inbox Verifikasi
            </a>
        @endif
    </div>

    {{-- ── ITEM HEADER CARD ── --}}
    <div class="sakdi-card w-full p-8 relative overflow-hidden">

        {{-- Verifikasi Stepper --}}
        <div class="sakdi-stepper mb-6 pb-6" style="border-bottom: 1px solid var(--color-neutral-300);">
            <div class="sakdi-stepper-step {{ $item->documents->count() > 0 ? 'done' : 'active' }}">
                <div class="sakdi-step-indicator">{{ $item->documents->count() > 0 ? '✓' : '1' }}</div>
                <div class="sakdi-step-label">Dokumen SPJ ({{ $item->documents->count() }})</div>
            </div>
            <div class="sakdi-stepper-step {{ $item->verification_status === 'APPROVED' ? 'done' : ($item->verification_status === 'REJECTED' ? 'error' : ($item->documents->count() > 0 ? 'active' : '')) }}">
                <div class="sakdi-step-indicator">
                    {{ $item->verification_status === 'APPROVED' ? '✓' : ($item->verification_status === 'REJECTED' ? '✕' : '2') }}
                </div>
                <div class="sakdi-step-label">Verifikasi Bendahara</div>
            </div>
            <div class="sakdi-stepper-step {{ $item->verification_status === 'APPROVED' ? 'done' : ($item->verification_status === 'REJECTED' ? 'error' : '') }}">
                <div class="sakdi-step-indicator">
                    {{ $item->verification_status === 'APPROVED' ? '✓' : ($item->verification_status === 'REJECTED' ? '✕' : '3') }}
                </div>
                <div class="sakdi-step-label">Pencairan Dana</div>
            </div>
        </div>

        <div class="flex items-start justify-between flex-wrap gap-6">
            <div class="flex-1 min-w-[280px]">
                <div class="flex items-center gap-3 flex-wrap mb-3">
                    <span class="num-mono text-xs font-extrabold px-3.5 py-1 rounded-lg"
                          style="color: var(--color-primary-900); background: var(--color-primary-50); border: 1px solid var(--color-primary-100);">
                        KODE ITEM: {{ $item->code }}
                    </span>

                    @if($item->verification_status === 'APPROVED')
                        <span class="sakdi-badge sakdi-badge-success text-xs py-1 px-3">
                            ✓ Siap Cair (Approved)
                        </span>
                    @elseif($item->verification_status === 'REJECTED')
                        <span class="sakdi-badge sakdi-badge-error text-xs py-1 px-3">
                            ✕ Ditolak — Butuh Revisi
                        </span>
                    @else
                        <span class="sakdi-badge sakdi-badge-warning text-xs py-1 px-3">
                            ⏳ Menunggu Verifikasi
                        </span>
                    @endif
                </div>

                <h1 class="text-xl sm:text-2xl font-black tracking-tight mb-2" style="color: var(--color-neutral-900);">
                    {{ $item->name }}
                </h1>

                <div class="text-xs space-y-1" style="color: var(--color-neutral-500);">
                    <div>📂 Akun: <strong class="num-mono" style="color: var(--color-neutral-900);">{{ $item->account->code }}</strong> — {{ $item->account->name }}</div>
                    <div>🗂 Sub-Komponen: <strong class="num-mono" style="color: var(--color-neutral-900);">{{ $breadcrumb['sub_component']->code }}</strong> — {{ $breadcrumb['sub_component']->name }}</div>
                </div>
            </div>

            <div class="text-right">
                <div class="sakdi-overline mb-1">Pagu Anggaran</div>
                <div class="text-2xl font-black num-mono tracking-tight" style="color: var(--color-positive-700);">
                    {{ $item->pagu_formatted }}
                </div>
                <div class="text-xs font-semibold mt-1" style="color: var(--color-neutral-500);">
                    {{ $item->documents->count() }} dokumen terunggah
                </div>
            </div>
        </div>

        {{-- Rejection Note Alert --}}
        @if($item->verification_status === 'REJECTED' && $item->rejection_note)
        <div class="sakdi-alert sakdi-alert-error mt-6">
            <svg class="sakdi-alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <div class="font-extrabold text-xs uppercase tracking-wider mb-1">📝 Catatan Penolakan Bendahara:</div>
                <div class="text-sm font-semibold leading-relaxed">{{ $item->rejection_note }}</div>
            </div>
        </div>
        @endif
    </div>


    {{-- ── MAIN WORKSPACE GRID ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- LEFT COLUMN: DOCUMENT LIST & DROPZONE --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Upload Dropzone Form --}}
            @if(auth()->user()->canUpload())
            <div class="sakdi-card p-6" x-data="fileUploader()">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-extrabold flex items-center gap-2" style="color: var(--color-neutral-900);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-primary);" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <span>Unggah Dokumen SPJ Baru</span>
                    </h2>
                    <span class="text-xs font-semibold" style="color: var(--color-neutral-500);">PDF, JPG, PNG • Max 15MB</span>
                </div>

                @if($item->verification_status === 'APPROVED')
                    <div class="sakdi-alert sakdi-alert-info">
                        <svg class="sakdi-alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <span>Item sudah disetujui Bendahara. Dokumen terkunci dan tidak dapat ditambahkan lagi.</span>
                    </div>
                @else
                    <form action="{{ route('documents.store', $item) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Dropzone Container --}}
                        <div class="border-2 border-dashed rounded-xl p-8 text-center cursor-pointer transition-all hover:bg-blue-50/50"
                             style="border-color: var(--color-neutral-300); background: var(--color-neutral-50);"
                             @click="$refs.fileInput.click()"
                             @dragover.prevent="isDragOver = true"
                             @dragleave="isDragOver = false"
                             @drop.prevent="handleDrop($event)">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3 text-xl"
                                 style="background: var(--color-primary-50); border: 1px solid var(--color-primary-100); color: var(--color-primary-900);">
                                📂
                            </div>
                            <div class="text-sm font-extrabold" style="color: var(--color-neutral-900);">Klik atau seret file dokumen ke sini</div>
                            <div class="text-xs font-medium mt-1" style="color: var(--color-neutral-500);">PDF, JPG, PNG • Maksimal 15 MB per file • Multi-file didukung</div>
                        </div>

                        <input type="file" x-ref="fileInput" name="files[]" multiple
                               accept=".pdf,.jpg,.jpeg,.png" class="hidden" style="display:none;"
                               @change="handleFiles($event.target.files)">

                        {{-- Selected Files Preview List --}}
                        <div x-show="files.length > 0" class="mt-5 space-y-3" x-transition>
                            <div class="text-xs font-bold uppercase tracking-wider" style="color: var(--color-neutral-700);">
                                File Dipilih (<span x-text="files.length"></span>):
                            </div>

                            <template x-for="(file, index) in files" :key="index">
                                <div class="flex items-center justify-between gap-3 p-3 rounded-xl border shadow-sm"
                                     style="background: var(--color-white); border-color: var(--color-neutral-300);">
                                    <!-- Icon & File Info -->
                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                        <div class="p-2 rounded-lg shrink-0" style="background: var(--color-primary-50); color: var(--color-primary);">
                                            <span x-text="fileIcon(file.name)" class="text-xl"></span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-bold truncate" style="color: var(--color-neutral-900);" x-text="file.name"></p>
                                            <p class="text-[10px] num-mono" style="color: var(--color-neutral-500);" x-text="formatSize(file.size)"></p>
                                        </div>
                                    </div>

                                    <!-- Dropdown Label Kategori -->
                                    <div class="shrink-0">
                                        <select :name="'labels[' + index + ']'" required class="sakdi-select text-xs py-1.5 px-2.5">
                                            <option value="" disabled selected>-- Pilih Label Dokumen (Wajib) --</option>
                                            <option value="BAPP Honor">BAPP Honor</option>
                                            <option value="Kuitansi">Kuitansi</option>
                                            <option value="KAK">KAK (Kerangka Acuan Kerja)</option>
                                            <option value="SK Petugas">SK Petugas</option>
                                            <option value="Daftar Hadir">Daftar Hadir / Penerima</option>
                                            <option value="SPJ Perjalanan Dinas">SPJ Perjalanan Dinas</option>
                                            <option value="Lainnya">Dokumen Pendukung Lainnya</option>
                                        </select>
                                    </div>

                                    <!-- Tombol Hapus Pilihan -->
                                    <button type="button" @click="removeFile(index)" class="p-1.5 hover:bg-rose-50 rounded-lg transition-colors shrink-0" style="color: var(--color-neutral-500);">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </template>

                            <button type="submit" class="sakdi-btn sakdi-btn-primary w-full py-3 text-sm font-extrabold">
                                <span>⬆️ Unggah</span>
                                <span x-text="files.length"></span>
                                <span>Dokumen ke Sistem</span>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
            @endif

            {{-- ── UPLOADED DOCUMENTS TABLE (DOKUMEN TERSIMPAN) ── --}}
            <div class="sakdi-table-wrapper w-full">
                <div class="px-6 py-4 flex items-center justify-between flex-wrap gap-3"
                     style="background: var(--color-neutral-50); border-bottom: 1px solid var(--color-neutral-300);">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full" style="background: var(--color-primary);"></span>
                        <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900);">Dokumen Tersimpan</h2>
                    </div>
                    <span class="sakdi-badge sakdi-badge-primary font-mono text-xs">
                        {{ $item->documents->count() }} File Terlampir
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="sakdi-table">
                        <thead>
                            <tr>
                                <th class="w-10 text-center">#</th>
                                <th>Nama File Dokumen</th>
                                <th>Label Berkas</th>
                                <th>Ukuran</th>
                                <th>Pengunggah</th>
                                <th class="text-center">Status Verifikasi</th>
                                <th class="text-center w-36">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($item->documents as $i => $doc)
                            <tr>
                                <td class="text-center text-xs num-mono" style="color: var(--color-neutral-500);">{{ $i + 1 }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xl">
                                            @if($doc->isPdf()) 📄 @else 🖼️ @endif
                                        </span>
                                        <div class="min-w-0">
                                            <div class="font-bold text-xs truncate max-w-xs" style="color: var(--color-neutral-900);">{{ $doc->file_name }}</div>
                                            <div class="text-[10px] num-mono uppercase mt-0.5" style="color: var(--color-neutral-500);">
                                                {{ $doc->file_type }} • {{ $doc->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($doc->label)
                                        <span class="sakdi-badge sakdi-badge-primary text-xs">
                                            🏷️ {{ $doc->label }}
                                        </span>
                                    @else
                                        <span class="text-xs" style="color: var(--color-neutral-500);">—</span>
                                    @endif
                                </td>
                                <td class="text-xs num-mono whitespace-nowrap" style="color: var(--color-neutral-700);">{{ $doc->file_size_formatted }}</td>
                                <td class="text-xs whitespace-nowrap" style="color: var(--color-neutral-700);">{{ $doc->uploadedBy->name }}</td>
                                <td class="text-center whitespace-nowrap">
                                    @if($doc->is_checked)
                                        <span class="sakdi-badge sakdi-badge-success text-xs" title="Dicentang oleh {{ $doc->checkedBy->name ?? 'Bendahara' }} pada {{ $doc->checked_at ? $doc->checked_at->format('d/m/Y H:i') : '' }}">
                                            ✓ Verified
                                        </span>
                                    @else
                                        <span class="sakdi-badge sakdi-badge-warning text-xs">
                                            ⏳ Belum Dicentang
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5" x-data>
                                        {{-- Stream Inline Preview Modal Button --}}
                                        <button type="button"
                                                @click="$dispatch('open-preview-modal', { url: '{{ route('documents.stream', $doc) }}', title: '{{ addslashes($doc->file_name) }}', type: '{{ $doc->file_type }}' })"
                                                class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm" title="Pratinjau Dokumen">
                                            👁️
                                        </button>

                                        {{-- Download Button --}}
                                        <a href="{{ route('documents.download', $doc) }}"
                                           class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm" title="Unduh Dokumen">
                                            ⬇️
                                        </a>

                                        {{-- Delete Button (Guard 4 Ownership) --}}
                                        @if($item->verification_status !== 'APPROVED' && auth()->user()->canUpload() && (auth()->user()->isAdmin() || auth()->user()->isSupervisor() || $doc->uploaded_by_user_id === auth()->id()))
                                        <form action="{{ route('documents.destroy', $doc) }}" method="POST"
                                              onsubmit="return confirm('Hapus dokumen ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="sakdi-btn sakdi-btn-danger sakdi-btn-sm" title="Hapus Dokumen">🗑️</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-14" style="color: var(--color-neutral-500);">
                                    <div class="text-3xl mb-2">📂</div>
                                    <div class="font-extrabold text-sm" style="color: var(--color-neutral-700);">Belum ada dokumen yang diunggah</div>
                                    <div class="text-xs mt-1" style="color: var(--color-neutral-500);">Unggah dokumen SPJ, BAPP, atau Kuitansi pada form di atas.</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: BENDAHARA ACTION CONTROL PANEL --}}
        <div class="space-y-6 lg:sticky lg:top-24">

            {{-- Container Panel Verifikasi Bendahara --}}
            @if(auth()->user()->role === 'BENDAHARA' || auth()->user()->role === 'ADMIN')
                <div x-data="{
                    checkedDocs: {{ json_encode($item->documents->pluck('is_checked', 'id')->map(fn($v) => (bool)$v)) }},
                    totalDocs: {{ $item->documents->count() }},
                    get checkedCount() {
                        return Object.values(this.checkedDocs).filter(Boolean).length;
                    },
                    get canApprove() {
                        return this.totalDocs > 0 && this.checkedCount === this.totalDocs;
                    },
                    showRejectModal: false,
                    async toggleDocCheck(docId, event) {
                        const isChecked = event.target.checked;
                        try {
                            const res = await fetch('/documents/' + docId + '/check', {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ is_checked: isChecked })
                            });
                            const data = await res.json();
                            if (data.success) {
                                this.checkedDocs[docId] = data.is_checked;
                            } else {
                                alert(data.error || 'Gagal menyimpan status verifikasi.');
                                event.target.checked = !isChecked;
                                this.checkedDocs[docId] = !isChecked;
                            }
                        } catch (e) {
                            alert('Terjadi kesalahan koneksi saat menyimpan checklist.');
                            event.target.checked = !isChecked;
                            this.checkedDocs[docId] = !isChecked;
                        }
                    }
                }" class="sakdi-card p-6 space-y-4">

                    <h3 class="font-extrabold text-sm flex items-center gap-2" style="color: var(--color-neutral-900);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-primary);" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <span>Panel Verifikasi Bendahara</span>
                    </h3>

                    <!-- Status Item Saat Ini -->
                    @if($item->verification_status === 'APPROVED')
                        <div class="sakdi-alert sakdi-alert-success text-xs font-semibold">
                            <svg class="sakdi-alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Item ini telah disetujui oleh Bendahara. Status terkunci.</span>
                        </div>
                    @else
                        <!-- Box Ceklis Dokumen -->
                        <div class="p-4 rounded-xl border space-y-3"
                             style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
                            <div class="flex items-center justify-between text-xs font-extrabold mb-1"
                                 style="color: var(--color-neutral-700);">
                                <span>📋 CEKLIS VERIFIKASI BERKAS</span>
                                <span :class="canApprove ? 'sakdi-badge sakdi-badge-success' : 'sakdi-badge sakdi-badge-warning'"
                                      x-text="checkedCount + ' / ' + totalDocs + ' Dokumen Terverifikasi'"></span>
                            </div>

                            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                @forelse($item->documents as $doc)
                                    <label class="flex items-center gap-2 p-2 rounded-lg border cursor-pointer text-xs transition-colors"
                                           style="background: var(--color-white); border-color: var(--color-neutral-300);">
                                        <input
                                            type="checkbox"
                                            :checked="checkedDocs['{{ $doc->id }}']"
                                            @change="toggleDocCheck('{{ $doc->id }}', $event)"
                                            @if($item->verification_status === 'APPROVED') disabled @endif
                                            class="rounded border-slate-300 w-4 h-4 disabled:opacity-75 disabled:cursor-not-allowed"
                                            style="accent-color: var(--color-primary);"
                                        >
                                        <span class="font-bold truncate flex-1" style="color: var(--color-neutral-900);">{{ $doc->file_name }}</span>
                                        <span class="sakdi-badge sakdi-badge-neutral text-[10px] mr-1">{{ $doc->label ?? 'Dokumen' }}</span>
                                        <button type="button"
                                                @click.stop="$dispatch('open-preview-modal', { url: '{{ route('documents.stream', $doc) }}', title: '{{ addslashes($doc->file_name) }}', type: '{{ $doc->file_type }}' })"
                                                class="p-1 hover:underline" style="color: var(--color-primary);">
                                            👁️
                                        </button>
                                    </label>
                                @empty
                                    <p class="text-xs italic p-2 text-center" style="color: var(--color-neutral-500);">Belum ada dokumen terunggah.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Form Setujui Pencairan -->
                        <form action="{{ route('items.verify', $item) }}" method="POST" class="space-y-2 mt-4">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="APPROVED">

                            <button
                                type="submit"
                                :disabled="!canApprove"
                                :class="canApprove ? 'sakdi-btn sakdi-btn-success w-full' : 'sakdi-btn sakdi-btn-secondary w-full opacity-60 cursor-not-allowed'"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Setujui Pencairan (Approved)</span>
                            </button>
                        </form>

                        <!-- Tombol Tolak / Minta Revisi -->
                        <button
                            type="button"
                            @click="showRejectModal = true"
                            class="sakdi-btn sakdi-btn-danger w-full"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Tolak / Minta Revisi</span>
                        </button>

                        {{-- Rejection Modal --}}
                        <div x-show="showRejectModal"
                             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
                             style="display:none;"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">
                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showRejectModal = false"></div>
                            <div class="sakdi-card max-w-lg w-full p-6 relative z-10 space-y-4">
                                <h3 class="text-base font-black flex items-center gap-2" style="color: var(--color-error);">
                                    <span>❌ Tolak &amp; Minta Revisi Berkas</span>
                                </h3>
                                <p class="text-xs" style="color: var(--color-neutral-500);">
                                    Masukkan alasan atau catatan penolakan secara spesifik agar Operator dapat memperbaiki berkas SPJ.
                                </p>
                                <form action="{{ route('items.verify', $item) }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="action" value="REJECTED">

                                    <div>
                                        <label class="sakdi-label">Catatan Penolakan / Catatan Revisi (Wajib)</label>
                                        <textarea name="rejection_note" required rows="4"
                                                  placeholder="Contoh: Lampiran Kuitansi honor belum ditandatangani oleh penerima..."
                                                  class="sakdi-input w-full text-xs" style="min-height: 100px;"></textarea>
                                    </div>

                                    <div class="flex items-center justify-end gap-3 pt-2">
                                        <button type="button" @click="showRejectModal = false" class="sakdi-btn sakdi-btn-secondary">
                                            Batal
                                        </button>
                                        <button type="submit" class="sakdi-btn sakdi-btn-danger">
                                            Kirim Penolakan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

        </div>

    </div>

</div>

{{-- ── INLINE DOCUMENT STREAM PREVIEW MODAL ── --}}
<div x-data="{
        open: false,
        url: '',
        title: '',
        type: '',
        init() {
            window.addEventListener('open-preview-modal', (e) => {
                this.url   = e.detail.url;
                this.title = e.detail.title;
                this.type  = e.detail.type;
                this.open  = true;
            });
        }
    }"
    x-show="open"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
    style="display:none;"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-cloak>
    <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" @click="open = false"></div>

    <div class="sakdi-card w-full max-w-5xl h-[85vh] flex flex-col relative z-10 p-0 overflow-hidden shadow-2xl">
        <div class="px-6 py-4 border-b flex items-center justify-between"
             style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
            <div class="flex items-center gap-3 min-w-0">
                <span class="text-xl">📄</span>
                <div class="min-w-0">
                    <h3 class="text-sm font-extrabold truncate" style="color: var(--color-neutral-900);" x-text="title"></h3>
                    <p class="text-[10px] num-mono uppercase" style="color: var(--color-neutral-500);" x-text="'Pratinjau Langsung • ' + type"></p>
                </div>
            </div>
            <button type="button" @click="open = false" class="p-2 rounded-lg hover:bg-slate-200" style="color: var(--color-neutral-700);">
                ✕
            </button>
        </div>

        <div class="flex-1 bg-slate-900 relative">
            <template x-if="type === 'pdf'">
                <iframe :src="url" class="w-full h-full border-none"></iframe>
            </template>
            <template x-if="type !== 'pdf'">
                <div class="w-full h-full flex items-center justify-center p-4 overflow-auto">
                    <img :src="url" :alt="title" class="max-w-full max-h-full object-contain rounded shadow-md">
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function fileUploader() {
    return {
        files: [],
        isDragOver: false,
        handleFiles(fileList) {
            for (let i = 0; i < fileList.length; i++) {
                this.files.push(fileList[i]);
            }
        },
        handleDrop(e) {
            this.isDragOver = false;
            if (e.dataTransfer.files.length) {
                this.handleFiles(e.dataTransfer.files);
            }
        },
        removeFile(index) {
            this.files.splice(index, 1);
        },
        fileIcon(name) {
            const ext = name.split('.').pop().toLowerCase();
            return ext === 'pdf' ? '📄' : '🖼️';
        },
        formatSize(bytes) {
            if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
            return (bytes / 1024).toFixed(2) + ' KB';
        }
    };
}
</script>
@endsection
