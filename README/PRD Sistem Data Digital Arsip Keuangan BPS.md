# **Product Requirements Document (PRD)**

## **Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang**

## **1\. Kontrol Dokumen (Document Control)**

* **Nama Proyek:** Sistem Data Digital Arsip Keuangan BPS (Kabupaten Subang)  
* **Penulis (Author):** Tim Pengembang PKL / Product Manager  
* **Versi:** v1.4 (Full Scope Including FAN.ZZ1 and 524114 Boundary)  
* **Tanggal Terakhir Diperbarui:** 10 Agustus 2026  
* **Pemangku Kepentingan (Stakeholders):**  
  * Petinggi BPS Kabupaten Subang (Sponsor & Approver Utama)  
  * Bendahara Pengeluaran BPS Subang (Key User & Validator Pencairan)  
  * Tim Developer / AI Coding Assistant

## **2\. Ringkasan Eksekutif & Latar Belakang (Executive Summary)**

### **Visi Produk**

Mewujudkan tata kelola administrasi keuangan BPS Kabupaten Subang yang transparan, terstruktur, akuntabel, dan efisien melalui sistem digitalisasi arsip pertanggungjawaban keuangan yang terintegrasi secara dinamis.

### **Masalah Utama**

1. **Pencarian Dokumen Fisik Lambat:** Proses pencairan dana dan verifikasi berkas pertanggungjawaban (SPJ, SPM, BAPP, Honor) masih bergantung pada dokumen fisik (*hardcopy*), yang berisiko hilang, rusak, atau memakan waktu lama saat penelusuran.  
2. **Keterlambatan Syarat Pencairan:** Bendahara membutuhkan kepastian kelengkapan dokumen pendukung sebelum dapat mencairkan dana kegiatan.  
3. **Restrukturisasi Anggaran Berulang:** Struktur anggaran POK (Petunjuk Operasional Kegiatan) BPS berubah setiap tahun anggaran (misal 2026, 2027), sehingga sistem kaku (*hardcoded*) akan cepat usang.  
4. **Penggabungan File Manual:** Pengguna sering mengalami kesulitan jika harus menggabungkan (*merge*) file PDF secara manual sebelum diunggah ke sistem.

### **Solusi**

Membangun web-app **Sistem Data Digital Arsip Keuangan BPS** yang mampu memetakan hirarki POK BPS secara terstruktur (Program ![][image1] Output ![][image1] Sub-Output ![][image1] Komponen ![][image1] Sub-Komponen ![][image1] Akun ![][image1] Item Detail), mendukung *multi-file upload* per item tanpa penggabungan manual, fleksibel diatur pengguna (*dynamic master data*), serta menyediakan pratinjau dokumen (*inline preview*) untuk verifikasi cepat oleh Bendahara.

### **Metrik Keberhasilan (KPI)**

* **Kecepatan Verifikasi:** Memangkas waktu verifikasi berkas pertanggungjawaban oleh Bendahara dari rata-rata 2 hari menjadi ![][image2] menit per kegiatan.  
* **Akurasi Kelengkapan Berkas:** 100% berkas pencairan honor Sensus Ekonomi terunggah lengkap sebelum pencairan dana disetujui.  
* **Efisiensi Beban Pengguna:** Eliminasi kebutuhan alat *PDF merger* terpisah (![][image3] proses *merge* manual di pihak *user*).

## **3\. Batasan Ruang Lingkup (Scope & Phasing)**

### **Fase 1 (MVP \- Minimum Viable Product / Fokus Utama)**

* **Arsitektur Generik 17 Program:** Sistem siap menampung struktur 17 program BPS, namun **penguncian alur dan pengujian fokus penuh pada 1 alur utama**.  
* **Cakupan Alur Induk:** Dari GG.2902 (*Penyediaan dan Pengembangan Statistik Distribusi*) mencakup cabang BMA Data dan Informasi Publik hingga batas akhir akun 524114 (*Belanja Perjalanan Dinas Paket Meeting Dalam Kota*) di bawah sub-output FAN.ZZ1 (*Pemenuhan Prioritas Direktif Presiden*).  
* **Fokus Menu Utama (Core Priority):** Modul **BMA.006 PUBLIKASI/LAPORAN SENSUS EKONOMI** (khususnya pencairan honor pendataan sensus 001366, 001211, 001510, serta dokumen BAPP & SPJ pendukung).  
* **Manajemen Multi-File Upload:** Upload beberapa file PDF/Gambar terpisah pada 1 item kegiatan.  
* **RBAC (Role-Based Access Control):** Pengisian hak akses untuk Operator, Supervisor, Bendahara, dan Admin.  
* **Master Data Dinamis:** Penambahan/pengeditan struktur POK oleh Supervisor untuk fleksibilitas tahun anggaran mendatang.

