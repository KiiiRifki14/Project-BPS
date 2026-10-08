@extends('layouts.app')
@section('title', "Item {$item->code}")

@section('content')
<div class="space-y-8">

    {{-- ── BREADCRUMB TRAIL & BACK TO VERIFICATION ── --}}
    <div class="flex items-center justify-between flex-wrap gap-3 sm:gap-4">
        <nav class="sakdi-breadcrumb sakdi-card px-4 sm:px-5 py-2.5 sm:py-3 flex-1 overflow-x-auto whitespace-nowrap text-xs sm:text-sm">
            <a href="{{ route('dashboard') }}" class="hover:underline inline-flex items-center gap-1.5 flex-shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Dashboard</span>
            </a>
            <span class="sakdi-breadcrumb-sep">/</span>
            <span class="num-mono flex-shrink-0">[{{ $breadcrumb['program']->code }}]</span>
            <span class="sakdi-breadcrumb-sep">/</span>
            <span class="num-mono flex-shrink-0">[{{ $breadcrumb['output']->code }}]</span>
            <span class="sakdi-breadcrumb-sep">/</span>
            <span class="num-mono flex-shrink-0">[{{ $breadcrumb['sub_output']->code }}]</span>
            <span class="sakdi-breadcrumb-sep">/</span>
            <span class="num-mono flex-shrink-0">[{{ $breadcrumb['component']->code }}]</span>
            <span class="sakdi-breadcrumb-sep">/</span>
            <span class="num-mono flex-shrink-0">[{{ $breadcrumb['sub_component']->code }}]</span>
            <span class="sakdi-breadcrumb-sep">/</span>
            <span class="num-mono flex-shrink-0">[{{ $breadcrumb['account']->code }}]</span>
            <span class="sakdi-breadcrumb-sep">/</span>
            <span class="num-mono font-bold sakdi-badge sakdi-badge-primary flex-shrink-0">Item {{ $item->code }}</span>
        </nav>

        <a href="{{ route('items.index') }}" class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm font-extrabold inline-flex items-center gap-1.5 flex-shrink-0 text-xs sm:text-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>{{ auth()->user()->isBendahara() ? 'Kembali ke Verifikasi & Arsip' : 'Kembali ke Arsip POK' }}</span>
        </a>
    </div>

    {{-- ── ITEM HEADER CARD ── --}}
    <div class="sakdi-card w-full p-5 sm:p-7 md:p-8 relative overflow-hidden">

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
                        <span class="sakdi-badge sakdi-badge-success text-xs py-1 px-3 inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Siap Cair (Approved)</span>
                        </span>
                    @elseif($item->verification_status === 'REJECTED')
                        <span class="sakdi-badge sakdi-badge-error text-xs py-1 px-3 inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Ditolak (Butuh Revisi)</span>
                        </span>
                    @else
                        <span class="sakdi-badge sakdi-badge-warning text-xs py-1 px-3 inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Menunggu Verifikasi</span>
                        </span>
                    @endif
                </div>

                <h1 class="text-xl sm:text-2xl font-black tracking-tight mb-2" style="color: var(--color-neutral-900);">
                    {{ $item->name }}
                </h1>

                <div class="text-xs space-y-1.5" style="color: var(--color-neutral-500);">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        <span>Akun:</span>
                        <strong class="num-mono" style="color: var(--color-neutral-900);">{{ $item->account->code }}</strong>
                        <span>: {{ $item->account->name }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Sub-Komponen:</span>
                        <strong class="num-mono" style="color: var(--color-neutral-900);">{{ $breadcrumb['sub_component']->code }}</strong>
                        <span>: {{ $breadcrumb['sub_component']->name }}</span>
                    </div>
                </div>
            </div>

            <div class="text-right flex flex-col items-end gap-2">
                <div>
                    <div class="sakdi-overline mb-1">Pagu Anggaran</div>
                    <div class="text-2xl font-black num-mono tracking-tight" style="color: var(--color-positive-700);">
                        {{ $item->pagu_formatted }}
                    </div>
                    <div class="text-xs font-semibold mt-1" style="color: var(--color-neutral-500);">
                        {{ $item->documents->count() }} dokumen terunggah
                    </div>
                </div>

                @if($item->documents->count() > 0)
                <a href="{{ route('items.download-zip', $item) }}" class="sakdi-btn sakdi-btn-primary sakdi-btn-sm text-xs font-extrabold shadow-sm mt-1 inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Unduh Semua Berkas (ZIP)</span>
                </a>
                @endif
            </div>
        </div>

    </div>


    {{-- ── MAIN WORKSPACE GRID ── --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">

        {{-- LEFT COLUMN: DOCUMENT LIST & DROPZONE --}}
        <div class="xl:col-span-8 space-y-6">

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
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3"
                                 style="background: var(--color-primary-50); border: 1px solid var(--color-primary-100); color: var(--color-primary);">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
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
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                                             :class="file.name.toLowerCase().endsWith('.pdf') ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-emerald-50 text-emerald-600 border border-emerald-200'">
                                            <template x-if="file.name.toLowerCase().endsWith('.pdf')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            </template>
                                            <template x-if="!file.name.toLowerCase().endsWith('.pdf')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </template>
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
                                    <button type="button" @click="removeFile(index)" class="p-1.5 hover:bg-rose-50 rounded-lg transition-colors shrink-0" style="color: var(--color-neutral-500);" aria-label="Hapus Pilihan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </template>

                            <button type="submit" class="sakdi-btn sakdi-btn-primary w-full py-3 text-sm font-extrabold inline-flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span>Unggah</span>
                                <span x-text="files.length"></span>
                                <span>Dokumen ke Sistem</span>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
            @else
            {{-- Panel Panduan Ruang Kerja Verifikasi Bendahara (Compact with Tooltip) --}}
            <div class="sakdi-card p-5" style="border-left: 4px solid var(--color-primary);" x-data="{ showGuide: false }">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="background: var(--color-primary-50); color: var(--color-primary);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900);">
                                Ruang Kerja Verifikasi SPJ
                            </h2>
                            <p class="text-xs font-medium" style="color: var(--color-neutral-500);">
                                Tinjau keabsahan berkas fisik sebelum menetapkan persetujuan.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="sakdi-badge sakdi-badge-warning text-xs font-bold">
                            Mode Pemeriksaan
                        </span>
                        {{-- Info Tooltip --}}
                        <div class="relative">
                            <button type="button" @click="showGuide = !showGuide" @click.outside="showGuide = false"
                                    class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black transition-all cursor-pointer"
                                    :style="showGuide ? 'background: var(--color-primary); color: white;' : 'background: var(--color-primary-50); color: var(--color-primary); border: 1.5px solid var(--color-primary-200);'"
                                    title="Panduan Verifikasi" aria-label="Panduan Verifikasi">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </button>
                            <div x-show="showGuide" x-transition
                                 class="absolute right-0 top-full mt-2 w-80 p-4 rounded-xl shadow-xl z-50 text-xs leading-relaxed"
                                 style="background: var(--color-white); border: 1px solid var(--color-neutral-300); color: var(--color-neutral-700);">
                                <div class="font-extrabold text-sm mb-2 flex items-center gap-1.5" style="color: var(--color-neutral-900);">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                    <span>Panduan Verifikasi</span>
                                </div>
                                <p>Buka dan telaah setiap dokumen pada tabel di bawah menggunakan tombol <strong>Pratinjau</strong>. Lakukan pengecekan tanda tangan, kuitansi, dan nominal pagu. Berikan centang pada <strong>Panel Verifikasi Berkas</strong> di sebelah kanan untuk setiap dokumen yang sah.</p>
                                <div class="mt-3 p-2 rounded-lg text-[11px]" style="background: var(--color-primary-50); color: var(--color-primary-900);">
                                    Dokumen diverifikasi satu per satu. Status pencairan aktif setelah seluruh berkas tercentang 100%.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                    <table class="sakdi-table w-full text-xs [&_th]:!px-3 [&_th]:!py-3 [&_td]:!px-3 [&_td]:!py-3">
                        <thead>
                            <tr>
                                <th class="w-8 text-center !px-2">#</th>
                                <th class="!px-3">Nama File Dokumen</th>
                                <th class="!px-2.5">Label Berkas</th>
                                <th class="!px-2 text-right">Ukuran</th>
                                <th class="!px-2.5">Pengunggah</th>
                                <th class="text-center !px-2.5">Status Verifikasi</th>
                                <th class="text-center w-28 !px-2">Aksi</th>
                            </tr>
                                             @forelse($item->documents as $i => $doc)
                            <tr>
                                <td class="text-center text-xs num-mono !px-2" style="color: var(--color-neutral-500);">{{ $i + 1 }}</td>
                                <td class="!px-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $doc->isPdf() ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-emerald-50 text-emerald-600 border border-emerald-200' }}">
                                            @if($doc->isPdf())
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-xs truncate max-w-[170px] xl:max-w-xs" style="color: var(--color-neutral-900);">{{ $doc->file_name }}</div>
                                            <div class="text-[10px] num-mono uppercase mt-0.5" style="color: var(--color-neutral-500);">
                                                {{ $doc->file_type }} • {{ $doc->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="!px-2.5 whitespace-nowrap">
                                    @if($doc->label)
                                        <span class="sakdi-badge sakdi-badge-primary text-[11px] px-2 py-0.5 inline-flex items-center gap-1">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                            <span>{{ $doc->label }}</span>
                                        </span>
                                    @else
                                        <span class="text-xs" style="color: var(--color-neutral-400);">-</span>
                                    @endif
                                </td>
                                <td class="text-xs num-mono whitespace-nowrap text-right !px-2" style="color: var(--color-neutral-700);">{{ $doc->file_size_formatted }}</td>
                                <td class="text-xs whitespace-nowrap !px-2.5" style="color: var(--color-neutral-700);">{{ $doc->uploadedBy->name }}</td>
                                <td class="text-center whitespace-nowrap !px-2.5">
                                    @if($doc->is_checked)
                                        <span class="sakdi-badge sakdi-badge-success text-xs font-bold inline-flex items-center gap-1" title="Dicentang oleh {{ $doc->checkedBy->name ?? 'Bendahara' }} pada {{ $doc->checked_at ? $doc->checked_at->format('d/m/Y H:i') : '' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            <span>Lolos</span>
                                        </span>
                                    @elseif($item->verification_status === 'REJECTED')
                                        @if($item->rejection_note)
                                            <button type="button"
                                                    @click="$dispatch('open-rejection-info', {
                                                        fileName: '{{ addslashes($doc->file_name) }}',
                                                        label: '{{ addslashes($doc->label ?? '') }}',
                                                        note: '{{ addslashes($item->rejection_note) }}',
                                                        user: '{{ addslashes($doc->uploadedBy->name ?? 'Operator') }}',
                                                        size: '{{ $doc->file_size_formatted }}'
                                                    })"
                                                    class="sakdi-badge sakdi-badge-error text-xs font-bold inline-flex items-center gap-1.5 cursor-pointer hover:bg-red-200 transition-all hover:scale-105 active:scale-95 shadow-xs"
                                                    style="border: 1px solid #F87171;"
                                                    title="Catatan: &quot;{{ $item->rejection_note }}&quot; (Klik untuk lihat catatan revisi)"
                                                    aria-label="Lihat catatan revisi untuk {{ $doc->file_name }}">
                                                <svg class="w-3 h-3 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                <span>Perlu Revisi</span>
                                                <span class="w-3.5 h-3.5 rounded-full bg-red-600 text-white inline-flex items-center justify-center text-[9px] font-black italic shadow-xs">i</span>
                                            </button>
                                        @else
                                            <span class="sakdi-badge sakdi-badge-error text-xs font-bold inline-flex items-center gap-1" title="Dokumen ini belum lolos verifikasi atau memerlukan revisi">
                                                <svg class="w-3 h-3 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                <span>Perlu Revisi</span>
                                            </span>
                                        @endif
                                    @else
                                        <span class="sakdi-badge sakdi-badge-warning text-xs font-semibold inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>Belum Dicek</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center whitespace-nowrap w-28 !px-2">
                                    <div class="flex items-center justify-center gap-1.5" x-data>
                                        {{-- Stream Inline Preview Modal Button --}}
                                        <button type="button"
                                                @click="$dispatch('open-preview-modal', { url: '{{ route('documents.stream', $doc) }}', title: '{{ addslashes($doc->file_name) }}', type: '{{ $doc->file_type }}' })"
                                                class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm p-1.5 h-7 w-7 inline-flex items-center justify-center rounded-lg cursor-pointer"
                                                title="Pratinjau Dokumen"
                                                aria-label="Pratinjau Dokumen {{ $doc->file_name }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>

                                        {{-- Download Button --}}
                                        <a href="{{ route('documents.download', $doc) }}"
                                           class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm p-1.5 h-7 w-7 inline-flex items-center justify-center rounded-lg"
                                           title="Unduh Dokumen"
                                           aria-label="Unduh Dokumen {{ $doc->file_name }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        </a>

                                        {{-- Delete Button (Guard 4 Ownership) --}}
                                        @if($item->verification_status !== 'APPROVED' && auth()->user()->canUpload() && (auth()->user()->isAdmin() || auth()->user()->isSupervisor() || $doc->uploaded_by_user_id === auth()->id()))
                                        <form action="{{ route('documents.destroy', $doc) }}" method="POST"
                                              onsubmit="return confirm('Hapus dokumen ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="sakdi-btn sakdi-btn-danger sakdi-btn-sm p-1.5 h-7 w-7 inline-flex items-center justify-center rounded-lg cursor-pointer"
                                                    title="Hapus Dokumen"
                                                    aria-label="Hapus Dokumen {{ $doc->file_name }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-14" style="color: var(--color-neutral-500);">
                                    <div class="w-12 h-12 mx-auto mb-3 rounded-2xl flex items-center justify-center" style="background: var(--color-neutral-100); color: var(--color-neutral-400);">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="font-extrabold text-sm" style="color: var(--color-neutral-700);">Belum ada dokumen yang diunggah</div>
                                    <div class="text-xs mt-1" style="color: var(--color-neutral-500);">Unggah dokumen SPJ, BAPP, atau Kuitansi pada formulir di atas.</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: BENDAHARA ACTION CONTROL PANEL --}}
        <div class="space-y-6 xl:col-span-4 xl:sticky xl:top-24">

            {{-- Container Panel Verifikasi Bendahara --}}
            @if(auth()->user()->role === 'BENDAHARA' || auth()->user()->role === 'ADMIN')
                <div x-data="{
                    checkedDocs: {{ json_encode($item->documents->pluck('is_checked', 'id')->map(fn($v) => (bool)$v)) }},
                    docsInfo: {{ json_encode($item->documents->map(fn($d) => ['id' => (string)$d->id, 'file_name' => $d->file_name, 'label' => $d->label ?? 'Dokumen'])) }},
                    totalDocs: {{ $item->documents->count() }},
                    rejectionNote: '',
                    get checkedCount() {
                        return Object.values(this.checkedDocs).filter(Boolean).length;
                    },
                    get canApprove() {
                        return this.totalDocs > 0 && this.checkedCount === this.totalDocs;
                    },
                    get uncheckedDocs() {
                        return this.docsInfo.filter(d => !this.checkedDocs[d.id]);
                    },
                    fillUncheckedNote() {
                        if (this.uncheckedDocs.length > 0) {
                            const names = this.uncheckedDocs.map(d => d.file_name + ' (' + d.label + ')').join(', ');
                            this.rejectionNote = 'Berkas berikut belum sesuai / belum lengkap: ' + names + '. Mohon diperbaiki dan diunggah ulang.';
                        }
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
                            <span>Item ini telah disetujui oleh Bendahara. Status terkunci &amp; siap cair.</span>
                        </div>
                    @else
                        <!-- Box Ceklis Dokumen -->
                        <div class="p-4 rounded-xl border space-y-3"
                             style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
                            <div class="flex items-center justify-between text-xs font-bold mb-1"
                                 style="color: var(--color-neutral-700);">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    <span>Ceklis Verifikasi Berkas</span>
                                </span>
                                <span :class="canApprove ? 'sakdi-badge sakdi-badge-success' : 'sakdi-badge sakdi-badge-warning'"
                                      x-text="checkedCount + ' / ' + totalDocs + ' Dokumen Terverifikasi'"></span>
                            </div>

                            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                @forelse($item->documents as $doc)
                                    <label class="flex items-center gap-2 p-2 rounded-lg border cursor-pointer text-xs transition-colors hover:border-blue-400"
                                           style="background: var(--color-white); border-color: var(--color-neutral-300);">
                                        <input
                                            type="checkbox"
                                            :checked="checkedDocs['{{ $doc->id }}']"
                                            @change="toggleDocCheck('{{ $doc->id }}', $event)"
                                            @if($item->verification_status === 'APPROVED') disabled @endif
                                            class="rounded border-slate-300 w-4 h-4 disabled:opacity-75 disabled:cursor-not-allowed cursor-pointer"
                                            style="accent-color: var(--color-primary);"
                                        >
                                        <span class="font-bold truncate flex-1" style="color: var(--color-neutral-900);">{{ $doc->file_name }}</span>
                                        <span class="sakdi-badge sakdi-badge-neutral text-[10px] mr-1">{{ $doc->label ?? 'Dokumen' }}</span>
                                        @if($item->verification_status === 'REJECTED' && !$doc->is_checked && $item->rejection_note)
                                            <button type="button"
                                                    @click.stop="$dispatch('open-rejection-info', {
                                                        fileName: '{{ addslashes($doc->file_name) }}',
                                                        label: '{{ addslashes($doc->label ?? '') }}',
                                                        note: '{{ addslashes($item->rejection_note) }}',
                                                        user: '{{ addslashes($doc->uploadedBy->name ?? 'Operator') }}',
                                                        size: '{{ $doc->file_size_formatted }}'
                                                    })"
                                                    class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold transition-all hover:scale-105 active:scale-95 shadow-xs cursor-pointer mr-1"
                                                    style="background: #FEF2F2; color: #DC2626; border: 1px solid #FCA5A5;"
                                                    title="Catatan Penolakan: &quot;{{ $item->rejection_note }}&quot; (Klik untuk rincian)"
                                                    aria-label="Catatan Penolakan">
                                                <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>Catatan</span>
                                            </button>
                                        @endif
                                        <button type="button"
                                                @click.stop="$dispatch('open-preview-modal', { url: '{{ route('documents.stream', $doc) }}', title: '{{ addslashes($doc->file_name) }}', type: '{{ $doc->file_type }}' })"
                                                class="p-1 hover:text-blue-600 transition-colors" style="color: var(--color-primary);" title="Pratinjau Dokumen" aria-label="Pratinjau Dokumen">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                    </label>
                                @empty
                                    <p class="text-xs italic p-2 text-center" style="color: var(--color-neutral-500);">Belum ada dokumen terunggah.</p>
                                @endforelse
                            </div>

                            {{-- Petunjuk Alur Verifikasi --}}
                            <div class="p-2.5 rounded-lg text-xs leading-relaxed"
                                 :style="canApprove ? 'background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46;' : 'background: #EFF6FF; border: 1px solid #BFDBFE; color: #1E40AF;'">
                                <template x-if="canApprove">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span><strong>Semua berkas sah (100%):</strong> Anda dapat menekan tombol <strong>Setujui Pencairan</strong> di bawah.</span>
                                    </div>
                                </template>
                                <template x-if="!canApprove">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Periksa dokumen satu per satu. Jika ada dokumen yang <strong>salah / ditolak</strong>, biarkan tidak dicentang lalu klik <strong>Tolak / Minta Revisi</strong>.</span>
                                    </div>
                                </template>
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
                                :class="canApprove ? 'sakdi-btn sakdi-btn-success w-full font-bold' : 'sakdi-btn sakdi-btn-secondary w-full opacity-60 cursor-not-allowed'"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Setujui Pencairan (Approved)</span>
                            </button>
                        </form>

                        <!-- Tombol Tolak / Minta Revisi -->
                        <button
                            type="button"
                            @click="showRejectModal = true"
                            class="sakdi-btn sakdi-btn-danger w-full font-bold"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Tolak / Minta Revisi Berkas</span>
                        </button>

                        {{-- Rejection Modal (Teleported to Body) --}}
                        <template x-teleport="body">
                            <div x-show="showRejectModal"
                                 @keydown.escape.window="showRejectModal = false"
                                 class="fixed inset-0 flex items-center justify-center p-3 sm:p-6"
                                 style="display:none; position: fixed; inset: 0; z-index: 99999;"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 x-cloak>
                                {{-- Dark Backdrop --}}
                                <div class="fixed inset-0"
                                     style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.72); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); z-index: 99999;"
                                     @click="showRejectModal = false"></div>

                                {{-- Dialog Box --}}
                                <div class="relative flex flex-col overflow-hidden"
                                     style="z-index: 100000; width: 100%; max-width: 560px; max-height: 88vh; background: #ffffff; border-radius: 20px; box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(0,0,0,0.06);"
                                     @click.stop>

                                    {{-- Modal Header --}}
                                    <div class="px-6 py-4 shrink-0 flex items-center justify-between"
                                         style="background: linear-gradient(135deg, #7F1D1D 0%, #DC2626 100%);">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                                                 style="background: rgba(255,255,255,0.2);">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            </div>
                                            <div class="min-w-0">
                                                <h3 class="text-base font-extrabold text-white truncate">Tolak &amp; Minta Revisi Berkas</h3>
                                                <p class="text-xs" style="color: rgba(255,255,255,0.85);">Beri instruksi revisi yang spesifik untuk Operator</p>
                                            </div>
                                        </div>
                                        <button type="button" @click="showRejectModal = false"
                                                class="p-2 rounded-lg transition-colors cursor-pointer"
                                                style="color: rgba(255,255,255,0.8); background: rgba(255,255,255,0.15);"
                                                onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                                                onmouseout="this.style.background='rgba(255,255,255,0.15)'"
                                                title="Tutup (Esc)" aria-label="Tutup">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>

                                    {{-- Modal Body --}}
                                    <div class="p-6 space-y-4 overflow-y-auto flex-1">
                                        {{-- Daftar Berkas yang Belum Dicentang --}}
                                        <template x-if="uncheckedDocs.length > 0">
                                            <div class="p-3.5 rounded-xl border" style="background: #FEF2F2; border-color: #FECACA;">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-xs font-bold flex items-center gap-1.5" style="color: #991B1B;">
                                                        <svg class="w-3.5 h-3.5 text-red-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        <span>Berkas Belum Tercentang (<span x-text="uncheckedDocs.length"></span> berkas):</span>
                                                    </span>
                                                    <button type="button" @click="fillUncheckedNote()"
                                                            class="text-[11px] font-bold px-2 py-0.5 rounded transition-colors inline-flex items-center gap-1 cursor-pointer"
                                                            style="background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5;">
                                                        <svg class="w-3 h-3 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                        <span>Isi Otomatis ke Catatan</span>
                                                    </button>
                                                </div>
                                                <ul class="text-xs space-y-1" style="color: #7F1D1D;">
                                                    <template x-for="doc in uncheckedDocs" :key="doc.id">
                                                        <li class="flex items-center gap-1.5 font-medium">
                                                            <svg class="w-3 h-3 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                            <span class="font-bold truncate" x-text="doc.file_name"></span>
                                                            <span class="text-[10px] opacity-75" x-text="'(' + doc.label + ')'"></span>
                                                        </li>
                                                    </template>
                                                </ul>
                                            </div>
                                        </template>

                                        <form action="{{ route('items.verify', $item) }}" method="POST" class="space-y-4">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="REJECTED">

                                            <div>
                                                <label class="sakdi-label sakdi-label-required font-bold">Catatan Penolakan / Revisi</label>
                                                <textarea name="rejection_note" required rows="4" x-model="rejectionNote"
                                                          placeholder="Contoh: Lampiran Kuitansi honor belum ditandatangani, nominal berbeda dengan pagu..."
                                                          class="sakdi-input w-full text-xs" style="min-height: 110px;"></textarea>
                                                <p class="text-[11px] mt-1" style="color: var(--color-neutral-500);">
                                                    Catatan ini akan langsung tampil di dashboard &amp; halaman detail Operator agar dapat segera diperbaiki.
                                                </p>
                                            </div>

                                            <div class="flex items-center justify-end gap-3 pt-3 border-t" style="border-color: var(--color-neutral-200);">
                                                <button type="button" @click="showRejectModal = false" class="sakdi-btn sakdi-btn-secondary cursor-pointer">
                                                    Batal
                                                </button>
                                                <button type="submit" class="sakdi-btn sakdi-btn-danger font-bold inline-flex items-center gap-1.5 cursor-pointer">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    <span>Kirim Penolakan</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </template>
                    @endif
                </div>
            @else
                {{-- Panel Status & Ceklis Berkas (Tampilan Operator & Supervisor) --}}
                <div class="sakdi-card p-6 space-y-4" x-data="{ showInfoTip: false }">
                    <div class="flex items-center justify-between">
                        <h3 class="font-extrabold text-sm flex items-center gap-2" style="color: var(--color-neutral-900);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-primary);" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span>Status &amp; Verifikasi Berkas SPJ</span>
                        </h3>
                        <div class="relative">
                            <button type="button" @click="showInfoTip = !showInfoTip" @click.outside="showInfoTip = false"
                                    class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black cursor-pointer transition-all"
                                    style="background: var(--color-primary-50); color: var(--color-primary); border: 1.5px solid var(--color-primary-200);"
                                    title="Info Verifikasi" aria-label="Info Verifikasi">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </button>
                            <div x-show="showInfoTip" x-transition
                                 class="absolute right-0 top-full mt-2 w-64 p-3 rounded-xl shadow-xl z-50 text-[11px] leading-relaxed"
                                 style="background: var(--color-white); border: 1px solid var(--color-neutral-300); color: var(--color-neutral-700);">
                                <strong>Informasi Verifikasi:</strong> Dokumen diverifikasi satu per satu oleh Bendahara Pengeluaran. Status pencairan akan aktif setelah seluruh berkas tercentang lengkap (100%).
                            </div>
                        </div>
                    </div>

                    <!-- Status Item Saat Ini -->
                    @if($item->verification_status === 'APPROVED')
                        <div class="sakdi-alert sakdi-alert-success text-xs font-semibold">
                            <svg class="sakdi-alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Item telah disetujui oleh Bendahara. Status siap cair &amp; berkas terkunci.</span>
                        </div>
                    @elseif($item->verification_status === 'REJECTED')
                        <div class="sakdi-alert sakdi-alert-error text-xs font-semibold">
                            <svg class="sakdi-alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Item ditolak oleh Bendahara. Silakan periksa catatan revisi di atas dan unggah perbaikan berkas.</span>
                        </div>
                    @else
                        <div class="sakdi-alert sakdi-alert-warning text-xs font-semibold">
                            <svg class="sakdi-alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Berkas sedang dalam antrean verifikasi Bendahara Pengeluaran.</span>
                        </div>
                    @endif

                    <!-- Box Ceklis Dokumen (Read Only untuk Operator & Supervisor) -->
                    @php
                        $checkedDocsCount = $item->documents->where('is_checked', true)->count();
                        $totalDocsCount = $item->documents->count();
                    @endphp
                    <div class="p-4 rounded-xl border space-y-3"
                         style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
                        <div class="flex items-center justify-between text-xs font-bold mb-1"
                             style="color: var(--color-neutral-700);">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                <span>Ceklis Status Verifikasi</span>
                            </span>
                            <span class="{{ $totalDocsCount > 0 && $checkedDocsCount === $totalDocsCount ? 'sakdi-badge sakdi-badge-success' : 'sakdi-badge sakdi-badge-warning' }}">
                                {{ $checkedDocsCount }} / {{ $totalDocsCount }} Terverifikasi
                            </span>
                        </div>

                        <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                            @forelse($item->documents as $doc)
                                <div class="flex items-center gap-2 p-2 rounded-lg border text-xs"
                                     style="background: var(--color-white); border-color: var(--color-neutral-300);">
                                    <span class="shrink-0">
                                        @if($doc->is_checked)
                                            <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center" title="Terverifikasi Bendahara">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </span>
                                        @else
                                            <span class="w-4 h-4 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center" title="Menunggu Ceklis Bendahara">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </span>
                                        @endif
                                    </span>
                                    <span class="font-bold truncate flex-1" style="color: var(--color-neutral-900);">{{ $doc->file_name }}</span>
                                    <span class="sakdi-badge sakdi-badge-neutral text-[10px] mr-1">{{ $doc->label ?? 'Dokumen' }}</span>
                                    <button type="button"
                                            @click.stop="$dispatch('open-preview-modal', { url: '{{ route('documents.stream', $doc) }}', title: '{{ addslashes($doc->file_name) }}', type: '{{ $doc->file_type }}' })"
                                            class="p-1 hover:text-blue-600 transition-colors" style="color: var(--color-primary);" title="Pratinjau Dokumen" aria-label="Pratinjau Dokumen">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            @empty
                                <p class="text-xs italic p-2 text-center" style="color: var(--color-neutral-500);">Belum ada dokumen terunggah.</p>
                            @endforelse
                        </div>
                    </div>


                </div>
            @endif

        </div>

    </div>

