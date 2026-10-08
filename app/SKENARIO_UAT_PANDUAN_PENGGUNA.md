# 📑 Panduan Presentasi & Skenario Pengujian UAT
## Sistem Data Digital Arsip Keuangan — BPS Kabupaten Subang

> **Tujuan Dokumen:**  
> Panduan terstruktur untuk memandu jalannya presentasi, demonstrasi langsung (*live demo*), dan *User Acceptance Testing* (UAT) di hadapan pimpinan BPS Kabupaten Subang, dosen penguji, dan stakeholder terkait.

---

## 🔑 1. Kredensial Akun Uji Coba (Demo Credentials)

Gunakan kredensial resmi berikut untuk masuk ke sistem di `http://127.0.0.1:8000/login`:

| Peran (Role) | Username (NIP) | Password | Nama Akun / Personel | Wewenang Utama |
| :--- | :--- | :--- | :--- | :--- |
| **ADMIN** | `admin` | `admin123` | Administrator BPS | Kelola Pengguna, Master POK, & Akses Sistem |
| **OPERATOR** | `operator` | `oper123` | Pak Didin (Operator Input) | Unggah SPJ/BAPP/Kuitansi & Perbaikan Revisi |
| **BENDAHARA** | `bendahara` | `bend123` | Pak Ahmad (Bendahara Pengeluaran) | Pratinjau Berkas, Checklist, & Eksekusi Pencairan |
| **SUPERVISOR** | `supervisor` | `super123` | Supervisor / PJ Kegiatan / Pimpinan | Monitoring KPI, Daya Serap Pagu, & Ekspor Laporan |

> 💡 **Tips Presenter:** Seluruh password menggunakan format `[role]123` yang mudah diingat saat mendemokan perpindahan antar-aktor.

---

## 🎯 2. Peta Item POK untuk Demo Live

Gunakan item-item berikut saat demo agar skenario berjalan mulus:

| Kode Item | Nama Item Kegiatan | Status Awal | Skenario yang Didemokan |
| :--- | :--- | :--- | :--- |
| **`001510`** | Honor pemeriksa lapangan sensus (UB-organik) | `PENDING` | Demo verifikasi Bendahara: Pratinjau berkas inline & centang checklist hingga tombol aktif. |
| **`000700`** | Jasa percetakan materi publisitas SE2026 | `REJECTED` | Demo fitur catatan revisi: Tunjukkan badge `[ ✕ Perlu Revisi ℹ ]` dan alur unggah perbaikan Operator. |
| **`001366`** | Honor petugas pendataan sensus ekonomi BPS Kab/Kota | `APPROVED` | Demo proteksi arsip siap cair: Tunjukkan bahwa berkas terkunci total (*LOCKED*) demi kepatuhan audit. |

---

## 🎬 3. Alur Cerita Presentasi (Live Demo Storyline)

Urutan demonstrasi terbaik dimulai dari **Operator (Pengajuan)** $\rightarrow$ **Bendahara (Verifikasi)** $\rightarrow$ **Supervisor (Monitoring)** $\rightarrow$ **Admin (Keamanan & Tata Kelola)**.

```
[1. OPERATOR]              [2. BENDAHARA]              [3. SUPERVISOR]             [4. ADMIN]
Unggah Berkas SPJ    -->   Pratinjau & Checklist  -->  Monitoring KPI Pagu   -->   Manajemen User &
Sesuai Label POK           Verifikasi Pencairan        & Ekspor Rekapitulasi       Audit Kepatuhan
```

---

### Skenario A: Operator — Pengunggahan & Perbaikan Berkas SPJ
*Aktor: `operator` / `oper123`*

1. **Navigasi Hirarki POK:**
   - Masuk ke menu **Arsip Keuangan POK** (`/items`).
   - Tunjukkan struktur anggaran POK yang dinamis dan terstruktur (Program GG.2902 $\rightarrow$ Output BMA $\rightarrow$ Sub-Output BMA.006 $\rightarrow$ Akun $\rightarrow$ Item).
   - Buka item **`001510`** (Honor pemeriksa lapangan sensus).