### **Fase 2 (Pengembangan Mendatang / Post-MVP)**

* Pemetaan penuh pengunggahan data secara aktif untuk 16 program BPS lainnya.  
* Notifikasi otomatis via Email/WhatsApp jika berkas ditolak atau disetujui Bendahara.  
* Fitur OCR (*Optical Character Recognition*) untuk pembacaan otomatis nilai nominal kuitansi/SPM.

### **Di Luar Batasan (Out of Scope)**

* Integrasi langsung (*API sync*) dengan aplikasi nasional kementerian keuangan seperti SAKTI atau SPAN (Sistem ini murni sebagai arsip digital internal BPS Subang).  
* Transaksi perbankan otomatis (Pencairan tetap dilakukan secara manual oleh Bendahara setelah status dokumen diverifikasi di aplikasi Sistem Data Digital Arsip Keuangan BPS).

## **4\. Analisis Pengguna & Persona (User Persona)**

| Persona | Role | Kebutuhan Utama | Tantangan |
| :---- | :---- | :---- | :---- |
| **Pak Didin** | Operator / Petugas Input | Mengunggah bukti kuitansi, SPJ, dan BAPP honor petugas sensus dengan cepat tanpa ribet *merge* PDF. | Sering memiliki banyak potongan scan PDF terpisah untuk 1 item kegiatan. |
| **Bu Rina** | Supervisor / PJ Kegiatan | Mengelola struktur POK jika ada revisi DIPA/POK dan mengecek kelengkapan tim. | Struktur anggaran sering berubah di tengah tahun berjalan. |
| **Pak Ahmad** | Bendahara Pengeluaran | Melihat pratinjau (*preview*) dokumen sebelum mencairkan honor/dana kegiatan. | Takut salah cair jika dokumen pertanggungjawaban fisik belum lengkap. |
| **Admin** | System Administrator | Mengelola akun pengguna, mengatur reset password, dan melakukan pemeliharaan database. | Memastikan keamanan akses sistem. |

## **5\. Kebutuhan Fungsional & Kriteria Penerimaan (Functional Specs)**

### **Modul A: Autentikasi & Manajemen Pengguna (RBAC)**

* **Deskripsi:** Mengamankan akses aplikasi berdasarkan peran masing-masing pengguna.  
* **Business Rules:**  
  * Pengguna wajib login dengan Username/NIP dan Password.  
  * Role terbagi menjadi 4: Admin, Supervisor, Operator, Bendahara.  
* **User Stories & Acceptance Criteria:**  
  * *US-01:* Sebagai pengguna, saya ingin login agar dapat mengakses menu sesuai wewenang saya.  
  * *Kriteria Penerimaan:*  
    * Operator hanya melihat form upload dan daftar arsipnya.  
    * Supervisor dapat menambah/mengedit master data POK.  
    * Bendahara melihat opsi validasi/persetujuan pencairan.

### **Modul B: Pengelolaan Master Data POK Dinamis**

* **Deskripsi:** Mengelola hirarki POK anggaran BPS tanpa perlu mengubah kode pemrograman (*no hardcoding*).  
* **Business Rules:**  
  * Hirarki data wajib berurutan: Program ![][image1] Output ![][image1] SubOutput (BMA/FAN) ![][image1] Komponen ![][image1] SubKomponen ![][image1] Akun (52xxxx) ![][image1] Item Detail.  
  * Item dapat diaktifkan/dinonaktifkan per Tahun Anggaran (misal: 2026, 2027).  
* **User Stories & Acceptance Criteria:**  
  * *US-02:* Sebagai Supervisor, saya ingin menambah item kegiatan baru pada kode BMA.006 atau FAN.ZZ1 jika ada revisi anggaran.  
  * *Kriteria Penerimaan:*  
    * Sistem menyediakan form penambahan Item di bawah Akun tertentu (contoh: Akun 524114).  
    * Data baru langsung muncul di pohon hirarki (*treeview*) navigasi aplikasi.

