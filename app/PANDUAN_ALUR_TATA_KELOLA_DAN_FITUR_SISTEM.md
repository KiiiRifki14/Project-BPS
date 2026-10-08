# 📘 PANDUAN TATA KELOLA, ALUR KERJA & FITUR SISTEM
## Sistem Data Digital Arsip Keuangan — BPS Kabupaten Subang

> **Status Dokumen:** Dokumen Resmi Tata Kelola Operasional & Arsitektur Fitur  
> **Sasaran Pembaca:** Dosen Penguji, Pimpinan BPS, Kepala Seksi/PJ Kegiatan, Bendahara Pengeluaran, dan Operator Unit Kerja.

---

## 🏛️ 1. PRINSIP DASAR TATA KELOLA ANGGARAN (GOVERNANCE)

### Mengapa Operator Harus Dibuatkan Kegiatan/POK Dulu oleh Admin/Supervisor?

Salah satu pertanyaan paling mendasar dalam tata kelola sistem ini adalah:  
**`"Apakah Operator bisa membuat nama kegiatan/POK sendiri saat mau unggah berkas?"`**  
Jawabannya adalah **TIDAK BISA**. Operator hanya bertugas mengunggah berkas pada Item POK yang sudah disiapkan oleh Admin / Supervisor.

Berikut 4 alasan fundamentalnya:

| No | Landasan Tata Kelola | Penjelasan & Dampak Positif |
| :-: | :--- | :--- |
| **1** | **Kepatuhan DIPA BPS (Regulasi APBN)** | Dokumen POK (*Petunjuk Operasional Kegiatan*) adalah turunan sah dari DIPA resmi BPS RI. Kode anggaran dan besaran pagunya sudah ditetapkan oleh Tim Perencana/Pimpinan di awal tahun anggaran dan tidak boleh diubah sembarangan di lapangan. |
| **2** | **Pencegahan Kegiatan Fiktif** | Jika Operator diizinkan membuat pos kegiatan secara bebas, terdapat celah di mana berkas dapat diunggah ke pos anggaran yang tidak resmi atau fiktif tanpa pengawasan Pejabat Pembuat Komitmen (PPK). |
| **3** | **Integritas Serapan Pagu Real-Time** | Setiap Item POK memiliki pagu dana yang pasti (misal: *Rp 925.600.000*). Pemusatan master data menjamin angka daya serap anggaran di Dashboard Eksekutif selalu akurat 100%. |
| **4** | **Pemisahan Wewenang (*Segregation of Duties*)** | Menjaga prinsip tata kelola keuangan negara yang baik (*Good Public Governance*): pihak yang **merencanakan/mengawasi anggaran** (Admin/Supervisor) dipisahkan dari pihak yang **melaksanakan administrasi berkas** (Operator). |

---

## 👥 2. MATRIKS PERAN & PEMBAGIAN HAK AKSES (RBAC MATRIX)

Sistem membagi wewenang ke dalam 4 peran (*Role*) yang terisolasi secara aman:

```
[ADMIN]         --> Mengelola Pengguna, Master POK DIPA, & Keamanan Sistem
[SUPERVISOR]    --> Pengawasan PJ Kegiatan, Pemantauan Pagu, & Ekspor Rekapitulasi
[OPERATOR]      --> Pelaksana Unit Kerja: Unggah Berkas SPJ/BAPP/Kuitansi & Perbaikan
[BENDAHARA]     --> Penjaga Kas Negara: Verifikasi Checklist Berkas & Persetujuan Pencairan
```

### Tabel Rincian Hak Akses Sistem:

