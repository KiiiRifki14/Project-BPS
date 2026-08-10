@extends('layouts.app')
@section('title', "Item {$item->code}")
@section('page-title', "Detail Item [{$item->code}]")

@section('content')
{{-- ── BREADCRUMB ──────────────────────────────────────── --}}
<div class="breadcrumb">
    <a href="{{ route('dashboard') }}" style="color:#64748b;text-decoration:none;">🏠 Dashboard</a>
    <span class="breadcrumb-sep">/</span>
    <span>[{{ $breadcrumb['program']->code }}] {{ Str::limit($breadcrumb['program']->name, 20) }}</span>
    <span class="breadcrumb-sep">/</span>
    <span>[{{ $breadcrumb['output']->code }}]</span>
    <span class="breadcrumb-sep">/</span>
    <span>[{{ $breadcrumb['sub_output']->code }}]</span>
    <span class="breadcrumb-sep">/</span>
    <span>[{{ $breadcrumb['component']->code }}]</span>
    <span class="breadcrumb-sep">/</span>
    <span>[{{ $breadcrumb['sub_component']->code }}]</span>
    <span class="breadcrumb-sep">/</span>
    <span>[{{ $breadcrumb['account']->code }}]</span>
    <span class="breadcrumb-sep">/</span>
    <span class="current">({{ $item->code }})</span>
</div>

{{-- ── ITEM HEADER CARD ────────────────────────────────── --}}
<div style="background:#fff;border-radius:14px;padding:22px 24px;border:1px solid #e2e8f0;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,.04);">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div style="flex:1;min-width:260px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <code style="background:#e8f0fe;color:#003087;padding:4px 10px;border-radius:6px;font-size:13px;font-weight:700;">
                    {{ $item->code }}
                </code>
                <span class="badge {{ $item->status_badge_class }}" style="font-size:12px;padding:4px 12px;">
                    @if($item->verification_status === 'APPROVED') ✅ Siap Cair
                    @elseif($item->verification_status === 'REJECTED') ❌ Ditolak — Butuh Revisi
                    @else ⏳ Menunggu Verifikasi
                    @endif
                </span>
            </div>
            <h1 style="font-size:17px;font-weight:700;color:#0f172a;margin:0 0 6px;">{{ $item->name }}</h1>
            <div style="font-size:12px;color:#64748b;line-height:1.6;">
                <span style="margin-right:12px;">📂 Akun: <strong>{{ $item->account->code }}</strong> — {{ $item->account->name }}</span><br>
                <span>🗂 Sub-Komponen: <strong>{{ $breadcrumb['sub_component']->code }}</strong> — {{ $breadcrumb['sub_component']->name }}</span>
            </div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:11px;color:#94a3b8;margin-bottom:4px;">Pagu Anggaran</div>
            <span class="pagu-badge" style="font-size:16px;">{{ $item->pagu_formatted }}</span>
            <div style="font-size:11px;color:#94a3b8;margin-top:6px;">{{ $item->documents->count() }} dokumen terunggah</div>
        </div>
    </div>

    {{-- Rejection Note --}}
    @if($item->verification_status === 'REJECTED' && $item->rejection_note)
    <div style="margin-top:14px;padding:12px 14px;background:#fee2e2;border-radius:8px;border-left:4px solid #dc2626;">
        <div style="font-size:12px;font-weight:700;color:#991b1b;margin-bottom:4px;">📝 Catatan Penolakan Bendahara:</div>
        <div style="font-size:13px;color:#7f1d1d;">{{ $item->rejection_note }}</div>
    </div>
    @endif
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;">