### **Modul C: Pengunggahan Dokumen Multi-File (Focus: BMA.006 Sensus Ekonomi)**

* **Deskripsi:** Mengunggah berkas pertanggungjawaban pada item spesifik (seperti item 001366 Honor Petugas Pendataan Sensus).  
* **Business Rules:**  
  * 1 Item Detail dapat menampung ![][image4] file PDF/Gambar.  
  * Ukuran maksimal per file: **15 MB**.  
  * Format file yang diizinkan: .pdf, .jpg, .jpeg, .png.  
* **User Stories & Acceptance Criteria:**  
  * *US-03:* Sebagai Operator, saya ingin mengunggah beberapa berkas scan (BAPP, Daftar Hadir, Kuitansi) sekaligus pada item 001366\. Honor petugas pendataan sensus.  
  * *Kriteria Penerimaan:*  
    * Terdapat area *drag-and-drop* atau *file picker* multi-select.  
    * Setiap file yang diunggah diberi label nama/keterangan dokumen (misal: "BAPP Honor", "Daftar Penerima").  
    * File berhasil disimpan di server dan terdaftar pada tabel database item terkait.

### **Modul D: Pratinjau & Verifikasi Dokumen Syarat Pencairan (Bendahara)**

* **Deskripsi:** Halaman khusus bagi Bendahara untuk memeriksa kelengkapan file sebelum pencairan dana.  
* **Business Rules:**  
  * Bendahara dapat mengubah status verifikasi item dari Pending ![][image1] Disetujui / Siap Cair atau Ditolak (Butuh Revisi).  
  * Pembukaan file menggunakan *PDF Previewer* bawaan aplikasi tanpa perlu mengunduh file ke PC lokal.  
* **User Stories & Acceptance Criteria:**  
  * *US-04:* Sebagai Bendahara, saya ingin melihat pratinjau PDF berkas BMA.006 untuk memastikan honor sensus siap dicairkan.  
  * *Kriteria Penerimaan:*  
    * Klik pada dokumen langsung membuka modal/panel PDF Viewer interaktif.  
    * Terdapat tombol "Setujui Pencairan" yang memicu perubahan status indikator menjadi Hijau (Lengkap).

## **6\. Integrasi Pihak Ketiga & Ketergantungan (Dependencies)**

* **File Viewer Engine:** PDF.js / Browser Native PDF Renderer (Untuk pratinjau dokumen tanpa plugin luar).  
* **Storage Engine:** Local File System Server BPS Subang / S3-compatible local storage dengan struktur direktori teratur (/uploads/{tahun}/{kode\_sub\_output}/{kode\_item}/).

## **7\. Kebutuhan Non-Fungsional (Non-Functional Requirements)**

### **Keamanan (Security)**

* Enkripsi kata sandi menggunakan standar Bcrypt atau Argon2.  
* Proteksi URL berkas: File upload tidak boleh diakses secara publik tanpa token sesi/autentikasi login pengguna.  
* Pencegahan celah keamanan web umum (XSS, CSRF, SQL Injection).

### **Performa & Aksesibilitas**

* Waktu muat halaman (*page load*) ![][image5] detik pada jaringan lokal BPS Subang.  
* Dukungan unggah bersamaan (*chunked upload* / respon asinkron) agar aplikasi tidak *freeze* saat mengunggah file besar.

### **Antarmuka & Lokalisasi**

* Bahasa Utama: Bahasa Indonesia (mengikuti terminologi resmi BPS seperti DIPA, POK, SPJ, SPM, BAPP, Wilkerstat).  
* Format Mata Uang: Rupiah Indonesia (Rp xx.xxx.xxx).

## **8\. UX/UI & Alur Data (User Flow & Detailed Navigation Tree)**

### **Struktur Navigasi Lengkap Hirarki POK (Sidebar / Treeview)**

