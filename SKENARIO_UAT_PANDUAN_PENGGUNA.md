# 📑 SKENARIO UAT & PANDUAN PENGUJIAN PENGGUNA (USER ACCEPTANCE TESTING)
## SISTEM DATA DIGITAL ARSIP KEUANGAN BPS KABUPATEN SUBANG

Panduan ini disusun untuk memandu jalannya uji coba sistem (UAT) oleh pegawai BPS Kabupaten Subang sesuai role masing-masing.

---

## 🔵 SKENARIO 1: UJI COBA ROLE ADMIN (`admin` / `password`)

1. **Login Skenario Demo**:
   - Buka `http://127.0.0.1:8000/login`
   - Klik tombol demo **`🔵 Login sebagai ADMIN`** (atau ketik username `admin` & password `password`).
2. **Pengujian Manajemen Pengguna (`/users`)**:
   - Buka menu **Manajemen Pengguna** di sidebar navigasi.
   - Tambah akun pengguna baru (contoh: *Budi Santoso*, Role: *OPERATOR*).
3. **Pengujian Verifikasi Pengganti (Segregation Guard)**:
   - Buka menu **Verifikasi Pencairan**.
   - Coba setujui item yang dokumennya diunggah oleh orang lain $\rightarrow$ *Berhasil*.
   - Coba setujui item yang dokumennya diunggah oleh diri sendiri $\rightarrow$ *Sistem memblokir (Guard 6 Segregation of Duties)*.

---

## 🟢 SKENARIO 2: UJI COBA ROLE OPERATOR (`operator` / `password`)

1. **Login Operator**:
   - Klik tombol demo **`🟢 Login sebagai OPERATOR`**.
2. **Pencarian Item Kegiatan (`/items`)**:
   - Buka menu **Arsip Keuangan POK**.
   - Cari item `001366` atau gunakan filter program/sub-output.
3. **Pengunggahan Dokumen SPJ**:
   - Buka Workspace Detail Item `001366`.
   - Unggah file PDF / Gambar Kuitansi / BAPP Honor.
   - Pilih Label Kategori Berkas (misal: *BAPP Honor Sensus*).
   - Klik **Unggah Dokumen**.
4. **Membaca Alasan Revisi (Jika REJECTED)**:
   - Perhatikan badge merah di sidebar navigasi.
   - Buka item yang berstatus `REJECTED`, baca catatan penolakan dari Bendahara, lalu unggah berkas revisi yang baru.

---

## 🟠 SKENARIO 3: UJI COBA ROLE BENDAHARA (`bendahara` / `password`)

1. **Login Bendahara**:
   - Klik tombol demo **`🟠 Login sebagai BENDAHARA`**.
2. **Inbox Verifikasi (`/verification`)**:
   - Perhatikan badge kuning antrean di sidebar navigasi.
   - Buka menu **Verifikasi Pencairan**. Item yang mengendap paling lama berada paling atas (FIFO Rule).
3. **Pemeriksaan & Centang Checklist**:
   - Klik item yang akan diperiksa.
   - Klik **`👁️ Pratinjau Inline`** untuk melihat isi berkas PDF/Kuitansi tanpa mengunduh.
   - Centang seluruh dokumen pada **Panel Verifikasi Checklist** di sidebar kanan.
4. **Eksekusi Verifikasi**:
   - Klik **Setujui Pencairan (APPROVED)** jika seluruh berkas sudah lengkap $\rightarrow$ *Item terkunci*.
   - Klik **Tolak / Minta Revisi (REJECTED)** dan isi catatan revisi jika berkas belum lengkap.

---

## 🟣 SKENARIO 4: UJI COBA ROLE SUPERVISOR & KEPALA BPS (`supervisor` / `password`)

1. **Login Supervisor**:
   - Klik tombol demo **`🟣 Login sebagai SUPERVISOR`**.
2. **Monitoring Dashboard Eksekutif (`/dashboard`)**:
   - Memantau 5 Kartu KPI (Total Pagu, Approved, Pending, Rejected, Sisa Pagu).
   - Memantau grafik persentase daya serap anggaran kegiatan BMA.006 Sensus Ekonomi 2026.
3. **Export Laporan Digital (`/reports`)**:
   - Buka menu **Laporan & Rekapitulasi**.
   - Uji filter periode Mingguan, Bulanan, atau Tahunan.
   - Klik **`📊 Export ke Excel (.csv)`** atau **`Cetak Laporan`**.

---
*Dokumen skenario ini siap dicetak atau dibagikan kepada peserta uji coba UAT BPS Kabupaten Subang.*