2. **Multi-File Upload dengan Label Kategori:**
   - Di Workspace Detail Dokumen, unggah berkas SPJ (PDF atau Gambar).
   - Pilih label kategori yang jelas (misal: *BAPP Honor Sensus*, *Kuitansi Pembayaran*, *Daftar Hadir*).
   - Klik tombol **Unggah Dokumen** $\rightarrow$ File tersimpan aman di disk privat server dengan enkripsi UUID.
3. **Uji Perlindungan Kepemilikan Dokumen (Guard 4):**
   - Operator hanya memiliki wewenang untuk menghapus dokumen yang diunggah oleh dirinya sendiri. Berkas milik operator lain terlindungi dari manipulasi.
4. **Melihat Catatan Penolakan (Rejection Note):**
   - Buka item **`000700`** (Status `REJECTED`).
   - Tunjukkan badge interaktif **`[ ✕ Perlu Revisi ℹ ]`** di baris dokumen.
   - Klik badge tersebut untuk melihat alasan spesifik penolakan dari Bendahara (contoh: *"Kuitansi belum bertanda tangan basah"*).
   - Unggah berkas pengganti $\rightarrow$ Status item otomatis kembali ke `PENDING` dan seluruh checklist direset bersih (**Guard 5**).

---

### Skenario B: Bendahara — Pratinjau & Verifikasi Pencairan
*Aktor: `bendahara` / `bend123`*

1. **Inbox Verifikasi & Indikator Antrean:**
   - Perhatikan badge notifikasi angka antrean pada menu **Verifikasi Pencairan** (`/verification`).
   - Daftar item diurutkan berdasarkan dokumen yang paling awal masuk (*FIFO Rule*).
2. **Pratinjau Inline Tanpa Download:**
   - Klik item **`001510`**.
   - Klik tombol **Pratinjau** pada dokumen $\rightarrow$ Berkas PDF/Gambar langsung tampil di dalam modal browser tanpa perlu mengunduh ke komputer lokal.
3. **Interactive Verification Checklist (Guard 2):**
   - Tunjukkan bahwa tombol **Setujui Pencairan (Siap Cair)** dalam keadaan *disabled* (tidak dapat diklik).
   - Centang checkbox *"Sudah diperiksa"* pada tiap berkas secara bertahap.
   - Perhatikan indikator progres dinamis (contoh: *1/3 Dokumen*, *2/3 Dokumen*).
   - Begitu seluruh dokumen tercentang (3/3), tombol **Setujui Pencairan** otomatis aktif dan berubah warna.
4. **Keputusan Verifikasi:**
   - **Jika Disetujui (`APPROVED`):** Item berubah status menjadi hijau (*Siap Cair*). Berkas otomatis terkunci total (**Guard 1A & 1B**).
   - **Jika Ditolak (`REJECTED`):** Bendahara wajib memasukkan catatan revisi agar Operator memahami perbaikan yang harus dilakukan.

---

### Skenario C: Supervisor / Pimpinan — Monitoring & Rekapitulasi
*Aktor: `supervisor` / `super123`*

1. **Executive Dashboard (`/dashboard`):**
   - Pantau 5 Kartu Metrik Utama: *Total Pagu Anggaran*, *Pencairan Disetujui (Approved)*, *Menunggu Verifikasi (Pending)*, *Perlu Revisi (Rejected)*, dan *Sisa Pagu*.
   - Tunjukkan grafik persentase daya serap anggaran kegiatan BPS Subang secara visual dan informatif.
2. **Penyaringan Periode Laporan (`/reports`):**
   - Buka menu **Laporan & Rekapitulasi**.
   - Tunjukkan fleksibilitas filter laporan: *Filter Tahunan*, *Filter Bulanan*, atau *Filter Mingguan*.