\[GG.2902\] Penyediaan dan Pengembangan Statistik Distribusi  
 ├── \[BMA\] Data dan Informasi Publik  
 │    ├── \[BMA.004\] PUBLIKASI/LAPORAN STATISTIK DISTRIBUSI  
 │    │    ├── \[005\] Dukungan Penyelenggaraan Tugas dan Fungsi Unit  
 │    │    │    └── \[005.0A\] TANPA SUB KOMPONEN  
 │    │    │         ├── \[521213\] Belanja Honor Output Kegiatan  
 │    │    │         │    ├── (Item 000733\) Honor petugas pendataan lapangan Survei Jasa...  
 │    │    │         │    └── (Item 001204\) Honor pelaksanaan SPUNP  
 │    │    │         └── \[524113\] Belanja Perjalanan Dinas Dalam Kota  
 │    │    │              ├── (Item 000734\) Transport lokal petugas pemeriksaan lapangan...  
 │    │    │              └── (Item 001359\) Transport lokal petugas pendataan lapangan...  
 │    │    ├── \[051\] PERSIAPAN  
 │    │    │    └── \[051.0A\] TANPA SUB KOMPONEN  
 │    │    │         ├── \[521211\] Belanja Bahan (001363, 001364\)  
 │    │    │         └── \[524113\] Belanja Perjalanan Dinas Dalam Kota (001365)  
 │    │    └── \[052\] PENGUMPULAN DATA  
 │    │         └── \[052.0A\] TANPA SUB KOMPONEN  
 │    │              └── \[521211\] Belanja Bahan (000742, 001361, 001362\)  
 │    │  
 │    └── \[BMA.006\] PUBLIKASI/LAPORAN SENSUS EKONOMI (FOKUS UTAMA / MVP CORE)  
 │         ├── \[005\] Dukungan Penyelenggaraan Tugas dan Fungsi Unit  
 │         │    ├── \[005.0A\] TANPA SUB KOMPONEN  
 │         │    │    ├── \[521213\] Belanja Honor Output Kegiatan  
 │         │    │    │    ├── (Item 001366\) Honor petugas pendataan sensus  \<-- \[UPLOAD MULTI-FILE\]  
 │         │    │    │    └── (Item 001510\) Honor pemeriksa lapangan sensus (UB-organik)  
 │         │    │    └── \[524113\] Belanja Perjalanan Dinas Dalam Kota  
 │         │    │         └── (Item 000698\) Task force pendataan lengkap dan CAWI BPS Kab/Kota  
 │         │    └── \[005.0B\] SENSUS EKONOMI 2026  
 │         │         └── \[521213\] Belanja Honor Output Kegiatan  
 │         │              ├── (Item 001211\) Honor petugas lapangan sensus  \<-- \[UPLOAD MULTI-FILE\]  
 │         │              └── (Item 001508\) Honor pemeriksa lapangan sensus (PML)  
 │         ├── \[523\] Publisitas SE2026  
 │         │    └── \[523.0A\] TANPA SUB KOMPONEN  
 │         │         ├── \[521211\] Belanja Bahan (001126)  
 │         │         ├── \[522191\] Belanja Jasa Lainnya (000699, 000700\)  
 │         │         └── \[524111\] Belanja Perjalanan Dinas Biasa (001123)  
 │         ├── \[524\] Penetapan Kerangka Geospasial dan Muatan Wilkerstat  
 │         │    └── \[524.0A\] TANPA SUB KOMPONEN  
 │         │         ├── \[521211\] Belanja Bahan (000701, 000702\)  
 │         │         ├── \[521213\] Belanja Honor Output Kegiatan (001340)  
 │         │         ├── \[521219\] Belanja Barang Non Operasional Lainnya (000704)  
 │         │         ├── \[521811\] Belanja Barang Persediaan Barang Konsumsi (000705, 000706\)  
 │         │         └── \[524113\] Belanja Perjalanan Dinas Dalam Kota (000707, 000708\)  
 │         ├── \[529\] Penerapan Prelist SBR Untuk SE2026  
 │         │    └── \[529.0A\] TANPA SUB KOMPONEN  
 │         │         ├── \[521811\] Belanja Barang Persediaan Barang Konsumsi (000709, 000710\)  
 │         │         └── \[524113\] Belanja Perjalanan Dinas Dalam Kota (000711, 000712\)  
 │         ├── \[530\] Pelaksanaan SE2026  
 │         │    ├── \[530.0A\] TANPA SUB KOMPONEN  
 │         │    │    └── \[521213\] Belanja Honor Output Kegiatan (001511, 001512\)  
 │         │    └── \[530.0B\] PENDATAAN LENGKAP  
 │         │         ├── \[521211\] Belanja Bahan (001344, 001345, 001367, 001509\)  
 │         │         ├── \[521213\] Belanja Honor Output Kegiatan (001346, 001347, 001348, 001349\)  
 │         │         ├── \[521811\] Belanja Barang Persediaan Barang Konsumsi (000724, 000725\)  
 │         │         ├── \[522151\] Belanja Jasa Profesi (000727, 000728, 001341\)  
 │         │         └── \[524113\] Belanja Perjalanan Dinas Dalam Kota (000729, 001342, 001343, 001368\)  
 │         └── \[535\] Penyusunan Diseminasi SE2026  
 │              └── \[535.0A\] TANPA SUB KOMPONEN  
 │                   └── \[521811\] Belanja Barang Persediaan Barang Konsumsi (000732)  
 │  
 └── \[FAN\] Pemenuhan Prioritas Direktif Presiden  
      └── \[FAN.ZZ1\] Pemenuhan Prioritas Direktif Presiden  
           └── \[051\] SENSUS EKONOMI 2026  
                └── \[051.0A\] TANPA SUB KOMPONEN  
                     ├── \[521213\] Belanja Honor Output Kegiatan  
                     │    ├── (Item 001207\) Honor petugas lapangan sensus  
                     │    ├── (Item 001208\) Honor pengolahan peta (Wilkerstat)  
                     │    └── (Item 001350\) Honor pengolahan peta (Wilkerstat)  
                     ├── \[521219\] Belanja Barang Non Operasional Lainnya  
                     │    ├── (Item 001353\) Asuransi petugas  
                     │    └── (Item 001354\) Asuransi petugas  
                     ├── \[524113\] Belanja Perjalanan Dinas Dalam Kota  
                     │    └── (Item 001209\) Task force pendataan lengkap dan CAWI BPS Kab/Kota  
                     └── \[524114\] Belanja Perjalanan Dinas Paket Meeting Dalam Kota  \<-- \[BATAS AKHIR AKUN MVP\]  
                          ├── (Item 001351\) Paket Meeting Fullboard pelatihan petugas pendataan...  
                          └── (Item 001352\) Perjalanan Fullboard pelatihan petugas pendataan...

