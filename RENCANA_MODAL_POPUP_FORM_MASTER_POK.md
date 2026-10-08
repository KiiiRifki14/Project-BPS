# 📋 DOKUMEN RENCANA REDESAIN FORM MASTER POK (MODAL / POPUP DIALOG)
## SISTEM DATA DIGITAL ARSIP KEUANGAN BPS KABUPATEN SUBANG
### (Transformasi Layout Side-Form Menjadi Full-Width Table + Modal Dialog)

> **Status Dokumen:** Rencana Strategis & Cetak Biru UI/UX (Disimpan untuk Implementasi Produksi).  
> **Tujuan:** Mengoptimalkan ruang visual halaman *Kelola Master POK* dengan mengubah form statis di sisi kanan menjadi tombol aksi interaktif `[ + Tambah Item Baru ]` yang membuka form dalam bentuk **Popup Modal Dialog**, sehingga tabel daftar kegiatan mendapat ruang penuh 100% (*full-width*) yang lega dan elegan.

---

## 📑 DAFTAR ISI
1. [Latar Belakang & Analisis Layout Saat Ini](#1-latar-belakang--analisis-layout-saat-ini)
2. [Konsep Solusi & Keuntungan UI/UX](#2-konsep-solusi--keuntungan-uiux)
3. [Perbandingan Desain Layout (Before vs After)](#3-perbandingan-desain-layout-before-vs-after)
4. [Spesifikasi Interaksi Modal Popup (Alpine.js)](#4-spesifikasi-interaksi-modal-popup-alpinejs)
5. [Standarisasi ke Seluruh Tab Master Data POK](#5-standarisasi-ke-seluruh-tab-master-data-pok)
6. [Tahapan Teknis Implementasi](#6-tahapan-teknis-implementasi)

---

## 1. LATAR BELAKANG & ANALISIS LAYOUT SAAT INI

Pada versi saat ini di menu **Kelola Master POK**:
* **Kondisi Eksisting:** Form *"Tambah Item Baru"* diletakkan statis di kolom sebelah kanan (memakan porsi ~33% lebar layar).
* **Kendala yang Muncul:**
  1. **Tabel Terhimpit:** Kolom nama kegiatan, kode akun, dan pagu anggaran menjadi lebih sempit karena harus berbagi layar dengan form input yang jarang dipakai terus-menerus.
  2. **Scroll Berlebih:** Form kanan memiliki tinggi yang berbeda dengan tabel kiri, menyebabkan tampilan kurang simetris saat data tabel bertambah panjang.
  3. **Pengalaman Split-Screen Kurang Maksimal:** Saat jendela browser diperkecil / dibagi dua, tabel terasa padat.

---

## 2. KONSEP SOLUSI & KEUNTUNGAN UI/UX

Dengan memindahkan form input ke dalam **Popup Modal Dialog**:

```
+--------------------------------------------------------------------------------------------------+
| 📋 TAB AKTIF: ITEM KEGIATAN POK                                            32 Total Item         |
+--------------------------------------------------------------------------------------------------+
| Daftar Item Kegiatan                         [ 🔍 Cari Item... ]   [ ➕ Tambah Item Baru ]       |
| +--------+------------------------------------+---------------+------------------+-------------+ |
| | KODE   | NAMA ITEM KEGIATAN                 | AKUN / SUB-OUT| PAGU ANGGARAN    | AKSI        | |
| +--------+------------------------------------+---------------+------------------+-------------+ |
| | 000698 | Task force pendataan lengkap...    | [524113]      | Rp 45.000.000    | [Edit][Hapus| |
| | 000699 | Jasa desain materi publisitas SE...| [522191]      | Rp 25.000.000    | [Edit][Hapus| |
| +--------+------------------------------------+---------------+------------------+-------------+ |
|                                                                    < 1  2  3  4 >                |
+--------------------------------------------------------------------------------------------------+
```

### Keuntungan Utama:
1. **Tabel Luas & Nyaman Dibaca (100% Full-Width):** Nama kegiatan POK yang panjang (misal: *"Jasa narasumber pelatihan enumerator SE2026 sesi 1"*) dapat terbaca utuh tanpa terpotong.
2. **Fokus Input Terarah (No Distraction):** Saat menekan tombol `+ Tambah Item Baru`, muncul modal popup di tengah layar dengan latar belakang gelap/blur (*backdrop focus*), membuat supervisor lebih fokus saat mengisi akun, kode, dan pagu.
3. **Responsif Sempurna di Semua Ukuran Layar:** Baik di layar monitor lebar, laptop 14 inch, maupun saat browser dibagi dua (*split-screen*), tabel tetap rapi dan tombol tambah selalu mudah dijangkau.

---

## 3. PERBANDINGAN DESAIN LAYOUT (BEFORE VS AFTER)

### 3.1 Layout Sebelum (Side-by-Side Form)
```
+-----------------------------------------------------+-----------------------------+
|               TABEL ITEM (66% LEBAR)                |     FORM INPUT (33%)        |
| - Kolom nama kegiatan rentan berdesakan             | - Form statis selalu tampak |
| - Tampilan terasa padat                             |   meski tidak sedang input  |
+-----------------------------------------------------+-----------------------------+
```

### 3.2 Layout Baru (Full-Width + Popup Modal)
```
+-----------------------------------------------------------------------------------+
|               TABEL ITEM KEGIATAN LENGKAP (100% LEBAR LAYAR)                      |
| [ 🔍 Cari Item Kegiatan ]                             [ ➕ Tambah Item Baru ]     |
| - Ruang nama kegiatan sangat lega                                                 |
| - Pagu & Kode akun tertata simetris                                               |
+-----------------------------------------------------------------------------------+

                          ⬇️ (Saat Tombol Diklik) ⬇️

        +----------------------------------------------------------+
        |  ➕ Tambah Item Kegiatan POK Baru                    [✕]  |
        +----------------------------------------------------------+
        |  Akun POK:                                               |
        |  [ 🔍 Cari & Pilih Kode Akun (521211 / 524113)...    ▼ ] |
        |                                                          |
        |  Kode Item (6 Digit):                                    |
        |  [ 001366                                              ] |
        |                                                          |
        |  Nama Item Kegiatan:                                     |
        |  [ Honor Petugas Lapangan Sensus Ekonomi 2026...       ] |
        |                                                          |
        |  Pagu Anggaran (Rp):                                     |
        |  [ Rp 15.000.000                                       ] |
        |                                                          |
        |  [ Batal ]                               [ 💾 Simpan ]   |
        +----------------------------------------------------------+
```

---

## 4. SPESIFIKASI INTERAKSI MODAL POPUP (ALPINE.JS)

Modal akan diatur menggunakan state reaktif `Alpine.js` bawaan aplikasi:

### Fitur Interaktivitas Modal:
1. **Open Trigger:** Tombol `[ + Tambah Item Baru ]` dengan styling tombol utama (*Primary Button*) berbayang elegan.
2. **Backdrop & Transitions:** Animasi halus *fade-in* backdrop (`opacity-50 bg-slate-900`) dan *scale-up* modal box (`transform ease-out duration-200`).
3. **Aksesibilitas & Keyboard Navigation:**
   * Menekan tombol `Esc` pada keyboard otomatis menutup modal.
   * Klik di luar area modal (*backdrop click*) otomatis menutup modal.
   * Input pertama (Pencarian Akun POK) otomatis menerima *autofocus*.
4. **Formatting Rupiah Real-time:** Input pagu anggaran tetap dilengkapi format otomatis ribuan (contoh: `15.000.000`).

---

## 5. STANDARISASI KE SELURUH TAB MASTER DATA POK

Pola desain Modal Popup ini akan distandarisasi untuk seluruh tab di menu **Kelola Master POK**:

| Tab Master Data | Tombol Pemicu Modal | Isi Modal Popup |
| :--- | :--- | :--- |
| **📋 Item Kegiatan** | `+ Tambah Item Baru` | Akun POK, Kode Item 6-digit, Nama Kegiatan, Pagu (Rp). |
| **💳 Akun** | `+ Tambah Akun Baru` | Sub-Komponen POK, Kode Akun 6-digit, Nama Akun. |
| **🔷 Sub-Komponen** | `+ Tambah Sub-Komponen` | Komponen POK, Kode Sub-Komponen, Nama Sub-Komponen. |
| **🔶 Komponen** | `+ Tambah Komponen` | Sub-Output POK, Kode Komponen 3-digit, Nama Komponen. |
| **📦 Sub-Output** | `+ Tambah Sub-Output` | Output POK, Kode Sub-Output (cth: BMA.006), Nama Sub-Output. |
| **📁 Output** | `+ Tambah Output` | Program POK, Kode Output, Nama Output. |

---

## 6. TAHAPAN TEKNIS IMPLEMENTASI

Saat instruksi eksekusi diberikan, langkah-langkah modifikasi pada `resources/views/master/index.blade.php`:

1. **Ubah Wrapper Grid Tabel:**
   * Hapus class `grid grid-cols-1 xl:grid-cols-3 gap-8`.
   * Jadikan wrapper tabel `w-full` dengan header tabel berisi tombol `[ + Tambah Item Baru ]`.
2. **Pasang State Alpine.js:**
   * Tambahkan state `showCreateModal: false` pada container tab.
3. **Pindahkan Form ke Komponen Modal:**
   * Bungkus form pembuatan item dalam modal dengan `@click.away="showCreateModal = false"` dan `@keydown.escape.window="showCreateModal = false"`.
4. **Verifikasi Fungsionalitas:**
   * Uji penambahan item baru via modal, pastikan validasi backend dan pesan flash sukses berfungsi normal.

---

> 📌 **Catatan:** Dokumen rencana ini telah disimpan di dalam repositori `d:\Project BPS\RENCANA_MODAL_POPUP_FORM_MASTER_POK.md`. Kode aplikasi saat ini belum diubah dan siap diterapkan kapan saja sesuai instruksi.