3. **Ekspor Data:**
   - Klik **Export ke Excel (.csv)** untuk menghasilkan rekap data mentah yang siap diolah lebih lanjut.
   - Tunjukkan tombol **Cetak Laporan** untuk tampilan ramah cetak dokumen resmi BPS.

---

### Skenario D: Admin — Keamanan Sistem & Pemisahan Wewenang
*Aktor: `admin` / `admin123`*

1. **Manajemen Pengguna (`/users`):**
   - Buka menu **Manajemen Pengguna**.
   - Tambah atau kelola data akun staf BPS lengkap dengan pembagian Role (Admin, Supervisor, Operator, Bendahara).
2. **Prinsip Empat Mata (Guard 6 — Segregation of Duties):**
   - Jelaskan bahwa staf yang mengunggah dokumen pertanggungjawaban dilarang keras menyetujui/memverifikasi pencairannya sendiri.
   - Sistem akan memblokir secara otomatis untuk menjaga integritas audit keuangan.
3. **Jejak Audit Aktivitas (Audit Trail):**
   - Setiap tindakan login, unggah berkas, centang checklist, persetujuan, dan penolakan tercatat permanen dengan cap waktu (*timestamp*) dan identitas pengguna.

---

## 🛡️ 4. Ringkasan 6 Guard Proteksi Finansial BPS

Saat sesi tanya-jawab, jelaskan 6 lapis keamanan logika bisnis yang telah dibangun:

| No | Guard Keamanan | Lokasi Proteksi | Mekanisme & Dampak Positif |
| :--: | :--- | :--- | :--- |
| **1A & 1B** | **Lock on APPROVED** | `DocumentController` | Item yang sudah disetujui terkunci total; berkas tidak dapat ditambah atau dihapus lagi. |
| **2** | **Mandatory Checklist** | `ItemController@verify` | Bendahara tidak bisa menyetujui pencairan jika dokumen masih 0 atau ada dokumen yang belum dicentang. |
| **3** | **Storage Auto-Cleanup** | `Document::booted()` | File fisik di server otomatis terhapus jika record dokumen dihapus (mencegah file sampah mengendap). |
| **4** | **Operator Ownership** | `DocumentController@destroy` | Operator hanya boleh menghapus dokumen miliknya sendiri (mencegah salah hapus berkas rekan). |
| **5** | **Checklist Auto-Reset** | Controller Item & Doc | Jika item ditolak atau diunggah ulang, checklist kembali ke 0 agar Bendahara memeriksa ulang berkas baru secara menyeluruh. |
| **6** | **Segregation of Duties** | `ItemController@verify` | Prinsip empat mata audit: Pengunggah berkas dilarang menyetujui pencairannya sendiri. |

---

## 💬 5. Antisipasi Pertanyaan Penguji / Stakeholder (FAQ)

**Q1: "Apakah dokumen seperti BAPP dan kuitansi akan hilang jika berganti bulan?"**  
> **Jawaban:** *Tidak akan hilang sama sekali. Sistem ini adalah arsip digital permanen untuk kebutuhan audit BPK dan Inspektorat. Data dikelompokkan berdasarkan Tahun Anggaran, dan pergantian bulan/tahun tidak menghapus arsip fisik maupun database.*

**Q2: "Bagaimana jika ada dokumen rahasia keuangan yang bocor?"**  
> **Jawaban:** *Semua dokumen disimpan di direktori privat server (`storage/app/private`), bukan di folder publik. Berkas hanya dapat diakses melalui token autentikasi sesi aktif pengguna BPS dengan hak akses yang terverifikasi.*

**Q3: "Apakah sistem ini membuat dokumen fisik BAPP secara otomatis?"**  
> **Jawaban:** *Sesuai batasan PRD (Out of Scope), pembuatan dan penandatanganan fisik dokumen tetap dilakukan secara resmi di luar sistem. Sistem ini berfokus sebagai wadah arsip digital hasil scan yang mengunci kelengkapan syarat pencairan dana kegiatan.*

---

*Dokumen panduan ini telah disinkronkan dengan implementasi sistem terbaru BPS Kabupaten Subang.*