## **9\. Rencana Peluncuran & Pengujian (Go-To-Market & QA)**

### **Strategi Pengujian (QA Plan)**

1. **Unit Testing:** Pengujian fungsi upload file, validasi tipe file, dan relasi database master POK.  
2. **Integration Testing:** Pengujian alur pengunggahan oleh Operator hingga persetujuan verifikasi oleh Bendahara pada menu BMA.006.  
3. **User Acceptance Testing (UAT):** Pengujian langsung oleh Bendahara dan Kasubag BPS Subang menggunakan data riil POK Sensus Ekonomi.

### **Strategi Peluncuran**

* **Tahap 1 (Bulan Ke-1):** Rilis Modul Utama BMA.006 (Sensus Ekonomi) untuk penggunaan riil pencairan honor petugas.  
* **Tahap 2 (Bulan Ke-2 s/d Ke-6):** Menerapkan modul untuk 16 program lainnya secara bertahap sambil penyusunan Laporan Tugas Akhir.

[image1]: <data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAZCAYAAADe1WXtAAABVUlEQVR4XrVUPU/EMAxtBBsgBlQqXU5J2g5dWdlZEL+FEWaG29gZuOH4f/wJkmuaPMduqfh4khXbz352UqlVlaCiFUf0v0OuXdnA8ePGCLFfyUuJySm19vpLHOJPRMvGuboSK+tCGZbiu41cZvEq4JJJMyQVGaOmac6stQ+0CtUmn0wo4jQlaZw61764tm2gigI3WwVfa4y5s8Y8soEEkJNoDqX8Ezx5u08ZpLu+v/bkq7f3YCae1DfRgDPmwxr76f19sI3WV/8rKr5pGdMPdYRz7sYLH7TebIMhx/sBmaMfQ2u99du9dX13OXF5OcUb2RCWOH79Z/80txJXiALE5Jiu6/rcb7kLZ16v+GuV/UiKt1DVyTAMF4mKHInnRYWnWIXFrmL6FONVAPRWNCOUB4yijFvsWyQDfimawf+nDEIqJ2cmZh82FYVgNp4TypgNjDGrA3wBOYgwgSOmB34AAAAASUVORK5CYII=>