| Role Pengguna | Personel / Aktor | Tanggung Jawab Operasional | Hak Master POK | Hak Unggah Berkas | Hak Verifikasi / Cair |
| :--- | :--- | :--- | :---: | :---: | :---: |
| 🔴 **`ADMIN`** | Administrator IT BPS | Manajemen akun staf, konfigurasi sistem, audit jejak aktivitas, dan verifikasi darurat. | ✅ Penuh | ✅ Semua Dokumen | ✅ (Dengan Guard 6) |
| 🔵 **`SUPERVISOR`** | PJ Kegiatan / Pimpinan BPS | Menyiapkan struktur POK DIPA, memantau daya serap anggaran kegiatan, dan mengunduh laporan. | ✅ Master POK | ✅ Semua Dokumen | ❌ |
| 🟢 **`OPERATOR`** | Tim Kerja / Staf Lapangan | Menelusuri item kegiatan, mengunggah berkas SPJ per label, dan memperbaiki berkas jika ada penolakan. | ❌ | ✅ Hanya Berkas Sendiri | ❌ |
| 🟡 **`BENDAHARA`** | Bendahara Pengeluaran | Memeriksa berkas via inline preview, mencentang checklist 100%, dan memutuskan *Approved* / *Rejected*. | ❌ | ❌ | ✅ Akses Utama |

---

## 🖥️ 3. PENJELASAN LENGKAP SETIAP MENU & FITUR APLIKASI

### 3.1 Dashboard Eksekutif (`/dashboard`)
Halaman pemantauan pimpinan dan supervisor untuk membaca kondisi keuangan secara instan:
* **Hero Banner Strategis:** Sorotan kegiatan prioritas utama tahun berjalan (contoh: *Sensus Ekonomi 2026*).
* **5 Kartu Indikator KPI Real-Time:**
  1. *Total Pagu Anggaran (Rp):* Total akumulasi seluruh item kegiatan di dalam POK aktif.
  2. *Total Pagu Approved (Rp):* Dana yang sudah sah diverifikasi oleh Bendahara (Siap Cair).
  3. *Total Pagu Pending (Rp):* Dana kegiatan yang sedang dalam proses verifikasi checklist.
  4. *Total Pagu Rejected (Rp):* Dana kegiatan yang berkasnya ditolak dan butuh perbaikan Operator.
  5. *Sisa Pagu Anggaran (Rp):* Anggaran yang belum diajukan berkas pertanggungjawabannya.
* **Grafik Persentase Daya Serap Anggaran:** Progress bar interaktif serapan anggaran per Sub-Output.
* **Tabel Rekapitulasi Sub-Output:** Rincian serapan dan status berkas per kelompok kegiatan.

---

### 3.2 Arsip Keuangan POK (`/items`)
Katalog digital seluruh pos kegiatan DIPA BPS:
* **Penelusuran Cepat & Filter:** Pencarian berdasarkan kode item, nama kegiatan, atau kelompok Sub-Output.
* **Badge Status Verifikasi Interaktif:**
  * 🟢 **`APPROVED`**: Berkas lengkap, disetujui Bendahara, dan terkunci total dari perubahan.
  * 🟡 **`PENDING`**: Berkas terunggah dan sedang dalam antrean verifikasi Bendahara.
  * 🔴 **`REJECTED`**: Berkas dikembalikan oleh Bendahara untuk diperbaiki Operator.
  * ⚪ **`BELUM ADA BERKAS`**: Pos kegiatan yang belum diunggah berkas SPJ-nya.
* **Tautan Workspace Detail:** Akses langsung ke ruang kerja berkas masing-masing item.

---

### 3.3 Workspace Detail Item Kegiatan (`/items/{item}`)
Ruang kerja utama interaksi antara Operator dan Bendahara:
* **Panel Informasi POK:** Menampilkan hierarki lengkap mata anggaran (Program $\rightarrow$ Output $\rightarrow$ Sub-Output $\rightarrow$ Komponen $\rightarrow$ Akun $\rightarrow$ Item $\rightarrow$ Pagu Dana).
* **Form Unggah Berkas Multi-File:**
  * Mendukung unggah banyak berkas sekaligus (*batch upload*).
  * Format yang didukung: **PDF, JPG, JPEG, PNG** (Maksimal 15 MB per file).
  * **Pilihan Label Kategori Wajib:**
    * *BAPP Honor Sensus*
    * *Kuitansi Pembayaran*
    * *Kerangka Acuan Kerja (KAK)*
    * *Surat Keputusan (SK) Petugas*
    * *Daftar Hadir / Daftar Penerima*
    * *SPJ Perjalanan Dinas*
    * *Dokumen Pendukung Lainnya*
