@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', '📊 Dashboard Arsip Keuangan BPS')

@section('content')
{{-- ── STAT CARDS ──────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px;">

    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div>
                <div style="font-size:11.5px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Total Pagu Anggaran</div>
                <div class="stat-value" style="font-size:18px;">Rp {{ number_format($stats['total_pagu'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-icon" style="background:#e8f0fe;">💰</div>
        </div>
        <div class="stat-label">GG.2902 — Seluruh item kegiatan</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div>
                <div style="font-size:11.5px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Total Item Kegiatan</div>
                <div class="stat-value">{{ number_format($stats['total_items']) }}</div>
            </div>
            <div class="stat-icon" style="background:#f0fdf4;">📋</div>
        </div>
        <div class="stat-label">Seluruh item dalam hirarki POK</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div>
                <div style="font-size:11.5px;font-weight:600;color:#16a34a;text-transform:uppercase;letter-spacing:.5px;">✅ Siap Cair</div>
                <div class="stat-value" style="color:#16a34a;">{{ $stats['approved'] }}</div>
            </div>
            <div class="stat-icon" style="background:#dcfce7;">✅</div>
        </div>
        <div class="stat-label">Rp {{ number_format($stats['pagu_approved'], 0, ',', '.') }}</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div>
                <div style="font-size:11.5px;font-weight:600;color:#d97706;text-transform:uppercase;letter-spacing:.5px;">⏳ Menunggu Verifikasi</div>
                <div class="stat-value" style="color:#d97706;">{{ $stats['pending'] }}</div>
            </div>
            <div class="stat-icon" style="background:#fef9c3;">⏳</div>
        </div>
        <div class="stat-label">Belum diverifikasi Bendahara</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div>
                <div style="font-size:11.5px;font-weight:600;color:#dc2626;text-transform:uppercase;letter-spacing:.5px;">❌ Ditolak</div>
                <div class="stat-value" style="color:#dc2626;">{{ $stats['rejected'] }}</div>
            </div>
            <div class="stat-icon" style="background:#fee2e2;">❌</div>
        </div>
        <div class="stat-label">Perlu revisi dokumen</div>
    </div>
</div>

{{-- ── MVP FOCUS: BMA.006 RECENT ITEMS ──────────────────── --}}
<div style="margin-bottom:24px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <div>
            <h2 style="font-size:15px;font-weight:700;color:#0f172a;">🎯 Fokus MVP: BMA.006 Sensus Ekonomi 2026</h2>
            <p style="font-size:12px;color:#64748b;margin-top:2px;">Item kegiatan terbaru yang memerlukan perhatian verifikasi</p>
        </div>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Kode Item</th>
                    <th>Nama Kegiatan</th>
                    <th>Pagu Anggaran</th>
                    <th>Dokumen</th>
                    <th>Status Verifikasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentItems as $item)
                <tr>
                    <td>
                        <code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:12px;color:#475569;">
                            {{ $item->code }}
                        </code>
                    </td>
                    <td>
                        <div style="font-weight:500;color:#1e293b;">{{ Str::limit($item->name, 55) }}</div>
                        <div style="font-size:11px;color:#94a3b8;margin-top:2px;">
                            {{ $item->account->code }} — {{ Str::limit($item->account->name, 30) }}
                        </div>
                    </td>
                    <td>
                        <span class="pagu-badge">Rp {{ number_format($item->pagu, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        <span style="font-size:12.5px;font-weight:600;color:{{ $item->documents->count() > 0 ? '#16a34a' : '#94a3b8' }};">
                            {{ $item->documents->count() }} file
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $item->status_badge_class }}">
                            @if($item->verification_status === 'APPROVED') ✅ Siap Cair
                            @elseif($item->verification_status === 'REJECTED') ❌ Ditolak
                            @else ⏳ Menunggu
                            @endif
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('items.show', $item) }}" class="btn btn-primary btn-sm">
                            Lihat Detail →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:32px;color:#94a3b8;">
                        Belum ada data. Jalankan seeder terlebih dahulu.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── INFO CARD ──────────────────────────────────────── --}}
<div style="background:linear-gradient(135deg,#003087,#0d47a1);border-radius:12px;padding:20px 24px;color:#fff;display:flex;align-items:center;gap:16px;">
    <div style="font-size:36px;">📌</div>
    <div>
        <div style="font-size:14px;font-weight:700;">Cara Penggunaan Sistem</div>
        <div style="font-size:12.5px;opacity:.85;margin-top:4px;line-height:1.6;">
            Gunakan sidebar kiri untuk navigasi ke item kegiatan spesifik (kode 001366, 001211, dll).
            <strong>Operator</strong> dapat mengunggah dokumen SPJ/BAPP/Kuitansi. 
            <strong>Bendahara</strong> dapat menyetujui atau menolak pencairan setelah memeriksa pratinjau PDF.
        </div>
    </div>
</div>
@endsection
