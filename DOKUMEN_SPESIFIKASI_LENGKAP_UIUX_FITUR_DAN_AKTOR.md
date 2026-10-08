# 🏛️ DOKUMEN SPESIFIKASI LENGKAP ARSITEKTUR UI/UX & FITUR SISTEM
## SISTEM DATA DIGITAL ARSIP KEUANGAN BPS KABUPATEN SUBANG

> **Tujuan Dokumen:** *Master Design Blueprint & Feature Specification* untuk perombakan UI/UX total seluruh aktor (Admin, Supervisor, Bendahara, Operator).
> **Panduan untuk AI Designer:** Gunakan dokumen ini sebagai referensi mutlak (*Single Source of Truth*). Jangan menyederhanakan menu atau hanya mendesain satu tampilan per aktor. Setiap aktor memiliki alur kerja, menu, hak akses, dan komponen antarmuka yang spesifik. Semua layar pada **Bagian 10 (Inventaris Layar)** WAJIB dihasilkan.

---

# 📑 DAFTAR ISI
0. [Aturan Penamaan Sistem (WAJIB)](#0-aturan-penamaan-sistem-wajib)
1. [Design System & Identitas Visual](#1-design-system--identitas-visual)
2. [Matriks Hak Akses & Peta Menu 4 Aktor](#2-matriks-hak-akses--peta-menu-4-aktor)
3. [Alur Status Item & Berkas](#3-alur-status-item--berkas)
4. [Aktor 1: OPERATOR](#4-aktor-1-operator)
5. [Aktor 2: BENDAHARA PENGELUARAN](#5-aktor-2-bendahara-pengeluaran)
6. [Aktor 3: SUPERVISOR / PJ KEGIATAN](#6-aktor-3-supervisor--pj-kegiatan)
7. [Aktor 4: ADMINISTRATOR (ADMIN)](#7-aktor-4-administrator-admin)
8. [Halaman Publik & Komponen Global](#8-halaman-publik--komponen-global)
9. [7 Aturan Logika Pengamanan Keuangan (Financial Guards)](#9-7-aturan-logika-pengamanan-keuangan-financial-guards)
10. [Inventaris Layar yang Wajib Dibuat](#10-inventaris-layar-yang-wajib-dibuat)
11. [Prompt Template untuk AI Designer](#11-prompt-template-untuk-ai-designer)

---

# 0. ATURAN PENAMAAN SISTEM (WAJIB)

Nama resmi sistem, **tanpa singkatan, tanpa akronim, tanpa nama lain**:

```
SISTEM DATA DIGITAL ARSIP KEUANGAN BPS KABUPATEN SUBANG
```

* Gunakan nama tersebut **persis seperti tertulis** di: halaman login, topbar/header, judul tab browser (`<title>`), footer, kop laporan cetak, dan metadata ekspor.
* **DILARANG** membuat, menyingkat, atau menambahkan nama/akronim/nama produk lain (termasuk nama kode, nama design system, atau nama fiktif) di antarmuka mana pun.
* Di ruang sempit (topbar mobile), boleh dipotong dengan elipsis (`…`) atau ditampilkan dua baris, **bukan** diganti singkatan. Baris bantu di bawah nama: `BPS Kabupaten Subang`.
* Teks antarmuka seluruhnya **Bahasa Indonesia** formal-ramah.

---

# 1. DESIGN SYSTEM & IDENTITAS VISUAL

Untuk menjamin konsistensi grafis di seluruh layar. (Di dokumen dan antarmuka cukup disebut **"Design System"**, tanpa nama merek.)

### 1.1 Palet Warna Resmi
* **BPS Primary Blue (`#0057A8`):** Identitas utama BPS, tombol primer, tab aktif, brand header.
* **BPS Navy Dark (`#002D5C`):** Latar sidebar dan footer resmi.
* **BPS Accent Sky (`#0284C7` / `#38BDF8`):** Aksen sekunder, kartu sorotan, link navigasi.
* **BPS Positive Green (`#00873E` / `#22C55E`):** Status *APPROVED*, checklist selesai.
* **BPS Warning Amber (`#D97706` / `#F59E0B`):** Status *PENDING*, perhatian khusus.
* **BPS Error Red (`#DC2626` / `#EF4444`):** Status *REJECTED*, tombol hapus, error.
* **Neutral Canvas (`#F8FAFC` / `#FFFFFF`):** Latar halaman; kartu putih dengan *soft shadow* `0 4px 12px rgba(0,0,0,0.05)`.
* **Neutral Gray (`#64748B` teks sekunder, `#E2E8F0` border, `#94A3B8` status "Belum Ada Berkas").**

### 1.2 Warna Badge Peran (DIPISAH dari warna status)
Warna hijau/kuning/merah sudah dipakai untuk status berkas, jadi **jangan dipakai lagi untuk peran** agar tidak membingungkan:

| Peran | Warna | Ikon |
|---|---|---|
| Administrator | Slate `#334155` | perisai |
| Supervisor / PJ | Sky `#0284C7` | bintang/lencana |
| Operator | Teal `#0F766E` | dokumen+panah naik |
| Bendahara | Violet `#7C3AED` | centang-dalam-lingkaran |

### 1.3 Badge Status (4 status, selalu **warna + ikon + teks**, tidak boleh hanya warna)
| Status | Warna | Ikon | Label |
|---|---|---|---|
| Belum Ada Berkas | Abu | lingkaran putus-putus | `BELUM ADA BERKAS` |
| Pending | Amber | jam | `PENDING` |
| Approved | Hijau | centang | `APPROVED` |
| Rejected | Merah | silang | `REJECTED` |

### 1.4 Tipografi
* **Font UI:** Inter / Plus Jakarta Sans.
* **Font Monospace Angka:** JetBrains Mono / Fira Code, **wajib** untuk NIP, Kode POK, Kode Akun, dan Nominal Rupiah.
* **Skala:** H1 28/36 · H2 22/30 · H3 18/26 · Body 14/22 · Caption 12/18 · Angka KPI 32/40 (semibold, monospace).

### 1.5 Spacing, Radius, Elevasi
* Grid spasi 4 px (4, 8, 12, 16, 24, 32, 48).
* Radius: tombol/input 8 px, kartu 12 px, modal 16 px, badge pill penuh.
* Elevasi: kartu = soft shadow di atas; modal/drawer = shadow lebih kuat + overlay `rgba(0,22,46,0.5)`.

### 1.6 Komponen Standar (buat satu kali, pakai di semua layar)
* **Tombol:** Primary (biru), Secondary (outline), Danger (merah), Success (hijau), Ghost, dan **Disabled** (abu + ikon gembok + tooltip alasan terkunci).
* **Input:** default, fokus (ring biru), error (border merah + pesan), disabled.
* **Tabel:** header sticky, zebra halus, hover baris, **search bar**, **pagination** (10/25/50 per halaman + info "Menampilkan 1-10 dari N"), sortir kolom, dan state kosong.
* **Badge status & peran, KPI card, progress bar, tab, breadcrumb, modal, drawer, toast, alert banner, dropdown searchable, dropzone upload, timeline, skeleton loader.**

### 1.7 Aturan Format Data
* Rupiah: `Rp 925.600.000` (titik ribuan, tanpa desimal), monospace, rata kanan di tabel.
* Tanggal: `08 Okt 2026, 14:30 WIB`. Ukuran file: `2,4 MB`.
* Kode item 6 digit tampil sebagai badge monospace (contoh `001366`).
* **Nilai dinamis, jangan di-hardcode:** Tahun Anggaran aktif (`DIPA 2026`) dan kegiatan prioritas (contoh `BMA.006 Sensus Ekonomi 2026`) berasal dari data/pengaturan, bukan teks tetap.

### 1.8 Responsif & Aksesibilitas
* Breakpoint: Mobile ≤ 640 px, Tablet 641-1024 px, Desktop ≥ 1025 px.
* Mobile: sidebar menjadi drawer (hamburger), tabel menjadi kartu bertumpuk atau scroll horizontal dengan kolom kunci tetap, modal menjadi *bottom sheet / full screen*.
* Kontras minimal WCAG AA, fokus keyboard terlihat, target sentuh ≥ 44 px, animasi "berkedip" hanya denyut halus dan hormati `prefers-reduced-motion`.

---

# 2. MATRIKS HAK AKSES & PETA MENU 4 AKTOR

| Menu Navigasi | Rute URL | Operator | Bendahara | Supervisor | Admin |
|---|---|:---:|:---:|:---:|:---:|
| **Dashboard Utama** | `/dashboard` | ✅ (Personal) | ✅ (Antrean SPJ) | ✅ (Eksekutif) | ✅ (Penuh) |
| **Arsip Keuangan POK** | `/arsip` / `/items` | ✅ (Unggah) | ✅ (Audit) | ✅ (Audit) | ✅ (Penuh) |
| **Detail Item Workspace** | `/items/{id}` | ✅ (Upload/Hapus milik sendiri) | ✅ (Verifikasi + Unduh ZIP) | ✅ (Pantau, baca-saja) | ✅ (Penuh) |
| **Inbox Verifikasi SPJ** | `/verification` | ❌ 403 | ✅ **(Menu Utama)** | ❌ 403 | ✅ (Pengganti) |
| **Kelola Master Data POK** | `/master` | ❌ 403 | ❌ 403 | ✅ **(Menu Utama)** | ✅ (Penuh) |
| **Laporan & Rekapitulasi** | `/reports` | ✅ (Lihat/Cetak) | ✅ (Lihat/Cetak) | ✅ **(Menu Utama)** | ✅ (Penuh) |
| **Manajemen Pengguna** | `/users` | ❌ 403 | ❌ 403 | ❌ 403 | ✅ **(Menu Utama)** |
| **Audit Log Sistem** | `/audit-log` | ❌ 403 | ❌ 403 | ❌ 403 | ✅ **(Menu Utama)** |
| **Profil Akun** | `/profile` | ✅ | ✅ | ✅ | ✅ |

**Urutan menu sidebar per peran:**
* Operator: Dashboard · Arsip Keuangan · Laporan · Profil.
* Bendahara: Dashboard · Inbox Verifikasi · Arsip Keuangan · Laporan · Profil.
* Supervisor: Dashboard · Master Data POK · Arsip Keuangan · Laporan · Profil.
* Admin: Dashboard · Inbox Verifikasi · Master Data POK · Arsip Keuangan · Laporan · Manajemen Pengguna · Audit Log · Profil.

Menu yang tidak berhak **disembunyikan**; jika URL diakses langsung, tampilkan halaman **403** (lihat 8.5).

---

# 3. ALUR STATUS ITEM & BERKAS

```
BELUM ADA BERKAS ──(operator unggah berkas)──▶ PENDING
PENDING ──(bendahara centang 100% + Setujui)──▶ APPROVED  (terkunci permanen, Guard 1)
PENDING ──(bendahara Tolak + catatan wajib)──▶ REJECTED
REJECTED ──(operator unggah revisi; checklist di-reset, Guard 5)──▶ PENDING
```

* Status APPROVED tidak punya aksi lanjutan di antarmuka (form unggah & hapus hilang/terkunci).
* Setiap perpindahan status tercatat di Timeline Audit Item dan di Audit Log Sistem.
* Item REJECTED menampilkan catatan penolakan **terbaru** secara mencolok, riwayat catatan sebelumnya ada di Timeline.

---

# 4. AKTOR 1: OPERATOR
> **Tanggung Jawab Utama:** Staf pelaksana unit kerja yang mengecek pos kegiatan DIPA, mengunggah berkas pertanggungjawaban (SPJ/BAPP/Kuitansi) dalam format digital, dan memperbaiki berkas jika ada catatan penolakan dari Bendahara.

---

### Tampilan 4.1: Dashboard Operator (`/dashboard`)
* **Header & Sambutan:** Nama Lengkap & NIP Operator, badge peran `OPERATOR` (teal), indikator Tahun Anggaran Aktif (`DIPA 2026`, dinamis).
* **3 KPI Card Pribadi:**
  1. *Total Item Kegiatan Ditugaskan.*
  2. *Berkas Siap Cair (Approved).*
  3. *Berkas Butuh Revisi Segera (Rejected):* kartu merah dengan denyut halus; klik membuka daftar item REJECTED.
* **Banner Shortcut Kegiatan Prioritas** (dinamis, contoh `BMA.006 Sensus Ekonomi 2026`): akses 1-klik ke pos kegiatan tersebut.
* **Tabel Aktivitas Berkas Terkini:** 5 unggahan terakhir milik operator beserta status. Ada tautan "Lihat semua".
* **Empty state:** "Belum ada aktivitas. Mulai dari menu Arsip Keuangan." + tombol.

### Tampilan 4.2: Arsip Keuangan POK (`/arsip`)
* **Fungsi:** Direktori pencarian dan penjelajahan item kegiatan POK tempat berkas diunggah.
* **Filter:** Search bar (nama kegiatan/kode item, contoh `001366`), dropdown Output/Sub-Output (contoh `BMA.006`), filter status (*Semua, Belum Ada Berkas, Pending, Approved, Rejected*), tombol "Reset filter".
* **Tabel:** `Kode Item (badge monospace)`, `Nama Item Kegiatan`, `Mata Anggaran (Akun POK)`, `Pagu Anggaran (Rp)`, `Status Verifikasi`, `Jumlah Berkas`, `Aksi (Buka Workspace / Unggah)`. Pagination wajib.
* **Interaksi:** tombol Aksi menuju `/items/{id}`. Pada item APPROVED tombol berubah menjadi "Lihat Berkas" (ikon gembok).

### Tampilan 4.3: Detail Item & Workspace Berkas (`/items/{id}`)
* **Header Informasi POK:** Breadcrumb `Program > Output > Sub-Output > Komponen > Sub-Komponen > Akun > Item`, judul besar (`[001366] Honor Petugas Sensus Lapangan`), pagu (`Rp 925.600.000`), badge status.
* **Banner Penolakan (jika REJECTED):** merah mencolok: *"Catatan Perbaikan dari Bendahara: [alasan]"* + nama bendahara & waktu.
* **Formulir Unggah Multi-Upload:**
  * Dropzone *drag & drop* untuk PDF, JPG, JPEG, PNG (maks 15 MB/berkas), validasi format/ukuran dengan pesan error per berkas.
  * Dropdown kategori berkas: `Kuitansi Pembayaran`, `BAPP (Berita Acara Pembayaran)`, `Daftar Hadir / Nominatif Penerima`, `Surat Tugas / SK Petugas`, `Kerangka Acuan Kerja (KAK) / Laporan Kegiatan`, `Bukti Transfer / Dokumen Lainnya`.
  * Tombol **"Unggah Berkas Sekarang"** dengan progres per berkas.
  * *Guard 1:* form hilang/terkunci (tampilkan panel "Item sudah APPROVED, berkas terkunci") jika status APPROVED.
* **Tabel Berkas SPJ:** `No`, `Nama Berkas Asli`, `Label Kategori`, `Ukuran`, `Waktu Unggah`, `Pengunggah`, `Status Centang Verifikasi`, `Aksi`.
  * Aksi: **Pratinjau** (inline PDF/gambar), **Unduh**, **Hapus** (hanya untuk pemilik berkas dan hanya jika belum APPROVED, *Guard 4*; dengan dialog konfirmasi).
* **Timeline Jejak Aktivitas / Audit Log Item:** kronologis: unggah, centang, keputusan status, catatan penolakan.
* **Tampilan baca-saja:** untuk Supervisor, tombol unggah/hapus tidak ada. Untuk Bendahara, ada panel verifikasi (lihat 5.3) dan tombol Unduh ZIP.

### Tampilan 4.4: Laporan untuk Operator (`/reports`)
* Versi **baca-saja** dari layar Laporan (lihat 6.3): filter periode, kartu ringkasan, tabel rekap, tombol Export dan Cetak. Tanpa menu pengelolaan data.

---

# 5. AKTOR 2: BENDAHARA PENGELUARAN
> **Tanggung Jawab Utama:** Penjaga kepatuhan kas negara. Memverifikasi kelengkapan berkas SPJ satu per satu, mencentang checklist, lalu menerbitkan status *APPROVED* (Siap Cair) atau *REJECTED* (dengan Catatan Wajib).

---

### Tampilan 5.1: Dashboard Bendahara (`/dashboard`)
* **Header:** sambutan, badge peran `BENDAHARA` (violet), `DIPA 2026`.
* **4 KPI Card:** (1) Total Antrean Menunggu Verifikasi (Pending), (2) Total Dana Disetujui (Approved Pagu), (3) Total Berkas Ditolak (Rejected), (4) Total Anggaran POK Keseluruhan.
* **Akses Cepat:** tombol besar **"Buka Inbox Verifikasi Pencairan"** dengan badge jumlah antrean.
* **Daftar 5 antrean terlama (FIFO)** dengan tombol "Periksa".

### Tampilan 5.2: Inbox Verifikasi Pencairan SPJ (`/verification`) — *MENU UTAMA BENDAHARA*
* **Fungsi:** Pusat kerja verifikasi SPJ metode FIFO (First-In First-Out), item terlama di atas.
* **Tab Status:** `Semua Status` · `⏳ Menunggu Verifikasi (PENDING)` *(default)* · `✅ Disetujui Siap Cair (APPROVED)` · `❌ Ditolak / Revisi (REJECTED)`. Tiap tab menampilkan jumlah.
* **Search bar & filter** Sub-Output / Operator.
* **Tabel Antrean:** `Kode Item`, `Nama Kegiatan & Pos Akun POK`, `Pagu Anggaran (Rp)`, `Progres Checklist` (progress bar, contoh `3 / 3 Berkas (100%)`), `Status Item`, `Operator Pengunggah`, `Menunggu Sejak`, `Aksi: "Periksa Berkas & Verifikasi"`. Pagination wajib.
* **Drawer/Modal Pemeriksaan Detail (Interactive Review):**
  * **Kiri, Daftar Checklist Berkas SPJ:** checkbox per berkas, tombol Preview cepat (viewer PDF/gambar inline), info siapa yang mencentang dan kapan (real-time), indikator *"Semua berkas wajib dicentang sebelum tombol persetujuan aktif"* (*Guard 2*).
  * **Kanan, Panel Keputusan:**
    * **Tombol Hijau "Setujui Pencairan (APPROVE)":** aktif hanya jika berkas ≥ 1 dan checklist 100%; selain itu *disabled* dengan tooltip alasan. Ada dialog konfirmasi sebelum final (karena status terkunci permanen).
    * **Tombol Merah "Tolak Berkas (REJECT)":** membuka form **Catatan Penolakan** wajib (textarea, maks 500 karakter, penghitung karakter, tombol kirim nonaktif jika kosong).
    * *Guard 6:* jika Bendahara/Admin adalah pengunggah berkas tersebut, tombol Approve terkunci dengan pesan "Pengunggah tidak dapat menyetujui berkasnya sendiri".
* **State:** loading skeleton, antrean kosong ("Tidak ada SPJ yang menunggu. Kerja bagus!"), error.

### Tampilan 5.3: Detail Item untuk Bendahara (`/items/{id}`)
* Sama dengan 4.3 tetapi dengan panel verifikasi (checklist + keputusan) dan tombol **"Unduh Paket ZIP SPJ"**; tanpa form unggah.

### Tampilan 5.4: Unduh Paket ZIP SPJ (`/items/{id}/download-zip`)
* **Fungsi:** unduh seluruh berkas SPJ satu item menjadi 1 berkas `.zip` untuk diserahkan ke KPPN / arsip fisik.
* Penamaan otomatis: `SPJ_Item_[KODE]_[TIMESTAMP].zip`.
* **UI:** tombol dengan state *menyiapkan berkas…* (spinner), *selesai* (toast sukses), *gagal* (toast error + coba lagi). Tampilkan estimasi ukuran dan jumlah berkas di tombol/tooltip.

### Tampilan 5.5: Laporan untuk Bendahara (`/reports`)
* Versi baca-saja dari 6.3 (lihat/cetak/ekspor).

---

# 6. AKTOR 3: SUPERVISOR / PJ KEGIATAN
> **Tanggung Jawab Utama:** PPK / Kepala Seksi / Pimpinan Kegiatan. Mendaftarkan dan memelihara struktur POK DIPA resmi, memantau realisasi daya serap anggaran, dan mengunduh laporan rekapitulasi.

---

### Tampilan 6.1: Dashboard Eksekutif Supervisor (`/dashboard`)
* **Header Eksekutif**, badge `SUPERVISOR` (sky), `DIPA 2026`.
* **Widget KPI Realisasi:** Total Pagu DIPA, Pagu Approved, Pagu Pending, Pagu Rejected, Sisa Pagu.
* **Gauge / Progress:** persentase daya serap anggaran keseluruhan.
* **Grafik:** serapan per Sub-Output (bar) dan tren Approved per bulan (line). Beri legenda dan tooltip nominal.
* **Sorotan Kegiatan Prioritas** (dinamis, contoh BMA.006 Sensus Ekonomi): statistik capaiannya.

### Tampilan 6.2: Kelola Master Data POK (`/master`) — *MENU UTAMA SUPERVISOR*
* **Fungsi:** Manajemen hirarki POK DIPA BPS (8 level: Tahun Anggaran → Program → Output → Sub-Output → Komponen → Sub-Komponen → Akun → Item). Level **Program** bersifat data tetap yang ditampilkan sebagai kolom/filter induk (read-only), sedangkan 7 level lainnya dikelola lewat tab.
* **Struktur 7 Tab:**
  1. **`📋 Item Kegiatan` (Level 8):**
     * Tabel: Kode (6 digit), Nama Kegiatan, Pos Akun, Pagu (Rp), Edit, Hapus. Search + pagination.
     * **Form Tambah/Edit (panel kanan sticky):**
       * *Searchable Dropdown Akun POK* (ketik `521213` atau `honor`).
       * *Kode Item:* teks monospace 6 digit, wajib unik (*Guard 7*, error inline "Kode sudah dipakai").
       * *Nama Item Kegiatan.*
       * *Pagu Anggaran (Rp):* **live masking** titik ribuan (ketik `1000000` tampil `Rp 1.000.000`).
  2. **`💳 Akun` (Level 7):** daftar kode akun 6 digit (521211, 521213, 524113, dst.); form tambah akun terhubung ke Sub-Komponen induk.
  3. **`🔷 Sub-Komponen` (Level 6):** kode (051.0A, 052.0B).
  4. **`🔶 Komponen` (Level 5):** kode (051, 052, 530).
  5. **`📦 Sub-Output` (Level 4):** kode (BMA.006, BMA.004, FAN.ZZ1).
  6. **`📁 Output` (Level 3):** kelompok output Kemenkeu (BMA, FAN).
  7. **`📅 Tahun Anggaran` (Level 1):** pengaturan tahun DIPA aktif (2026, 2027) dan toggle status aktif (dengan konfirmasi karena memengaruhi seluruh sistem).
     * **Fitur Utama: Salin Struktur POK ke Tahun Baru (Rollover POK 1-Klik):** Memungkinkan Supervisor/Admin menduplikasi secara instan seluruh struktur 8-level (Program → Output → Sub-Output → Komponen → Sub-Komponen → Akun → Item) dari tahun berjalan ke tahun anggaran baru (misal 2026 ke 2027). Dilengkapi opsi menyalin nominal pagu acuan dan otomatisasi dokumen SPJ bersih (*fresh start* tanpa berkas lampau).
     * Form tambah tahun anggaran manual secara independen.
* **Smart Banner Pengingat Tahun Baru (Dashboard):** Banner otomatis yang muncul di Dashboard bagi Supervisor dan Admin ketika kalender memasuki tahun baru (namun DIPA aktif masih tahun lampau) atau ketika memasuki Q4 persiapan DIPA baru, dilengkapi tombol aksi langsung membuka modal rollover POK 1-klik.
* **Pola semua tab:** tabel + form panel kanan, validasi inline, anti-duplikasi kode (*Guard 7*), dialog konfirmasi hapus. **Hapus dinonaktifkan** (dengan tooltip) jika entri masih punya turunan/berkas.

### Tampilan 6.3: Laporan & Rekapitulasi (`/reports`) — *MENU UTAMA SUPERVISOR*
* **Filter Periode:** Tahun Anggaran, Bulan (Jan-Des), Minggu ke- (1: tgl 1-7, 2: tgl 8-14, dst.).
* **Kartu Ringkasan:** Total Item, Total Pagu Terdaftar, Pagu Approved, Pagu Pending, Pagu Rejected, Persentase Serapan (%).
* **Tabel Rekapitulasi Hierarkis:** `No`, `Kode Sub-Output`, `Nama Sub-Output`, `Program / Output`, `Total Item`, `Total Pagu (Rp)`, `Disetujui (Rp)`, `Pending`, `Revisi`, `% Realisasi`; baris total di bawah. Pagination wajib.
* **Ekspor & Cetak:**
  * **"Export ke Excel (.xlsx / .csv)":** spreadsheet resmi dengan format mata uang rapi.
  * **"Cetak Laporan Resmi":** *Print-Friendly View* bersih (tanpa sidebar/navbar), lengkap dengan **Kop Surat Resmi BPS Kabupaten Subang**, nama sistem lengkap (sesuai Bagian 0), periode, tanggal cetak, dan kolom tanda tangan PPK. Sertakan desain halaman cetak A4 portrait/landscape.

### Tampilan 6.4: Arsip & Detail Item untuk Supervisor
* `/arsip` dan `/items/{id}` dalam mode **audit/baca-saja**: tanpa tombol unggah, hapus, atau verifikasi; pratinjau dan unduh tetap ada.

---

# 7. AKTOR 4: ADMINISTRATOR (ADMIN)
> **Tanggung Jawab Utama:** Pengelola teknis tertinggi. Memiliki seluruh hak akses Supervisor, Bendahara, dan Operator, ditambah wewenang mutlak mengelola akun pengguna dan audit log sistem.

---

### Tampilan 7.1: Dashboard Admin (`/dashboard`)
* Badge peran `ADMIN` (slate), `DIPA 2026`.
* **KPI Sistem:** status server (indikator hijau/merah), total pengguna aktif, total arsip tersimpan, kapasitas penyimpanan (progress bar + peringatan di ≥ 80%), ringkasan serapan DIPA.
* **Ringkasan per peran** (jumlah pengguna tiap peran) dan **5 aktivitas terakhir** dari audit log.
* **Pintasan:** Tambah Pengguna, Inbox Verifikasi, Master Data.

### Tampilan 7.2: Manajemen Pengguna (`/users`) — *MENU KHUSUS ADMIN*
* **Fungsi:** Tata kelola RBAC untuk seluruh pegawai dan mitra BPS.
* **4 Mini Card Peran:** `Administrator`, `Supervisor / PJ`, `Operator Input`, `Bendahara Pengeluaran` (jumlah + warna peran dari 1.2).
* **Form Registrasi Pengguna Baru:** NIP / Username (unik; 18 digit NIP BPS atau username staf), Nama Lengkap, Dropdown Role (`ADMIN`, `SUPERVISOR`, `OPERATOR`, `BENDAHARA`), Password + Konfirmasi (indikator kekuatan sandi), tombol **"Tambah Pengguna"**.
* **Tabel Pengguna:** `NIP / Username`, `Nama Pegawai`, `Peran (badge)`, `Tanggal Terdaftar`, `Aksi`. Search, filter peran, pagination.
* **Aksi per baris:**
  * **Modal Edit Data:** ubah nama atau peran.
  * **Modal Reset Password Cepat.**
  * **Hapus Pengguna:** dialog konfirmasi; *Guard*: Admin tidak bisa menghapus akunnya sendiri (tombol nonaktif + tooltip). Beri peringatan jika pengguna masih memiliki berkas terunggah.

### Tampilan 7.3: Audit Log Sistem (`/audit-log`) — *MENU KHUSUS ADMIN*
* **Fungsi:** Jejak seluruh aktivitas penting sistem (baca-saja, tidak bisa diubah/dihapus).
* **Filter:** rentang tanggal, pengguna, jenis aksi (Login, Unggah, Hapus, Centang, Approve, Reject, Ubah Master, Kelola Pengguna), search.
* **Tabel:** `Waktu`, `Pengguna (+ badge peran)`, `Aksi`, `Objek (kode item/pengguna)`, `Detail ringkas`, `Alamat IP`. Pagination wajib + tombol Export CSV.

### Tampilan 7.4: Layar Admin Lainnya
* Admin juga melihat `/verification`, `/master`, `/arsip`, `/items/{id}`, `/reports` dengan hak penuh (sama seperti layar Bendahara/Supervisor/Operator, plus badge `ADMIN` di header).

---

# 8. HALAMAN PUBLIK & KOMPONEN GLOBAL

### 8.1 Halaman Login (`/login`)
* **Branding:** Logo Resmi BPS + judul nama sistem lengkap sesuai Bagian 0.
* **Form:** Input NIP / Username (ikon user), Password (toggle mata Show/Hide), checkbox *Ingat Saya*, tombol **"Masuk ke Sistem"** (Biru BPS).
* **State:** loading pada tombol, error kredensial ("NIP/Username atau kata sandi salah"), akun terkunci/nonaktif, sesi berakhir ("Sesi Anda telah berakhir, silakan masuk kembali").
* **Footer Keamanan:** keterangan enkripsi sistem keuangan & hak cipta BPS Kabupaten Subang.
* Layout: split-screen di desktop (panel branding navy + form), satu kolom di mobile.

### 8.2 Header / Topbar Global
* **Kiri:** Logo BPS Subang, nama sistem lengkap, hamburger (mobile).
* **Kanan:**
  * Pill `DIPA 2026` (dinamis).
  * Ikon notifikasi dengan badge jumlah (contoh: operator menerima "Berkas ditolak", bendahara menerima "SPJ baru masuk"), dropdown daftar notifikasi.
  * Profil: Nama Lengkap + badge peran (warna dari 1.2); dropdown berisi `Profil Akun` dan `Keluar`.
  * Tombol **Keluar (Logout)** dengan dialog konfirmasi.

### 8.3 Sidebar Navigasi Responsif
* Menu dinamis sesuai peran (urutan di Bagian 2); tidak berhak = tersembunyi.
* Item aktif: latar kontras + pill penanda. Sidebar bisa di-*collapse* menjadi ikon.
* Area Pintasan di bawah: tombol cepat ke pos kegiatan prioritas (dinamis).

### 8.4 Profil Akun (`/profile`) — semua peran
* **Informasi akun:** Nama Lengkap, NIP/Username, badge peran, tanggal terdaftar (baca-saja).
* **Ubah Kata Sandi:** sandi lama, sandi baru, konfirmasi, indikator kekuatan, tombol simpan.
* **Aktivitas login terakhir** (opsional ringkas).

### 8.5 Halaman Sistem & State Global
* **403 Akses Ditolak:** ilustrasi gembok, pesan "Anda tidak memiliki akses ke halaman ini", tombol "Kembali ke Dashboard".
* **404 Halaman Tidak Ditemukan** dan **500 Terjadi Kesalahan** dengan tombol coba lagi.
* **Sesi berakhir (timeout)** sebagai modal.
* **Pola global:** skeleton loading di semua tabel/kartu, empty state beraksi (ilustrasi + teks + tombol), toast sukses/error/info, dialog konfirmasi untuk semua aksi destruktif atau permanen.

---

# 9. 7 ATURAN LOGIKA PENGAMANAN KEUANGAN (FINANCIAL GUARDS)
> **PENTING UNTUK DESAINER:** Setiap antarmuka harus merefleksikan 7 guard berikut dalam bentuk status tombol, kunci akses (*disabled state* + ikon gembok + tooltip alasan), dan alert peringatan:

1. **[GUARD 1] Lock on APPROVED:** Item `APPROVED` → form unggah dan tombol hapus berkas **terkunci** (tampilkan panel "Berkas terkunci karena sudah disetujui").
2. **[GUARD 2] Mandatory 100% Checklist before Approval:** Tombol "Setujui Pencairan" **nonaktif** jika berkas 0 atau checklist belum 100%.
3. **[GUARD 3] Storage Auto-Cleanup:** Saat berkas dihapus, file fisik di server ikut terhapus. *UI:* dialog konfirmasi hapus menyebut "Berkas akan dihapus permanen".
4. **[GUARD 4] Operator Document Ownership:** Tombol hapus hanya muncul pada berkas milik sendiri; berkas milik operator lain tampil tanpa tombol hapus.
5. **[GUARD 5] Checklist Auto-Reset on Rejection:** Setelah revisi diunggah, seluruh checklist lama kembali kosong. *UI:* tampilkan info "Checklist direset, mohon periksa ulang dari awal".
6. **[GUARD 6] Segregation of Duties (Prinsip 4 Mata):** Pengunggah dilarang menyetujui berkasnya sendiri; tombol Approve terkunci dengan pesan jelas.
7. **[GUARD 7] Validasi Anti-Duplikasi Kode POK:** Kode item, akun, dan sub-output wajib unik; tampilkan error inline saat input duplikat.

---

# 10. INVENTARIS LAYAR YANG WAJIB DIBUAT

Setiap baris = minimal satu layar desktop **dan** satu versi mobile.

| # | Layar | Aktor | Rute |
|---|---|---|---|
| 1 | Login (+ state error/sesi berakhir) | Publik | `/login` |
| 2 | Dashboard Operator | Operator | `/dashboard` |
| 3 | Dashboard Bendahara | Bendahara | `/dashboard` |
| 4 | Dashboard Eksekutif | Supervisor | `/dashboard` |
| 5 | Dashboard Admin | Admin | `/dashboard` |
| 6 | Arsip Keuangan POK (mode unggah / audit) | Semua | `/arsip` |
| 7 | Detail Item: mode Operator (upload) | Operator | `/items/{id}` |
| 8 | Detail Item: status REJECTED (banner catatan) | Operator | `/items/{id}` |
| 9 | Detail Item: status APPROVED (terkunci) | Semua | `/items/{id}` |
| 10 | Detail Item: mode Bendahara (verifikasi + ZIP) | Bendahara | `/items/{id}` |
| 11 | Detail Item: mode baca-saja | Supervisor | `/items/{id}` |
| 12 | Inbox Verifikasi (4 tab) | Bendahara/Admin | `/verification` |
| 13 | Modal/Drawer Pemeriksaan + form Tolak | Bendahara/Admin | `/verification` |
| 14 | Master Data POK (7 tab + form) | Supervisor/Admin | `/master` |
| 15 | Laporan & Rekapitulasi | Semua | `/reports` |
| 16 | Tampilan Cetak Laporan Resmi (kop + TTD PPK) | Semua | `/reports` |
| 17 | Manajemen Pengguna + modal Edit, Reset Password, Hapus | Admin | `/users` |
| 18 | Audit Log Sistem | Admin | `/audit-log` |
| 19 | Profil Akun + Ubah Kata Sandi | Semua | `/profile` |
| 20 | Halaman 403, 404, 500 | Semua | - |
| 21 | Dropdown notifikasi + dialog Logout | Semua | global |
| 22 | Library komponen (tombol, badge, tabel, form, state kosong/loading) | - | - |

---