* **Tabel Berkas Tersimpan:**
  * Menampilkan nama file asli, label kategori, ukuran berkas, identitas pengunggah, dan waktu unggah.
  * **Tombol Pratinjau Inline (`👁️`):** Membuka file PDF/Gambar langsung di modal browser tanpa harus mengunduh file ke memori komputer.
  * **Badge Catatan Revisi (`[ ✕ Perlu Revisi ℹ ]`):** Jika dokumen ditolak, muncul tombol interaktif yang menampilkan alasan penolakan secara jelas.
  * **Tombol Hapus Berkas (`🗑️`):** Dilindungi **Guard 4**, operator hanya dapat menghapus dokumen yang diunggah oleh dirinya sendiri.
* **Panel Verifikasi Bendahara (Sisi Kanan):**
  * Checklist interaktif per dokumen.
  * Indikator penghitung progres verifikasi (*contoh: 2/3 Dokumen Diverifikasi*).
  * Tombol **Setujui Pencairan (APPROVED)** terkunci otomatis jika checklist belum 100%.
  * Tombol **Tolak / Minta Revisi (REJECTED)** yang mewajibkan input alasan penolakan.

---

### 3.4 Inbox Verifikasi Pencairan (`/verification`)
Ruang kerja khusus Bendahara Pengeluaran:
* **Prinsip Antrean FIFO (*First-In, First-Out*):** Berkas yang paling awal diajukan atau mengendap paling lama otomatis ditempatkan di urutan teratas untuk menjamin keadilan pelayanan pencairan.
* **Badge Notifikasi Sidebar:** Angka pengingat jumlah item yang menunggu pemeriksaan secara *real-time*.

---

### 3.5 Kelola Master POK (`/master`)
Menu pemeliharaan struktur anggaran 8-Level DIPA (khusus Supervisor & Admin):
* **Tab Navigasi 7 Tingkat:**
  1. 📋 *Item Kegiatan* (Level 8)
  2. 💳 *Akun Belanja* (Level 7)
  3. 🔷 *Sub-Komponen* (Level 6)
  4. 🔶 *Komponen* (Level 5)
  5. 📦 *Sub-Output* (Level 4)
  6. 📁 *Output* (Level 3)
  7. 📅 *Tahun Anggaran* (Level 1)
* **Operasi CRUD Lengkap:** Tambah, edit nama/kode, dan hapus master data dengan proteksi integritas relasi database.

---

### 3.6 Laporan & Rekapitulasi (`/reports`)
Modul pelaporan pertanggungjawaban dan audit:
* **Filter Periode Dinamis:** Mendukung pemilahan laporan berdasarkan rentang Tahunan, Bulanan, maupun Mingguan.
* **Ringkasan Anggaran:** Rangkuman total serapan dana dan persentase realisasi.
* **Ekspor Spreadsheet (.CSV / Excel):** Menghasilkan data rekapitulasi tabular lengkap untuk kebutuhan analisis lanjutan.
* **Tampilan Ramah Cetak (*Print-Friendly View*):** Format halaman cetak resmi siap tandatangan pimpinan.

---

### 3.7 Manajemen Pengguna (`/users`)
Menu tata kelola hak akses staf BPS (khusus Admin):
* **Daftar Akun Pengguna:** Menampilkan NIP/Username, Nama Staf, dan Hak Akses (*Role*).
* **Fitur Tambah Pengguna Baru:** Registrasi staf baru dengan penetapan role RBAC.
* **Modal Edit & Reset Password:** Pembaruan nama, pergantian role, dan pengaturan ulang kata sandi secara aman tanpa mengganggu riwayat log audit.

---

## 🛡️ 4. LOGIKA PENGAMANAN SISTEM (6 GUARDS FINANSIAL)