{{-- ── DOKUMEN LIST ─────────────────────────────────────── --}}
<div>
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
        <h2 style="font-size:14px;font-weight:700;color:#0f172a;">📎 Daftar Dokumen SPJ / BAPP / Kuitansi</h2>
    </div>

    {{-- Upload Form (Operator, Supervisor, Admin) --}}
    @if(auth()->user()->canUpload())
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:20px;margin-bottom:16px;"
         x-data="fileUploader()">

        <div style="font-size:13px;font-weight:600;color:#374151;margin-bottom:14px;">
            ⬆️ Unggah Dokumen Baru
        </div>

        <form action="{{ route('documents.store', $item) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Dropzone Area --}}
            <div class="dropzone" id="dropzone"
                 @click="$refs.fileInput.click()"
                 @dragover.prevent="isDragOver = true"
                 @dragleave="isDragOver = false"
                 @drop.prevent="handleDrop($event)"
                 :class="{ 'drag-over': isDragOver }">
                <div class="dz-icon">📂</div>
                <div class="dz-text">Klik atau seret file ke sini</div>
                <div class="dz-hint">PDF, JPG, PNG • Maksimal 15 MB per file • Multi-file didukung</div>
            </div>

            <input type="file" x-ref="fileInput" name="files[]" multiple
                   accept=".pdf,.jpg,.jpeg,.png" class="hidden" style="display:none;"
                   @change="handleFiles($event.target.files)">

            {{-- File Preview List --}}
            <div x-show="files.length > 0" style="margin-top:14px;" x-transition>
                <div style="font-size:12px;font-weight:600;color:#374151;margin-bottom:8px;">
                    File dipilih (<span x-text="files.length"></span>):
                </div>
                <template x-for="(file, index) in files" :key="index">
                    <div style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:#f8fafc;border-radius:8px;margin-bottom:6px;border:1px solid #e2e8f0;">
                        <span x-text="fileIcon(file.name)" style="font-size:18px;"></span>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:12.5px;font-weight:500;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" x-text="file.name"></div>
                            <div style="font-size:11px;color:#94a3b8;" x-text="formatSize(file.size)"></div>
                        </div>
                        <input type="text" :name="'labels[' + index + ']'"
                               placeholder="Label (misal: BAPP Honor)"
                               class="form-input" style="width:180px;padding:5px 8px;font-size:12px;">
                        <button type="button" @click="removeFile(index)"
                                style="color:#dc2626;font-size:18px;cursor:pointer;background:none;border:none;padding:0;">×</button>
                    </div>
                </template>

                <button type="submit" class="btn btn-primary" style="margin-top:10px;width:100%;"
                        x-text="'⬆️ Unggah ' + files.length + ' Dokumen ke Sistem'">
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Documents Table --}}
    <div class="table-card">
        <div style="padding:14px 16px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:13px;font-weight:600;color:#374151;">Dokumen Tersimpan</span>
            <span style="font-size:12px;color:#64748b;">{{ $item->documents->count() }} file</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama File</th>
                    <th>Label</th>
                    <th>Ukuran</th>
                    <th>Diunggah oleh</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($item->documents as $i => $doc)
                <tr>
                    <td style="color:#94a3b8;font-size:12px;">{{ $i + 1 }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:7px;">
                            <span style="font-size:18px;">
                                @if($doc->isPdf()) 📄
                                @else 🖼️
                                @endif
                            </span>
                            <div>
                                <div style="font-weight:500;color:#1e293b;font-size:12.5px;">{{ $doc->file_name }}</div>
                                <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;">{{ $doc->file_type }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($doc->label)
                            <span style="background:#e8f0fe;color:#003087;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:500;">{{ $doc->label }}</span>
                        @else
                            <span style="color:#cbd5e1;font-size:12px;">—</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:#64748b;">{{ $doc->file_size_formatted }}</td>
                    <td style="font-size:12px;color:#64748b;">{{ $doc->uploadedBy->name }}</td>
                    <td style="font-size:11.5px;color:#94a3b8;">{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div style="display:flex;gap:5px;flex-wrap:wrap;">
                            {{-- Preview Button (opens modal) --}}
                            <button type="button"
                                    @click="openPreview('{{ route('documents.stream', $doc) }}', '{{ addslashes($doc->file_name) }}', '{{ $doc->file_type }}')"
                                    class="btn btn-secondary btn-sm"
                                    x-data>
                                👁️ Preview
                            </button>

                            {{-- Download --}}
                            <a href="{{ route('documents.download', $doc) }}" class="btn btn-secondary btn-sm">
                                ⬇️
                            </a>

                            {{-- Delete --}}
                            @if(auth()->user()->canUpload() && (auth()->user()->isAdmin() || auth()->user()->isSupervisor() || $doc->uploaded_by_user_id === auth()->id()))
                            <form action="{{ route('documents.destroy', $doc) }}" method="POST"
                                  onsubmit="return confirm('Hapus dokumen ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:32px;color:#94a3b8;">
                        📂 Belum ada dokumen. Unggah dokumen SPJ, BAPP, atau Kuitansi di atas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── BENDAHARA VERIFICATION PANEL ──────────────────────── --}}
