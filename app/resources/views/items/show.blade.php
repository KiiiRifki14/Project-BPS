@extends('layouts.app')
@section('title', "Item {$item->code}")

@section('content')
<div class="space-y-8">

    {{-- ── BREADCRUMB TRAIL & BACK TO VERIFICATION ── --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <nav class="flex items-center gap-2 flex-wrap text-xs font-semibold text-slate-500 bg-white px-5 py-3 rounded-xl border border-slate-200 shadow-xs flex-1">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-800 transition-colors">🏠 Dashboard</a>
            <span class="text-slate-300">/</span>
            <span class="font-mono text-slate-700">[{{ $breadcrumb['program']->code }}]</span>
            <span class="text-slate-300">/</span>
            <span class="font-mono text-slate-700">[{{ $breadcrumb['output']->code }}]</span>
            <span class="text-slate-300">/</span>
            <span class="font-mono text-slate-700">[{{ $breadcrumb['sub_output']->code }}]</span>
            <span class="text-slate-300">/</span>
            <span class="font-mono text-slate-700">[{{ $breadcrumb['component']->code }}]</span>
            <span class="text-slate-300">/</span>
            <span class="font-mono text-slate-700">[{{ $breadcrumb['sub_component']->code }}]</span>
            <span class="text-slate-300">/</span>
            <span class="font-mono text-slate-700">[{{ $breadcrumb['account']->code }}]</span>
            <span class="text-slate-300">/</span>
            <span class="font-mono font-bold text-blue-900 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">Item {{ $item->code }}</span>
        </nav>

        @if(request()->query('from') === 'verification' || auth()->user()->isBendahara())
            <a href="{{ route('verification.index') }}" class="btn-bps btn-bps-secondary btn-bps-sm font-extrabold text-blue-900 bg-blue-50 border-blue-200 hover:bg-blue-100 shadow-xs">
                ← Kembali ke Inbox Verifikasi
            </a>
        @endif
    </div>

    {{-- ── ITEM HEADER CARD ── --}}
    <div class="card-corporate p-8 relative overflow-hidden">
        <div class="flex items-start justify-between flex-wrap gap-6">
            <div class="flex-1 min-w-[280px]">
                <div class="flex items-center gap-3 flex-wrap mb-3">
                    <span class="font-mono text-xs font-extrabold text-blue-900 bg-blue-50 border border-blue-200 px-3.5 py-1 rounded-lg">
                        KODE ITEM: {{ $item->code }}
                    </span>

                    @if($item->verification_status === 'APPROVED')
                        <span class="badge-corp badge-corp-approved text-xs py-1 px-3">
                            <span>Siap Cair (Approved)</span>
                        </span>
                    @elseif($item->verification_status === 'REJECTED')
                        <span class="badge-corp badge-corp-rejected text-xs py-1 px-3">
                            <span>Ditolak — Butuh Revisi</span>
                        </span>
                    @else
                        <span class="badge-corp badge-corp-pending text-xs py-1 px-3">
                            <span>Menunggu Verifikasi</span>
                        </span>
                    @endif
                </div>

                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mb-2">{{ $item->name }}</h1>

                <div class="text-xs text-slate-600 space-y-1">
                    <div>📂 Akun: <strong class="font-mono text-slate-800">{{ $item->account->code }}</strong> — {{ $item->account->name }}</div>
                    <div>🗂 Sub-Komponen: <strong class="font-mono text-slate-800">{{ $breadcrumb['sub_component']->code }}</strong> — {{ $breadcrumb['sub_component']->name }}</div>
                </div>
            </div>

            <div class="text-right">
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pagu Anggaran</div>
                <div class="text-2xl font-black text-emerald-800 font-mono tracking-tight">{{ $item->pagu_formatted }}</div>
                <div class="text-xs font-semibold text-slate-500 mt-1">{{ $item->documents->count() }} dokumen terunggah</div>
            </div>
        </div>

        {{-- Rejection Note Alert --}}
        @if($item->verification_status === 'REJECTED' && $item->rejection_note)
        <div class="mt-6 p-4 rounded-xl bg-red-50 border-l-4 border-l-red-600 border border-red-200">
            <div class="flex items-center gap-2 text-xs font-black text-red-900 uppercase tracking-wider mb-1">
                <span>📝 Catatan Penolakan Bendahara:</span>
            </div>
            <div class="text-sm font-semibold text-red-800 leading-relaxed">{{ $item->rejection_note }}</div>
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
                                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                                    <span x-text="fileIcon(file.name)" class="text-xl"></span>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs font-bold text-slate-800 truncate" x-text="file.name"></div>
                                        <div class="text-[10px] text-slate-500 font-mono" x-text="formatSize(file.size)"></div>
                                    </div>
                                    <input type="text" :name="'labels[' + index + ']'"
                                           placeholder="Label (misal: BAPP / Kuitansi)"
                                           class="form-input-v4 text-xs py-1.5 w-44">
                                    <button type="button" @click="removeFile(index)"
                                            class="text-red-600 hover:text-red-800 font-bold text-lg px-2">×</button>
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
                                            @click="openPreview('{{ route('documents.stream', $doc) }}', '{{ addslashes($doc->file_name) }}', '{{ $doc->file_type }}')"
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

            {{-- Verification Card --}}
            <div class="card-corporate p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 border border-amber-200 text-amber-800 flex items-center justify-center font-bold text-lg">
                        🏦
                    </div>
                    <div>
                        <h2 class="text-sm font-extrabold text-slate-900">Panel Verifikasi Bendahara</h2>
                        <span class="text-xs text-slate-500">Khusus Bendahara & Admin</span>
                    </div>
                </div>

                {{-- Status Display Pill --}}
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 mb-5 text-center">
                    <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-2">STATUS VERIFIKASI SAAT INI</div>
                    @if($item->verification_status === 'APPROVED')
                        <span class="badge-corp badge-corp-approved text-sm py-1.5 px-4">
                            <span>✅ Siap Cair (Approved)</span>
                        </span>
                    @elseif($item->verification_status === 'REJECTED')
                        <span class="badge-corp badge-corp-rejected text-sm py-1.5 px-4">
                            <span>❌ Ditolak (Revisi)</span>
                        </span>
                    @else
                        <span class="badge-corp badge-corp-pending text-sm py-1.5 px-4">
                            <span>⏳ Menunggu Verifikasi</span>
                        </span>
                    @endif
                </div>

                @if(auth()->user()->canVerify())
                <div x-data="{ showRejectForm: false }" class="space-y-3">
                    {{-- Approve Action --}}
                    <form action="{{ route('items.verify', $item) }}" method="POST"
                          onsubmit="return confirm('Setujui pencairan item {{ $item->code }}?\nPastikan dokumen SPJ telah diperiksa.')">
                        @csrf @method('PATCH')
                        <input type="hidden" name="action" value="APPROVED">
                        <button type="submit" class="btn-bps btn-bps-success w-full py-3 text-sm">
                            <span>✅ Setujui Pencairan (Approved)</span>
                        </button>
                    </form>

                    {{-- Reject Action Toggle --}}
                    <button type="button" class="btn-bps btn-bps-danger w-full py-3 text-sm"
                            @click="showRejectForm = !showRejectForm">
                        <span>❌ Tolak / Minta Revisi</span>
                    </button>

                    {{-- Rejection Form --}}
                    <div x-show="showRejectForm" x-transition class="p-4 rounded-xl bg-red-50 border border-red-200">
                        <form action="{{ route('items.verify', $item) }}" method="POST" class="space-y-3">
                            @csrf @method('PATCH')
                            <input type="hidden" name="action" value="REJECTED">
                            <label class="form-label-custom text-red-900">Catatan Penolakan *</label>
                            <textarea name="rejection_note" rows="3" class="form-input-v4 text-xs" required
                                      placeholder="Contoh: BAPP belum ditandatangani PPK, Kuitansi kurang legalisir..."></textarea>
                            <button type="submit" class="btn-bps btn-bps-danger w-full py-2.5 text-xs">
                                Kirim Penolakan Ke Operator
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="p-4 rounded-xl bg-slate-100 text-center text-xs font-semibold text-slate-500">
                    🔒 Hanya Bendahara atau Admin yang dapat melakukan verifikasi pencairan.
                </div>
                @endif
            </div>

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
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" style="display:none;">

    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="close()"></div>

    {{-- Modal Box --}}
    <div class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden z-10 border border-slate-700 flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 bg-[#001F54] text-white flex items-center justify-between border-b border-slate-700">
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
            this.fileType = type.toLowerCase();
            this.open = true;
        },

        close() {
            this.open = false;
            this.fileUrl = '';
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