Sistem ini diperkuat oleh 6 lapis aturan keamanan logika backend untuk mencegah kebocoran data dan manipulasi keuangan:

```
[GUARD 1] Lock on APPROVED       --> Item siap cair dikunci total (anti-ubah & anti-hapus)
[GUARD 2] 100% Mandatory Check   --> Bendahara dilarang approve jika dokumen belum dicek semua
[GUARD 3] Storage Auto-Cleanup   --> File fisik otomatis terhapus saat record DB dihapus
[GUARD 4] Operator Ownership     --> Operator dilarang menghapus berkas milik operator lain
[GUARD 5] Checklist Auto-Reset   --> Jika ada revisi, checklist diulang dari 0 agar diaudit ulang
[GUARD 6] Segregation of Duties  --> Prinsip 4 mata: Pengunggah dilarang meng-approve berkas sendiri
```

---

## 🔄 5. ALUR KERJA DOKUMEN DARI HULU KE HILIR (END-TO-END WORKFLOW)

```
1. PENETAPAN POS POK
   Admin / Supervisor mendaftarkan Kode Item & Pagu Anggaran resmi DIPA di Master POK.
   ↓
2. PELAKSANAAN & UNGGAH BERKAS
   Operator menyelesaikan kegiatan lapangan → Menelusuri Item di Arsip POK →
   Mengunggah berkas SPJ/BAPP/Kuitansi lengkap dengan label kategori.
   ↓
3. INBOX VERIFIKASI FIFO
   Berkas masuk antrean verifikasi Bendahara secara urut waktu pengajuan (FIFO).
   ↓
4. PEMERIKSAAN DOKUMEN
   Bendahara membuka Pratinjau Inline → Memeriksa keabsahan berkas satu per satu →
   Mencentang checkbox checklist tiap dokumen.
   ↓
5. KEPUTUSAN BENDAHARA
   ├── JIKA LENGKAP & BENAR:
   │   Bendahara klik "Setujui Pencairan" → Status berubah APPROVED →
   │   Item dan seluruh dokumen TERKUNCI PERMANEN (Siap Cair / Selesai).
   │
   └── JIKA ADA KEKURANGAN:
       Bendahara klik "Tolak / Minta Revisi" + Mengisi Catatan Revisi →
       Status berubah REJECTED → Muncul badge [ ✕ Perlu Revisi ℹ ] di berkas →
       Operator membaca catatan, mengunggah berkas baru → Status kembali PENDING.
```

---

## ❓ 6. TANYA-JAWAB KRITIS (FAQ AUDIT & PRESENTASI)

**Q1: "Apakah dokumen seperti BAPP dan kuitansi akan hilang saat pergantian bulan?"**  
> **Jawaban:** **Sama sekali tidak hilang.** Sistem ini adalah sistem arsip digital permanen untuk kebutuhan audit BPK dan Inspektorat. Data tersimpan permanen di database dan server privat. Pengelompokan data menggunakan Tahun Anggaran, dan pergantian bulan tidak menghapus berkas.

**Q2: "Di mana berkas disimpan dan apakah aman dari akses publik?"**  
> **Jawaban:** Seluruh berkas disimpan di folder privat server (`storage/app/private/uploads/...`) dan **bukan** di folder publik. File hanya bisa diakses lewat controller streaming yang memvalidasi sesi login dan hak akses pengguna.

**Q3: "Mengapa sistem tidak meng-generate berkas fisik secara otomatis?"**  
> **Jawaban:** Sesuai batasan PRD (*Out of Scope*), dokumen fisik pertanggungjawaban keuangan (BAPP, kuitansi, SK) sah secara hukum apabila ditandatangani basah oleh pejabat terkait di luar sistem. Sistem ini berfokus mendigitalkan hasil scan berkas fisik yang sah sebagai syarat mutlak sebelum dana dicairkan oleh Bendahara.

---

*Dokumen disusun sebagai panduan resmi arsitektur dan tata kelola Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang.*