[image2]: <data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACoAAAAZCAYAAABHLbxYAAADIUlEQVR4XtVVv2sUQRTeJUYUDxXJGTzOmd27s8gVFlmwsIxiF9IGy1gY0oiif0BKW+38I6wEQSGIlVjZaesVBgSttLHR782P3TdvZ/YukMJ83LubeT+/efP2NsuOBblUWBh1whZDTp+2v9A023qVMxe/pF8vXC/Q5CBpO4XhhmGUZIMuW4CEIxVgVakYLxiu/TenKezRMlElwzy7Q1CWEff6sixXgXM1EWdYX6+WB4PBitfT72g0ujCZTM6TuPAF8H8RZWVYkgAxnYdjJcewKMqLIHhDa/1Ma3WolKq4nfyV0hXsvyG/lFYzpfV3rD8jTpNYRyd1IFvbvdSQMgeB4gyJOVfLpVGg4D3IHkg+JjKGKAuwRFXlDjGDzxfk3Y900sEzFkXFdgmJbuLEr5DsPgl0p0KXOEBisybatlFHn9udIyFPH26tg3xisVwCqS0kO9BK7wyHw7ONzfpFm+qVtmubiDVEJQdGNEfuSxVmNvSYg6qqlkFwG0neYda2aW8Mke7z2i3SWbujPBz6NeR/j4O8xAi8wP4D5AnVq2syfwPqFpx2nBwUZbEF9RL36YLslk/PO+q11pJn4/H4Ms2lJwWfEWp/A/ldEnIMbhjGuyD2EY63SLJOgpG2JpG3OtqFfr/fo1v0QntjYFdwAojizujPGIkewPjWyQYMlqzj09BalKSFvHppK4viob96RvSrkysyxoDm1M6qsnNa0Jw6wgQaRC9WUZtSaDqqK+7f76/0cAAi9QOyZnX9npIdnVfCP/mQNzh18+RH0JVLp64eQUVZ7Jdlecdts6vD4TX4HqLmLkngz8EvlRWnP/sNGgkaDxoTksbcwMbYLFSIXouI+wP5C/mJ+BnkNgl50tVCXqOLj9BxepN9QtxT+ffUBmdaq2pFjtNf1+a1qPem0+lpo4zEhIgbeSOo22Zey2KVHzaCmDK3LOznCBBF3LLRJEmk0e2esiYKiQPFO91SGJhbi5sSkMmDYGGUies985M+x4aTQtSXkPnlPkDSmDTMx+JnjZilSu4jiB06RLc1AHc94kuHgQLbIXIf03Sdxj5kueH0D0i1lBGwYHMcAAAAAElFTkSuQmCC>

[image3]: <data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABsAAAAZCAYAAADAHFVeAAAC4ElEQVR4Xr1VMWsUQRTe5UhxJCBqztW73ZnZNQqija4Kim20sgkIQgoLCyWFxTWHghAJwUYhYopYxh8gNjbR4tBGtBMCNkL8E7b6fbszu7Ozc2dAzHe825k333vfvDezd0FQIcSH3+arerTGTXjILsyal2Mr2q5p8BLsjTQI7cq0u0KWZaellG+0vYzj+IhbGR9SyKXCpLxaBTvoJElykiakuNbrzc/VOmEwGAxilaqdOE4WaGmaXkbCz0LIRfA6VImiaDZV6iFEXtPyPJ+xc9g4GDEkOY7Fd0KIpzSMl2GfYEuGI+gTctzr9eZojMZ8XSl1B/YKXAiLLcahvQs0S6IEJiHIGyBt6rViHRWeg28XVZ7lXAoxcsQCxN2HQG5yYX6PG62zlPegBEZJHA+QZE9KsVz5gX6/P49qdhE8JBHjoS3GJJg/RtwZ8rkpbpitq8U0zBgVXALpF3Z409pQwITwj7HbbbowvoLxF5xVRMMZHoVvHefXRcu6bCHP0uT3giII+s2n7ecFQTVjrH3gwQc8IylXUMl7GvhvsdGL5KamfaYEtzIDW8xXGc2cUQthEV+1L4qOzWL8DB34RsPaqGirgVcsaFQ2USxO4i6SvoApzvEajBCzhiR8FTrowBB5F6uAAxXzXpDAuiApL4j2mh7rOW7jXQjc4hi38TD4X+2zx1oG36NyFuqrL+Ue35EqDb5S3Dj4f7Dv2tUA1s7DnptfCQifQCU/CzGdCOvwoVIL/CXYBNH3Un/nky4jxvbS8JJvle0rVxqV6SwYZ8KujH5UIUlEggc0kHLk2QFxxdIpk5brtKJ9FkLEbKRKrRoxzG/Dbji8IMjzCzN8b2gQv46/k0MuBxfiFIKf0BpXWoMxWNtWqfpYmEpXa15jz9b/WdPt8zZQU9q82mNG04httwNN2A/Zu2wHegkaLQHngF14F/+XWAErb0vn79EW2mLufCJaYhMia94/iNWYHrJfsT8Kxqr+mTvTlgAAAABJRU5ErkJggg==>

