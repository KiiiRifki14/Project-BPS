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
            <div class="card-corporate p-6" x-data="fileUploader()">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <span>Unggah Dokumen SPJ Baru</span>
                    </h2>
                    <span class="text-xs font-semibold text-slate-500">PDF, JPG, PNG • Max 15MB</span>
                </div>

                @if($item->verification_status === 'APPROVED')
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold flex items-center gap-2">
                        <span>🔒</span>
                        <span>Item sudah disetujui Bendahara. Dokumen terkunci dan tidak dapat ditambahkan lagi.</span>
                    </div>
                @else
                    <form action="{{ route('documents.store', $item) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Dropzone Container --}}
                        <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center bg-slate-50 hover:bg-blue-50/50 hover:border-blue-700 cursor-pointer transition-all"
                             @click="$refs.fileInput.click()"
                             @dragover.prevent="isDragOver = true"
                             @dragleave="isDragOver = false"
                             @drop.prevent="handleDrop($event)">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 border border-blue-200 text-blue-800 flex items-center justify-center mx-auto mb-3 text-xl">
                                📂
                            </div>
                            <div class="text-sm font-extrabold text-slate-800">Klik atau seret file dokumen ke sini</div>
                            <div class="text-xs font-medium text-slate-500 mt-1">PDF, JPG, PNG • Maksimal 15 MB per file • Multi-file didukung</div>
                        </div>

                        <input type="file" x-ref="fileInput" name="files[]" multiple
                               accept=".pdf,.jpg,.jpeg,.png" class="hidden" style="display:none;"
                               @change="handleFiles($event.target.files)">

                        {{-- Selected Files Preview List --}}
                        <div x-show="files.length > 0" class="mt-5 space-y-3" x-transition>
                            <div class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                File Dipilih (<span x-text="files.length"></span>):
                            </div>

                            <template x-for="(file, index) in files" :key="index">
                                <div class="flex items-center justify-between gap-3 p-3 bg-white border border-slate-200 rounded-xl shadow-sm">
                                    <!-- Icon & File Info -->
                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg shrink-0">
                                            <span x-text="fileIcon(file.name)" class="text-xl"></span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-bold text-slate-800 truncate" x-text="file.name"></p>
                                            <p class="text-[10px] text-slate-500 font-mono" x-text="formatSize(file.size)"></p>
                                        </div>
                                    </div>

                                    <!-- Dropdown Label Kategori -->
                                    <div class="shrink-0">
                                        <select :name="'labels[' + index + ']'" required class="text-xs rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 bg-slate-50 py-1.5 px-2.5 text-slate-700">
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
                                    <button type="button" @click="removeFile(index)" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </template>

                            <button type="submit" class="btn-bps btn-bps-primary w-full py-3 text-sm">
                                <span>⬆️ Unggah</span>
                                <span x-text="files.length"></span>
                                <span>Dokumen ke Sistem</span>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
            @endif

            {{-- Uploaded Documents Table --}}
            <div class="table-container-v4">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <h2 class="text-sm font-extrabold text-slate-900">Dokumen Tersimpan</h2>
                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-slate-200 text-slate-700 font-mono">
                        {{ $item->documents->count() }} File
                    </span>
                </div>

                <table class="table-v4">
                    <thead>
                        <tr>
                            <th class="w-10 text-center">#</th>
                            <th>Nama File Dokumen</th>
                            <th>Label Kuitansi/BAPP</th>
                            <th>Ukuran</th>
                            <th>Diunggah Oleh</th>
                            <th class="text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($item->documents as $i => $doc)
                        <tr>
                            <td class="text-center text-xs font-mono text-slate-400">{{ $i + 1 }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">
                                        @if($doc->isPdf()) 📄 @else 🖼️ @endif
                                    </span>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 text-xs truncate max-w-xs">{{ $doc->file_name }}</div>
                                        <div class="text-[10px] text-slate-500 font-mono uppercase">{{ $doc->file_type }} • {{ $doc->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($doc->label)
                                    <span class="font-semibold text-xs text-blue-900 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded">
                                        {{ $doc->label }}
                                    </span>
                                @else
                                    <span class="text-slate-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="text-xs font-mono text-slate-600 whitespace-nowrap">{{ $doc->file_size_formatted }}</td>
                            <td class="text-xs text-slate-600 whitespace-nowrap">{{ $doc->uploadedBy->name }}</td>
                            <td class="text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5" x-data>
                                    {{-- Stream Inline Preview Modal Button --}}
                                    <button type="button"
                                            @click="$dispatch('open-preview-modal', { url: '{{ route('documents.stream', $doc) }}', title: '{{ addslashes($doc->file_name) }}', type: '{{ $doc->file_type }}' })"
                                            class="btn-bps btn-bps-secondary btn-bps-sm">
                                        👁️ Preview
                                    </button>

                                    {{-- Download Button --}}
                                    <a href="{{ route('documents.download', $doc) }}" class="btn-bps btn-bps-secondary btn-bps-sm">
                                        ⬇️
                                    </a>

                                    {{-- Delete Button --}}
                                    @if($item->verification_status !== 'APPROVED' && auth()->user()->canUpload() && (auth()->user()->isAdmin() || auth()->user()->isSupervisor() || $doc->uploaded_by_user_id === auth()->id()))
                                    <form action="{{ route('documents.destroy', $doc) }}" method="POST"
                                          onsubmit="return confirm('Hapus dokumen ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-bps btn-bps-danger btn-bps-sm">🗑️</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-14 text-slate-400">
                                <div class="text-3xl mb-2">📂</div>
                                <div class="font-extrabold text-slate-700 text-sm">Belum ada dokumen yang diunggah</div>
                                <div class="text-xs text-slate-400 mt-1">Unggah dokumen SPJ, BAPP, atau Kuitansi pada form di atas.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        {{-- RIGHT COLUMN: BENDAHARA ACTION CONTROL PANEL --}}
        <div class="space-y-6 lg:sticky lg:top-24">

            {{-- Container Panel Verifikasi Bendahara --}}
            @if(auth()->user()->role === 'BENDAHARA')
                <div x-data="{
                    checkedDocs: {{ $item->verification_status === 'APPROVED' ? json_encode($item->documents->pluck('id')->mapWithKeys(fn($id) => [(string)$id => true])) : '{}' }},
                    totalDocs: {{ $item->documents->count() }},
                    get checkedCount() {
                        return Object.values(this.checkedDocs).filter(Boolean).length;
                    },
                    get canApprove() {
                        return this.totalDocs > 0 && this.checkedCount === this.totalDocs;
                    },
                    showRejectModal: false
                }" class="card-corporate p-6 bg-white border border-slate-200 shadow-sm space-y-4">

                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <span>Panel Verifikasi Bendahara</span>
                    </h3>

                    <!-- Status Item Saat Ini -->
                    @if($item->verification_status === 'APPROVED')
                        <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Item ini telah disetujui oleh Bendahara. Status terkunci.</span>
                        </div>
                    @else
                        <!-- Box Ceklis Dokumen -->
                        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between text-xs font-semibold text-slate-700 mb-1">
                                <span>📋 CEKLIS VERIFIKASI BERKAS</span>
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md font-mono text-[11px]" x-text="checkedCount + ' / ' + totalDocs + ' Terverifikasi'"></span>
                            </div>

                            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                @forelse($item->documents as $doc)
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer text-xs">
                                        <input 
                                            type="checkbox" 
                                            x-model="checkedDocs['{{ $doc->id }}']" 
                                            @if($item->verification_status === 'APPROVED') checked disabled @endif
                                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4 disabled:opacity-75 disabled:cursor-not-allowed"
                                        >
                                        <span class="font-medium text-slate-800 truncate flex-1">{{ $doc->file_name }}</span>
                                        <span class="text-[10px] text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded mr-1">{{ $doc->label ?? 'Dokumen' }}</span>
                                        <button type="button"
                                                @click.stop="$dispatch('open-preview-modal', { url: '{{ route('documents.stream', $doc) }}', title: '{{ addslashes($doc->file_name) }}', type: '{{ $doc->file_type }}' })"
                                                class="text-slate-400 hover:text-blue-800 p-1">
                                            👁️
                                        </button>
                                    </label>
                                @empty
                                    <p class="text-xs text-slate-400 italic p-2 text-center">Belum ada dokumen terunggah.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Form Setujui Pencairan (Harus SELALU Muncul) -->
                        <form action="{{ route('items.verify', $item) }}" method="POST" class="space-y-2 mt-4">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="APPROVED">

                            <button 
                                type="submit" 
                                :disabled="!canApprove"
                                :class="canApprove ? 'bg-emerald-600 hover:bg-emerald-700 text-white cursor-pointer shadow-md' : 'bg-slate-200 text-slate-400 cursor-not-allowed opacity-70'"
                                class="w-full py-3 px-4 font-semibold text-sm rounded-xl transition-all duration-200 flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Setujui Pencairan (Approved)</span>
                            </button>
                        </form>

                        <!-- Tombol Tolak / Minta Revisi -->
                        <button 
                            type="button" 
                            @click="showRejectModal = true" 
                            class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-sm rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Tolak / Minta Revisi</span>
                        </button>

                        {{-- Rejection Modal --}}
                        <div x-show="showRejectModal"
                             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
                             style="display:none;"
                             x-transition>
                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="showRejectModal = false"></div>
                            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden z-10 border border-slate-200 p-6">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                                    <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                        <span>❌ Tolak & Minta Revisi</span>
                                    </h3>
                                    <button type="button" @click="showRejectModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">×</button>
                                </div>
                                <form action="{{ route('items.verify', $item) }}" method="POST" class="space-y-4">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action" value="REJECTED">
                                    <div>
                                        <label class="form-label-custom text-slate-700 font-bold mb-1.5 block">Catatan Penolakan *</label>
                                        <textarea name="rejection_note" rows="4" class="form-input-v4 text-xs w-full resize-none" required
                                                  placeholder="Contoh: BAPP belum ditandatangani PPK, Kuitansi kurang legalisir..."></textarea>
                                    </div>
                                    <div class="flex justify-end gap-2.5">
                                        <button type="button" @click="showRejectModal = false" class="btn-bps btn-bps-secondary text-xs">
                                            Batal
                                        </button>
                                        <button type="submit" class="btn-bps btn-bps-danger text-xs font-black">
                                            Kirim Penolakan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>
            @endif

            {{-- Document Analytics Summary --}}
            <div class="card-corporate p-6">
                <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-4">RINGKASAN BERKAS</h3>
                @php
                    $pdfCount = $item->documents->filter(fn($d) => $d->isPdf())->count();
                    $imgCount = $item->documents->filter(fn($d) => $d->isImage())->count();
                    $totalSize = $item->documents->sum('file_size');
                @endphp
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between items-center text-slate-600">
                        <span>📄 File Dokumen PDF</span>
                        <span class="font-mono font-bold text-slate-900">{{ $pdfCount }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-600">
                        <span>🖼️ File Dokumen Gambar</span>
                        <span class="font-mono font-bold text-slate-900">{{ $imgCount }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-600 border-t border-slate-200 pt-2">
                        <span>💾 Total Ukuran Berkas</span>
                        <span class="font-mono font-bold text-blue-900">{{ $totalSize > 0 ? round($totalSize / 1048576, 2) . ' MB' : '0 KB' }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

{{-- ── INLINE STREAM PDF PREVIEW MODAL ── --}}
<div x-data="previewModal()" x-show="open" x-transition
     @open-preview-modal.window="openPreview($event.detail.url, $event.detail.title, $event.detail.type || 'pdf')"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" style="display:none;">

    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="close()"></div>

    {{-- Modal Box --}}
    <div class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden z-10 border border-slate-700 flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 text-white flex items-center justify-between border-b border-slate-700"
             style="background: var(--color-primary-900);">

            <div class="min-w-0 pr-4">
                <div class="text-sm font-extrabold truncate" x-text="fileName"></div>
                <div class="text-[11px] font-semibold text-slate-300 mt-0.5">Pratinjau Dokumen Inline — BPS Kabupaten Subang</div>
            </div>
            <button type="button" @click="close()" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-lg font-bold">
                ×
            </button>
        </div>

        <div class="flex-1 bg-slate-950 overflow-hidden relative min-h-[70vh]">
            <template x-if="fileType === 'pdf'">
                <iframe :src="fileUrl" class="w-full h-full min-h-[70vh] border-0" title="PDF Stream Viewer"></iframe>
            </template>
            <template x-if="fileType !== 'pdf'">
                <div class="flex items-center justify-center h-full min-h-[70vh] p-4 bg-slate-900">
                    <img :src="fileUrl" class="max-w-full max-h-[70vh] object-contain rounded-lg" :alt="fileName">
                </div>
            </template>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function fileUploader() {
    return {
        files: [],
        isDragOver: false,

        handleFiles(fileList) {
            Array.from(fileList).forEach(f => {
                if (f.size <= 15 * 1024 * 1024) {
                    this.files.push(f);
                } else {
                    alert(`File "${f.name}" melebihi batas 15 MB.`);
                }
            });
            this.syncInput();
        },

        handleDrop(event) {
            this.isDragOver = false;
            this.handleFiles(event.dataTransfer.files);
        },

        removeFile(index) {
            this.files.splice(index, 1);
            this.syncInput();
        },

        syncInput() {
            const dt = new DataTransfer();
            this.files.forEach(f => dt.items.add(f));
            this.$refs.fileInput.files = dt.files;
        },

        fileIcon(name) {
            const ext = name.split('.').pop().toLowerCase();
            return ext === 'pdf' ? '📄' : '🖼️';
        },

        formatSize(bytes) {
            if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
            return (bytes / 1024).toFixed(1) + ' KB';
        }
    };
}

function previewModal() {
    return {
        open: false,
        fileUrl: '',
        fileName: '',
        fileType: '',

        openPreview(url, name, type) {
            this.fileUrl = url;
            this.fileName = name;
            this.fileType = type ? type.toLowerCase() : (name.split('.').pop().toLowerCase() === 'pdf' ? 'pdf' : 'image');
            this.open = true;
        },

        close() {
            this.open = false;
            this.fileUrl = '';
        }
    };
}

function bendaharaChecklist(totalDocs, initialCheckedDocs) {
    return {
        checkedDocs: initialCheckedDocs || {},
        totalDocs: totalDocs,
        showRejectModal: false,
        get checkedCount() {
            return Object.values(this.checkedDocs).filter(Boolean).length;
        },
        get canApprove() {
            return this.totalDocs > 0 && this.checkedCount === this.totalDocs;
        }
    };
}

document.addEventListener('alpine:init', () => {
    window.openPreview = (url, name, type) => {
        document.querySelector('[x-data="previewModal()"]')?._x_dataStack?.[0]?.openPreview(url, name, type);
    };
});
</script>
@endpush