</div>

{{-- ── INLINE DOCUMENT STREAM PREVIEW MODAL (TELEPORTED) ── --}}
<template x-teleport="body">
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
        @keydown.escape.window="open = false"
        class="fixed inset-0 flex items-center justify-center p-3 sm:p-6"
        style="display:none; position: fixed; inset: 0; z-index: 99999;"
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak>

        {{-- Full Dark Glass Backdrop --}}
        <div class="fixed inset-0"
             style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 99999;"
             @click="open = false"></div>

        <div class="relative flex flex-col overflow-hidden"
             style="z-index: 100000; width: 95%; max-width: 1040px; height: 90vh; background: #ffffff; border-radius: 20px; box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(0,0,0,0.08);"
             @click.stop>

            {{-- Header Bar --}}
            <div class="px-5 py-3.5 flex items-center justify-between shrink-0"
                 style="background: linear-gradient(135deg, #002D5C 0%, #0057A8 100%); border-bottom: 1px solid rgba(255,255,255,0.12);">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                         style="background: rgba(255,255,255,0.15);">
                        <template x-if="type === 'pdf'">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </template>
                        <template x-if="type !== 'pdf'">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </template>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-extrabold truncate text-white" x-text="title"></h3>
                        <p class="text-[10px] num-mono uppercase" style="color: rgba(255,255,255,0.7);" x-text="'Pratinjau Langsung Dokumen • ' + type.toUpperCase()"></p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a :href="url" target="_blank"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors"
                       style="color: #ffffff; background: rgba(255,255,255,0.15);"
                       onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                       onmouseout="this.style.background='rgba(255,255,255,0.15)'"
                       title="Buka di Tab Baru">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        <span class="hidden sm:inline">Tab Baru</span>
                    </a>
                    <button type="button" @click="open = false"
                            class="p-2 rounded-lg transition-colors"
                            style="color: rgba(255,255,255,0.8); background: rgba(255,255,255,0.15);"
                            onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.15)'"
                            title="Tutup (Esc)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            {{-- Content Area --}}
            <div class="flex-1 relative overflow-hidden" style="background: #0f172a;">
                <template x-if="type === 'pdf'">
                    <iframe :src="url" class="w-full h-full border-none" style="background: white;"></iframe>
                </template>
                <template x-if="type !== 'pdf'">
                    <div class="w-full h-full flex items-center justify-center p-6 overflow-auto"
                         style="background: repeating-conic-gradient(#cbd5e1 0% 25%, #f1f5f9 0% 50%) 50% / 20px 20px;">
                        <img :src="url" :alt="title" class="max-w-full max-h-full object-contain"
                             style="border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