[image4]: <data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAAaCAYAAABVX2cEAAACBElEQVR4XqVVu0oDQRTdwVgoERMwiCGZxxYGQbDYbxBiY2EhhpS2oqVWItqkEEQbIb3kFyz8AMEP8AesbIR0ChrP3dnZnZndDT4unM3MmXPP3rkzbILAD5Yg+3FGZmYz8VgT04RuisuXrWVcwdA3scOfW8GF2BdcDGMIMVRSnrRarTmTJHVc01oM6KRUA3A114mRWNVguEWAeAx8Cs43jSSKollwSgj+QOCcH2K+guSZopaRYY8A4SmEb8AIdMWsdzqdBXA3BM5FPcv0+xj818wNJpUcEMIwXMV2R2TYbrfXjQDzNeCSYM7GOTfDoJo6cEVoLDWqqLCLpC80+MyoMd8B+oR8LXFomgseweiYQHNUt4hGPyLxWUm1TDqMz7FOushLdwMV9LClbYLhkHwATLC2l/YLOyAYk7wXdkt3Bm8MCYbkdBW4eAF3D4Mo7lXAGCEzMRc7YeJ+CfSq0agSsjXGYHABvAt9Wfs6IxUYXUZTD6gfMeeVT6cp9DUZKyU3LIkf2hZmR7g3u/5qEhUcxAhbfkL11t0KXFd91PwVwgl684H5HaHZbM7baqlkFy+7TXdUUloS1t78HkyNn+r85nmR0WaUE/p7cD98OXkafl4h+Rszf5L38sbldrn4q1e+rvRRkhEU/XEERU4lXvbajyNxSr2yR0FoVcE7rWFZbhrTBCz4BgfOXuVnhIHeAAAAAElFTkSuQmCC>

[image5]: <data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAZCAYAAABQDyyRAAACmklEQVR4Xs1UPYsUQRCdZj1RDAxXYXe6exNhFYMdRDCSUwQROdlo0URMDkS4wF8hGF4k/gIDo0tUWI3N/BUGBv6E81V310xNdd/MHYj42Nrp7nr1qvqzMhVgwq8Mwx7NiH36NwO+s4GSGZYT4bKG1G69Mgv7w5ggirgWup+jKyPjGh7P5342jEaPEYRfUak059wF593d+Xx+g9p9RiCNYYzxFwro4saSdZALzy0d7Z17Yq09gr22df0V39/W1g/J2nM9hCFOmI0k9G6Kqbz31+q6/gTeFRptmmYHBXyA/SKD7/rI+clcEwTeSTM6wrLug3JOk3hFkeAxeMe1te/Zgf4zGiNDYfsxQ5anQ3JNQN5D0Bb2YjabXSTrB+q2qWxtr4J/iJV4wMNY+jXsmIwKaONKNTTNasc7t4HIN5A31JdMbunUGmGMtgerjcSHcgsy5hwzo1km2zrv9uCZ9IkRMVmeMu5rPjPn/O2U/BVZrCqBWhh8ipl+R2X3yAwnllp5vgF0ZO895O0P6D9PjlBjy/gvCphOp5dwYg9A/JJst1LLbwwvsKpEdKN6h8VicRlaH2GPyEPXkYzytQHyy6c8nIMa58CdfA4idEqGqVZIhPg3WNH7PArdNRkmu5bsIqhSugGwzzhEGxLs5TspN7BahYfnrY3X950w6m/xNN8iHq1qgtyUDPQI7dLWYDYHZHEJs81ogRniyY2PTh3ufniU6PuTDBoLQU8yai8L6sZ7f5MMIi+Xy+V5WX/G1/1BlASKiMQ+vd86lUwGjpIK6lsUVhm7rhbMb89IsdLFFQzQtbwZFD8N/mEBQ8TgY4LOwP+lwrjm9FdgFDDAahNRgwU1P/lKBev9j5Bjyt/XkKqdox0VZPlU96N0AV3/DzA/bDcjXVCrAAAAAElFTkSuQmCC>