<div style="position:sticky;top:76px;">
    <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,.04);">
        <div style="font-size:13.5px;font-weight:700;color:#0f172a;margin-bottom:4px;">🏦 Panel Verifikasi Pencairan</div>
        <div style="font-size:12px;color:#64748b;margin-bottom:16px;">Khusus Bendahara & Admin</div>

        {{-- Current Status Display --}}
        <div style="padding:14px;background:#f8fafc;border-radius:10px;margin-bottom:16px;text-align:center;">
            <div style="font-size:11px;color:#64748b;margin-bottom:6px;">Status Saat Ini</div>
            <span class="badge {{ $item->status_badge_class }}" style="font-size:14px;padding:8px 18px;">
                @if($item->verification_status === 'APPROVED') ✅ Siap Cair
                @elseif($item->verification_status === 'REJECTED') ❌ Ditolak
                @else ⏳ Menunggu Verifikasi
                @endif
            </span>
        </div>

        @if(auth()->user()->canVerify())
        <div x-data="{ showRejectForm: false }">

            {{-- Approve Button --}}
            <form action="{{ route('items.verify', $item) }}" method="POST" style="margin-bottom:10px;"
                  onsubmit="return confirm('Setujui pencairan item {{ $item->code }}?\nPastikan semua dokumen telah diperiksa.')">
                @csrf @method('PATCH')
                <input type="hidden" name="action" value="APPROVED">
                <button type="submit" class="btn btn-success" style="width:100%;">
                    ✅ Setujui Pencairan
                </button>
            </form>

            {{-- Reject Button / Form --}}
            <button type="button" class="btn btn-danger" style="width:100%;margin-bottom:10px;"
                    @click="showRejectForm = !showRejectForm">
                ❌ Tolak / Butuh Revisi
            </button>

            <div x-show="showRejectForm" x-transition style="background:#fee2e2;padding:14px;border-radius:10px;">
                <form action="{{ route('items.verify', $item) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="action" value="REJECTED">
                    <label class="form-label" style="color:#991b1b;">Catatan Penolakan *</label>
                    <textarea name="rejection_note" rows="3" class="form-input" required
                              placeholder="Contoh: BAPP belum ditandatangani PPK, Kuitansi kurang legalisir..."
                              style="font-size:12.5px;resize:vertical;"></textarea>
                    <button type="submit" class="btn btn-danger" style="margin-top:8px;width:100%;">
                        Kirim Penolakan
                    </button>
                </form>
            </div>
        </div>
        @else
        <div style="text-align:center;padding:16px;color:#94a3b8;font-size:12.5px;">
            🔒 Hanya Bendahara atau Admin yang dapat melakukan verifikasi.
        </div>
        @endif
    </div>

    {{-- Document Stats --}}
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;margin-top:14px;">
        <div style="font-size:12px;font-weight:700;color:#374151;margin-bottom:10px;">📊 Ringkasan Dokumen</div>
        @php
            $pdfCount = $item->documents->filter(fn($d) => $d->isPdf())->count();
            $imgCount = $item->documents->filter(fn($d) => $d->isImage())->count();
            $totalSize = $item->documents->sum('file_size');
        @endphp
        <div style="display:flex;flex-direction:column;gap:8px;">
            <div style="display:flex;justify-content:space-between;font-size:12.5px;">
                <span style="color:#64748b;">📄 File PDF</span>
                <strong>{{ $pdfCount }}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12.5px;">
                <span style="color:#64748b;">🖼️ File Gambar</span>
                <strong>{{ $imgCount }}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12.5px;">
                <span style="color:#64748b;">💾 Total Ukuran</span>
                <strong>{{ $totalSize > 0 ? round($totalSize / 1048576, 2) . ' MB' : '0 KB' }}</strong>
            </div>
        </div>
    </div>
</div>

</div>{{-- end grid --}}

{{-- ── PDF PREVIEW MODAL ─────────────────────────────────── --}}
<div x-data="previewModal()" x-show="open" x-transition
     class="modal-backdrop" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <div style="font-size:14px;font-weight:700;color:#0f172a;" x-text="fileName"></div>
                <div style="font-size:11.5px;color:#64748b;margin-top:2px;">Pratinjau Dokumen — Sistem Arsip Keuangan BPS</div>
            </div>
            <button type="button" @click="close()"
                    style="font-size:22px;color:#64748b;background:none;border:none;cursor:pointer;padding:4px 8px;">×</button>
        </div>
        <div class="modal-body">
            <template x-if="fileType === 'pdf'">
                <iframe :src="fileUrl" class="modal-iframe" title="PDF Viewer"></iframe>
            </template>
            <template x-if="fileType !== 'pdf'">
                <div style="display:flex;align-items:center;justify-content:center;height:75vh;background:#f8fafc;">
                    <img :src="fileUrl" style="max-width:100%;max-height:100%;object-fit:contain;" :alt="fileName">
                </div>
            </template>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// File uploader Alpine.js component
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
            // Sync to actual input
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

// PDF/Image preview modal
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

// Global handler for preview button clicks
document.addEventListener('alpine:init', () => {
    window.openPreview = (url, name, type) => {
        // Dispatch to modal
        document.querySelector('[x-data="previewModal()"]')?._x_dataStack?.[0]?.openPreview(url, name, type);
    };
});
</script>
@endpush