{{-- ── MODAL INFORMASI PENOLAKAN DOKUMEN (TELEPORTED) ── --}}
<template x-teleport="body">
    <div x-data="{ open: false, fileName: '', label: '', note: '', user: '', size: '' }"
         @open-rejection-info.window="open = true; fileName = $event.detail.fileName; label = $event.detail.label; note = $event.detail.note; user = $event.detail.user; size = $event.detail.size;"
         @keydown.escape.window="open = false"
         x-show="open"
         x-cloak
         class="fixed inset-0 flex items-center justify-center p-4"
         style="display:none; position: fixed; inset: 0; z-index: 99999;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         role="dialog"
         aria-modal="true"
         aria-labelledby="rejection-info-modal-title">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
             @click="open = false"></div>

        <!-- Modal Dialog Box -->
        <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-lg border border-red-200 z-10"
             @click.outside="open = false">
            <!-- Modal Header -->
            <div class="px-6 py-4 flex items-center justify-between"
                 style="background: linear-gradient(135deg, #991B1B 0%, #DC2626 100%);">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center shadow-inner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 id="rejection-info-modal-title" class="text-base font-extrabold text-white">
                            Catatan Penolakan Dokumen
                        </h3>
                        <p class="text-xs text-red-100 font-medium">
                            Dokumen perlu diperbaiki / diunggah ulang
                        </p>
                    </div>
                </div>
                <button type="button" @click="open = false"
                        class="text-white/80 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors cursor-pointer"
                        title="Tutup (Esc)" aria-label="Tutup">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-4">
                <!-- Info Berkas -->
                <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50 flex items-start gap-3">
                    <div class="w-9 h-9 rounded-lg bg-red-100 text-red-700 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nama Berkas Dokumen</div>
                        <div class="text-sm font-extrabold text-slate-800 break-words" x-text="fileName"></div>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="sakdi-badge sakdi-badge-primary text-[10px] inline-flex items-center gap-1" x-show="label">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                <span x-text="label"></span>
                            </span>
                            <span class="sakdi-badge sakdi-badge-error text-[10px] font-bold inline-flex items-center gap-1">
                                <svg class="w-2.5 h-2.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>Perlu Revisi</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Alasan Penolakan dari Bendahara -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-red-700 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-red-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Alasan / Catatan Penolakan Bendahara:</span>
                    </label>
                    <div class="p-4 rounded-xl border-2 border-red-200 bg-red-50/70 text-slate-900 text-sm font-semibold leading-relaxed shadow-sm">
                        <p class="whitespace-pre-wrap" x-text="note"></p>
                    </div>
                </div>

                <!-- Info Petunjuk Revisi -->
                <div class="p-3 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    <span>Silakan perbaiki atau unggah ulang dokumen yang sesuai dengan catatan di atas melalui panel <strong>Unggah Dokumen SPJ Baru</strong>.</span>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex justify-end">
                <button type="button" @click="open = false"
                        class="sakdi-btn sakdi-btn-secondary px-5 py-2 text-xs font-bold rounded-lg shadow-sm hover:bg-slate-200">
                    Mengerti &amp; Tutup
                </button>
            </div>
        </div>
    </div>
</template>

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
        formatSize(bytes) {
            if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
            return (bytes / 1024).toFixed(2) + ' KB';
        }
    };
}
</script>
@endsection
