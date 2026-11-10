# 📅 DOKUMEN RENCANA MULTI-TAHUN ANGGARAN & MODE ARSIP LAMPAU
## SISTEM DATA DIGITAL ARSIP KEUANGAN BPS KABUPATEN SUBANG
### (Cetak Biru Akses Data Historis & Pencegahan Kesalahan Input Pegawai)

> **Status Dokumen:** Rencana Strategis & Cetak Biru Fitur (Disimpan untuk Implementasi Produksi).  
> **Tujuan:** Memfasilitasi pegawai, bendahara, pimpinan, dan auditor BPK/Inspektorat untuk melihat dan mengunduh data pertanggungjawaban keuangan tahun-tahun sebelumnya (misal TA 2025 saat berada di TA 2026) dengan antarmuka yang jelas, aman, dan mencegah salah input/salah verifikasi pada tahun yang sudah tutup buku.

---

## 📑 DAFTAR ISI
1. [Latar Belakang & Kebutuhan Operasional](#1-latar-belakang--kebutuhan-operasional)
2. [Prinsip UI/UX Pencegah Kebingungan Pegawai](#2-prinsip-uiux-pencegah-kebingungan-pegawai)
3. [Desain Antarmuka (Mockup Visual)](#3-desain-antarmuka-mockup-visual)
4. [Matriks Hak Akses: Tahun Aktif vs Tahun Lampau](#4-matriks-hak-akses-tahun-aktif-vs-tahun-lampau)
5. [Arsitektur Teknis Backend (Session State & Read-Only Guard)](#5-arsitektur-teknis-backend-session-state--read-only-guard)
6. [Tahapan Implementasi Saat Siap Dieksekusi](#6-tahapan-implementasi-saat-siap-dieksekusi)

---

## 1. LATAR BELAKANG & KEBUTUHAN OPERASIONAL

Dalam tata kelola keuangan instansi Badan Pusat Statistik (BPS):
1. **Pemeriksaan & Audit Berulang:** Auditor internal (Inspektorat Utama BPS) maupun eksternal (BPK RI) sering kali memeriksa SPJ, BAPP, dan Kuitansi 1–3 tahun ke belakang.
2. **Kebutuhan Referensi Dokumen:** Pegawai dan Pejabat Pembuat Komitmen (PPK) sering membutuhkan contoh berkas SPJ tahun lalu sebagai acuan penyusunan kegiatan serupa di tahun berjalan.
3. **Integritas Data:** Data tahun yang sudah tutup buku (*closed fiscal year*) **wajib dilindungi** agar tidak ada dokumen yang tidak sengaja terhapus, terubah, atau terverifikasi ulang.

---

## 2. PRINSIP UI/UX PENCEGAH KEBINGUNGAN PEGAWAI

Agar pegawai tidak tertukar antara **Tahun Anggaran Aktif (Sedang Berjalan)** dengan **Tahun Anggaran Lampau (Arsip Historis)**, diterapkan 3 pilar UX:

### A. Global Fiscal Year Switcher (Pemilih Tahun di Topbar)
* Terletak di bagian atas kanan/tengah layar (Topbar) yang terlihat dari semua halaman.
* Menampilkan status jelas:
  * `🟢 TA 2026 (Aktif / Berjalan)`
  * `📁 TA 2025 (Arsip / Tutup Buku)`
  * `📁 TA 2024 (Arsip / Tutup Buku)`

### B. Indikator "Archive Mode" (Banner Kuning / Amber Alert)
* Saat pengguna memilih tahun lampau (misal TA 2025), muncul **Banner Peringatan Sticky** di bagian atas konten:
  > *"⚠️ **MODE ARSIP LAMPAU:** Anda sedang melihat arsip Tahun Anggaran 2025 (Status: Tutup Buku / Read-Only). [ ⬅️ Kembali ke TA 2026 Aktif ]"*
* Warna badge DIPA di topbar berubah dari **Hijau (Aktif)** menjadi **Amber/Abu-abu (Arsip)**.

### C. Auto-Lock / Read-Only Guard (Kunci Otomatis Form & Aksi)
* Semua aksi yang bersifat memodifikasi data (*Write / Update / Delete / Approve / Reject*) secara otomatis dinonaktifkan / disembunyikan.
* Aksi penelusuran data (*View / Search / Filter / Download PDF / Download ZIP*) tetap aktif 100%.

---

## 3. DESAIN ANTARMUKA (MOCKUP VISUAL)

### 3.1 Tampilan Mode Normal (Tahun Aktif 2026)
```
+---------------------------------------------------------------------------------------------------------+
|  🏛️ SAKDI BPS Subang      [ 🟢 TA 2026 (Aktif) ▼ ]                         👤 Bendahara Subang (NIP)    |
+---------------------------------------------------------------------------------------------------------+
|  Dashboard > Arsip Keuangan POK 2026                                                                    |
|  [ + Tambah Kegiatan ]   [ 📤 Unggah SPJ ]   [ 🔍 Cari Berkas... ]                                     |
|                                                                                                         |
|  Status Dokumen: ✅ 12 Disetujui | ⏳ 3 Menunggu | ❌ 0 Ditolak                                         |
+---------------------------------------------------------------------------------------------------------+
```

### 3.2 Tampilan Mode Arsip Lampau (Tahun 2025 - Read-Only)
```
+---------------------------------------------------------------------------------------------------------+
|  🏛️ SAKDI BPS Subang      [ 📁 TA 2025 (Arsip) ▼ ]                         👤 Bendahara Subang (NIP)    |
+---------------------------------------------------------------------------------------------------------+
|  ⚠️ MODE ARSIP LAMPAU: Anda sedang melihat data TA 2025 (Read-Only).       [ ⬅️ Kembali ke TA 2026 ]    |
+---------------------------------------------------------------------------------------------------------+
|  Dashboard > Arsip Keuangan POK 2025 (CLOSED)                                                           |
|  (Tombol Tambah & Unggah Disembunyikan Otomatis)     [ 🔍 Cari Berkas 2025... ]                         |
|                                                                                                         |
|  Tabel Kegiatan POK 2025:                                                                               |
|  - Rapat Koordinasi Sensus 2025 (BMA.006)  [ 👁️ Lihat Dokumen ]  [ 📥 Unduh ZIP ]                       |
|  - Honorarium Petugas Lapangan             [ 👁️ Lihat Dokumen ]  [ 📥 Unduh ZIP ]                       |
+---------------------------------------------------------------------------------------------------------+
```

---

## 4. MATRIKS HAK AKSES: TAHUN AKTIF VS TAHUN LAMPAU

| Fitur / Aksi Sistem | Tahun Berjalan (Aktif 2026) | Tahun Lampau (Arsip 2025 / 2024) |
| :--- | :---: | :---: |
| **Melihat Dashboard & Statistik Serapan** | ✅ Aktif | ✅ Aktif (Rekap Final) |
| **Pencarian Dokumen & Filter Hierarki POK** | ✅ Aktif | ✅ Aktif |
| **Pratinjau PDF Dokumen (BAPP/Kuitansi)** | ✅ Aktif | ✅ Aktif |
| **Unduh Berkas & Download ZIP SPJ** | ✅ Aktif | ✅ Aktif |
| **Export Rekap Laporan Excel/CSV** | ✅ Aktif | ✅ Aktif |
| **Unggah Dokumen Baru (Operator)** | ✅ Aktif | ❌ **Terkunci (Disabled)** |
| **Hapus / Ganti Berkas (Operator)** | ✅ Aktif | ❌ **Terkunci (Disabled)** |
| **Keputusan Verifikasi / Centang (Bendahara)** | ✅ Aktif | ❌ **Terkunci (Disabled)** |
| **Tambah / Edit Struktur POK (Supervisor)** | ✅ Aktif | ❌ **Terkunci (Disabled)** |

---

## 5. ARSITEKTUR TEKNIS BACKEND

### 5.1 Penyimpanan State Tahun Pilihan (`Session`)
Sistem menyimpan ID tahun yang sedang dilihat pengguna ke dalam session Laravel:

```php
// Controller untuk mengganti tahun tampilan
public function switchYear(Request $request)
{
    $request->validate(['fiscal_year_id' => 'required|exists:fiscal_years,id']);
    
    session(['viewing_fiscal_year_id' => $request->fiscal_year_id]);
    
    return back()->with('info', 'Menampilkan data Tahun Anggaran yang dipilih.');
}
```

### 5.2 Helper Deteksi Status Read-Only
```php
// app/Helpers/FiscalYearHelper.php
function currentViewingYear() {
    $activeId = session('viewing_fiscal_year_id');
    if ($activeId) {
        return \App\Models\FiscalYear::find($activeId);
    }
    return \App\Models\FiscalYear::where('is_active', true)->first();
}

function isReadOnlyFiscalYear(): bool {
    $current = currentViewingYear();
    return $current ? !$current->is_active : false;
}
```

### 5.3 Middleware Pengaman (`EnsureWritableFiscalYear`)
Jika ada pengguna yang mencoba mengirim request POST/PATCH/DELETE ke data tahun lampau via manipulasi form:

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureWritableFiscalYear
{
    public function handle(Request $request, Closure $next)
    {
        if (isReadOnlyFiscalYear() && !$request->isMethodSafe()) {
            return back()->with('error', 'Aksi ditolak: Tahun Anggaran ini berstatus Arsip Lampau (Read-Only / Tutup Buku).');
        }

        return $next($request);
    }
}
```

---

## 6. TAHAPAN IMPLEMENTASI SAAT SIAP DIEKSEKUSI

1. **Tambahkan Route & Controller Switcher:**
   * Route `POST /fiscal-year/switch` untuk menangani perubahan tahun di session.
2. **Perbarui Topbar Navigation (`layouts/app.blade.php`):**
   * Mengganti badge statis `DIPA 2026` dengan dropdown interaktif `FiscalYear::orderBy('year', 'desc')->get()`.
3. **Pasang Banner Peringatan Mode Arsip:**
   * Ditampilkan jika `isReadOnlyFiscalYear() == true`.
4. **Proteksi Blade View & Form:**
   * Membungkus tombol upload/verifikasi/tambah POK dengan kondisi `@if(!isReadOnlyFiscalYear()) ... @endif`.
5. **Uji Coba Verifikasi:**
   * Memastikan perpindahan tahun 2026 ↔ 2025 ↔ 2024 berjalan mulus tanpa reload berlebihan.

---

> 📌 **Catatan:** Dokumen rencana ini telah disimpan di dalam repositori proyek `d:\Project BPS\RENCANA_MULTI_TAHUN_DAN_MODE_ARSIP_LAMPAU.md` dan siap diimplementasikan kapan saja ketika dibutuhkan.
