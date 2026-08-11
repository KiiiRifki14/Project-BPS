# **Product Requirements Document (PRD)**

## **Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang**

## **1\. Kontrol Dokumen (Document Control)**

* **Nama Resmi Proyek:** Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang  
* **Penulis (Author):** Tim Pengembang PKL / Product Manager  
* **Versi:** v2.0 (Final Scope & Structured Menu Alignment)  
* **Tanggal Terakhir Diperbarui:** 11 Agustus 2026  
* **Pemangku Kepentingan (Stakeholders):**  
  * Petinggi BPS Kabupaten Subang (Sponsor & Approver Utama)  
  * Bendahara Pengeluaran BPS Subang (Key User & Validator Pencairan)  
  * Tim Developer / AI Coding Assistant

## **2\. Ringkasan Eksekutif & Latar Belakang (Executive Summary)**

### **Visi Produk**

Mewujudkan tata kelola administrasi keuangan BPS Kabupaten Subang yang transparan, terstruktur, akuntabel, dan efisien melalui sistem digitalisasi arsip pertanggungjawaban keuangan yang terintegrasi secara dinamis berbasis POK.

### **Masalah Utama**

1. **Pencarian Dokumen Fisik Lambat:** Verifikasi berkas pertanggungjawaban (SPJ, SPM, BAPP, Kuitansi Honor) masih bergantung pada dokumen fisik (*hardcopy*) yang berisiko hilang atau memakan waktu lama saat penelusuran.  
2. **Keterlambatan Syarat Pencairan:** Bendahara membutuhkan kepastian kelengkapan dokumen pendukung sebelum mencairkan dana kegiatan.  
3. **Restrukturisasi Anggaran Berulang:** Struktur anggaran POK BPS berubah setiap tahun anggaran (misal 2026, 2027), sehingga sistem yang bersifat *hardcoded* akan cepat usang.  
4. **Penggabungan File Manual:** Pengguna sering mengalami kesulitan jika harus menggabungkan (*merge*) file PDF secara manual sebelum diunggah ke sistem.

### **Solusi**

Membangun web portal internal **Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang** yang memetakan hirarki POK BPS secara terstruktur (Program ![][image1] Kategori ![][image1] Output ![][image1] Sub-Output ![][image1] Komponen ![][image1] Sub-Komponen ![][image1] Akun ![][image1] Item Detail), mendukung *multi-file upload* per item tanpa penggabungan manual, fleksibel diatur pengguna (*dynamic master data*), serta menyediakan pratinjau dokumen (*inline preview*) untuk verifikasi cepat oleh Bendahara.

### **Metrik Keberhasilan (KPI)**

* **Kecepatan Verifikasi:** Memangkas waktu verifikasi berkas pertanggungjawaban oleh Bendahara dari rata-rata 2 hari menjadi ![][image2] menit per kegiatan.  
* **Akurasi Kelengkapan Berkas:** 100% berkas pencairan honor Sensus Ekonomi terunggah lengkap sebelum pencairan dana disetujui.  
* **Efisiensi Beban Pengguna:** Eliminasi kebutuhan alat *PDF merger* terpisah (![][image3] proses *merge* manual di pihak pengguna).

## **3\. Batasan Ruang Lingkup (Scope & Phasing)**

### **Fase 1 (MVP \- Minimum Viable Product / Fokus Utama)**

* **Arsitektur Generik POK BPS Subang:** Sistem siap menampung struktur POK BPS Subang (Pagu Rp 28,6 Miliar).  
* **Cakupan Alur Induk:** Penguncian alur dan pengujian fokus pada program GG.2902 (*Penyediaan dan Pengembangan Statistik Distribusi*) mencakup cabang BMA Data dan Informasi Publik hingga batas akhir akun 524114 (*Belanja Perjalanan Dinas Paket Meeting Dalam Kota*) di bawah sub-output FAN.ZZ1.  
* **Fokus Menu Utama (Core Priority):** Modul **BMA.006 PUBLIKASI/LAPORAN SENSUS EKONOMI** (khususnya pencairan honor pendataan sensus 001366, 001211, 001510, serta dokumen BAPP & SPJ pendukung).  
* **Manajemen Multi-File Upload:** Upload beberapa file PDF/Gambar terpisah pada 1 item kegiatan.  
* **RBAC (Role-Based Access Control):** Pengisian hak akses untuk Operator, Supervisor, Bendahara, dan Admin.  
* **Master Data Dinamis:** Penambahan/pengeditan struktur POK oleh Supervisor untuk fleksibilitas tahun anggaran mendatang.

### **Di Luar Batasan (Out of Scope)**

* Integrasi langsung (*API sync*) dengan aplikasi nasional Kemenkeu seperti SAKTI atau SPAN (Sistem ini murni sebagai arsip digital internal BPS Subang).  
* Transaksi perbankan otomatis (Pencairan tetap dilakukan secara manual oleh Bendahara setelah status dokumen diverifikasi di aplikasi).

## **4\. Analisis Pengguna & Persona (User Persona)**

| Persona | Role | Kebutuhan Utama | Tantangan |
| :---- | :---- | :---- | :---- |
| **Operator / Petugas Input** | OPERATOR | Mengunggah bukti kuitansi, SPJ, dan BAPP honor petugas sensus dengan cepat tanpa *merge* PDF. | Banyak potongan scan PDF terpisah untuk 1 item kegiatan. |
| **Supervisor / PJ Kegiatan** | SUPERVISOR | Mengelola struktur POK jika ada revisi DIPA/POK dan mengecek kelengkapan tim. | Struktur anggaran sering berubah di tengah tahun berjalan. |
| **Bendahara Pengeluaran** | BENDAHARA | Melihat pratinjau (*preview*) dokumen secara *inline* sebelum mencairkan honor/dana kegiatan. | Takut salah cair jika dokumen pertanggungjawaban fisik belum lengkap. |
| **System Administrator** | ADMIN | Mengelola akun pengguna, mengatur reset password, dan melakukan pemeliharaan database. | Memastikan keamanan akses dan hak peran sistem. |

## **5\. Rincian 6 Menu Utama Sistem Web**

Aplikasi dispesifikasikan menjadi **6 Menu Utama** yang bersih dan terstruktur:

1. **Dashboard Utama (/dashboard)**  
   * Menampilkan ringkasan Pagu Anggaran (Rp 28,6 Miliar), Realisasi, Sisa Anggaran, dan jumlah berkas (APPROVED, PENDING, REJECTED).  
   * Banner Akses Cepat Langsung ke Kegiatan Prioritas **BMA.006 PUBLIKASI/LAPORAN SENSUS EKONOMI**.  
   * Tabel riwayat pengunggahan berkas terbaru.  
2. **Arsip Keuangan POK (/items)**  
   * Workspace kerja utama Operator & Bendahara.  
   * Sidebar *Treeview* interaktif 8-level POK dengan fitur *Search Box* pencarian kode item/akun instan.  
   * Detail workspace per item: *Header Pagu*, *Multi-File Dropzone*, dan *Tabel Berkas Terunggah*.  
3. **Verifikasi Pencairan (/verification)**  
   * Panel khusus Bendahara untuk meninjau berkas yang berstatus PENDING.  
   * Integrasi *Inline PDF Previewer Modal*.  
   * Tombol Aksi: **Setujui Pencairan (APPROVED)** atau **Tolak/Revisi (REJECTED)** yang wajib menyertakan catatan revisi.  
4. **Kelola Master Data POK (/master)**  
   * Khusus SUPERVISOR & ADMIN.  
   * Kelola data dinamis Program, Output, Sub-Output, Komponen, Sub-Komponen, Akun, dan Item tanpa merubah kode pemrograman.  
5. **Laporan & Rekapitulasi Digital (/reports)**  
   * Laporan pemantauan status kelengkapan berkas pertanggungjawaban per unit/kegiatan.  
   * Fitur ekspor laporan rekapitulasi ke format PDF/Excel.  
6. **Manajemen Pengguna (/users)**  
   * Khusus ADMIN.  
   * Manajemen akun pengguna (Tambah pengguna, edit role, dan reset password).

## **6\. Kebutuhan Fungsional & Kriteria Penerimaan (User Stories)**

### **Modul A: Autentikasi & RBAC**

* *US-01:* Sebagai pengguna, saya ingin login menggunakan NIP/Username dan Password.  
* *Kriteria Penerimaan:*  
  * Operator hanya dapat mengakses workspace upload dan laporan.  
  * Supervisor dapat mengedit master data POK.  
  * Bendahara memiliki akses khusus ke panel verifikasi persetujuan pencairan.

### **Modul B: Multi-File Upload & Storage**

* *US-02:* Sebagai Operator, saya ingin mengunggah beberapa file PDF/Gambar terpisah pada item 001366\. Honor petugas pendataan sensus.  
* *Kriteria Penerimaan:*  
  * Terdapat area *drag-and-drop* multi-file (Maksimal 15MB per file).  
  * Setiap file diberi label keterangan (misal: "BAPP Honor", "Kuitansi", "Daftar Hadir").  
  * File disimpan pada direktori private server dan terdaftar pada database terikat ID Item.

### **Modul C: Inline Preview & Verification**

* *US-03:* Sebagai Bendahara, saya ingin melihat pratinjau PDF langsung di browser untuk memverifikasi kelengkapan syarat pencairan.  
* *Kriteria Penerimaan:*  
  * Pratinjau PDF terbuka dalam modal overlay tanpa mengunduh file ke PC lokal.  
  * Menyetujui pencairan mengubah indikator status item menjadi Hijau (APPROVED).  
  * Menolak pencairan wajib mengisi catatan revisi dan mengubah indikator menjadi Merah (REJECTED).

## **7\. Kebutuhan Non-Fungsional**

* **Keamanan (Security):** Enkripsi kata sandi menggunakan Bcrypt. Akses file private dijamin melalui *Authenticated Stream Controller* Laravel.  
* **Performa:** Waktu muat halaman ![][image4] detik pada jaringan internal BPS Subang.  
* **Lokalisasi:** Bahasa Indonesia dengan terminologi resmi BPS (DIPA, POK, SPJ, SPM, BAPP, Pagu Anggaran). Format Rupiah Indonesia (Rp xx.xxx.xxx).

## **8\. Navigasi Hirarki POK Fase 1 (Sidebar Treeview)**

\[GG.2902\] Penyediaan dan Pengembangan Statistik Distribusi  
 ├── \[BMA\] Data dan Informasi Publik  
 │    ├── \[BMA.004\] PUBLIKASI/LAPORAN STATISTIK DISTRIBUSI  
 │    │    ├── \[005\] Dukungan Penyelenggaraan Tugas dan Fungsi Unit  
 │    │    │    └── \[005.0A\] TANPA SUB KOMPONEN  
 │    │    │         ├── \[521213\] Belanja Honor Output Kegiatan (Item 000733, 001204\)  
 │    │    │         └── \[524113\] Belanja Perjalanan Dinas Dalam Kota (Item 000734 \- 001359\)  
 │    │    ├── \[051\] PERSIAPAN  
 │    │    │    └── \[051.0A\] TANPA SUB KOMPONEN (Item 001363 \- 001365\)  
 │    │    └── \[052\] PENGUMPULAN DATA  
 │    │         └── \[052.0A\] TANPA SUB KOMPONEN (Item 000742, 001361, 001362\)  
 │    │  
 │    └── \[BMA.006\] PUBLIKASI/LAPORAN SENSUS EKONOMI (MVP CORE FOCUS)  
 │         ├── \[005\] Dukungan Penyelenggaraan Tugas dan Fungsi Unit  
 │         │    ├── \[005.0A\] TANPA SUB KOMPONEN  
 │         │    │    ├── \[521213\] Belanja Honor Output Kegiatan (Item 001366, 001510\)  
 │         │    │    └── \[524113\] Belanja Perjalanan Dinas Dalam Kota (Item 000698\)  
 │         │    └── \[005.0B\] SENSUS EKONOMI 2026  
 │         │         └── \[521213\] Belanja Honor Output Kegiatan (Item 001211, 001508\)  
 │         ├── \[523\] Publisitas SE2026 (Item 001126, 000699, 000700, 001123\)  
 │         ├── \[524\] Penetapan Kerangka Geospasial dan Muatan Wilkerstat (Item 000701 \- 000708\)  
 │         ├── \[529\] Penerapan Prelist SBR Untuk SE2026 (Item 000709 \- 000712\)  
 │         ├── \[530\] Pelaksanaan SE2026 (Item 001511 \- 001368\)  
 │         └── \[535\] Penyusunan Diseminasi SE2026 (Item 000732\)  
 │  
 └── \[FAN\] Pemenuhan Prioritas Direktif Presiden  
      └── \[FAN.ZZ1\] Pemenuhan Prioritas Direktif Presiden  
           └── \[051\] SENSUS EKONOMI 2026  
                └── \[051.0A\] TANPA SUB KOMPONEN  
                     ├── \[521213\] Belanja Honor Output Kegiatan (Item 001207 \- 001350\)  
                     ├── \[521219\] Belanja Barang Non Operasional Lainnya (Item 001353, 001354\)  
                     ├── \[524113\] Belanja Perjalanan Dinas Dalam Kota (Item 001209\)  
                     └── \[524114\] Belanja Perjalanan Dinas Paket Meeting Dalam Kota (Item 001351, 001352\)  


[image1]: <data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAZCAYAAADe1WXtAAABVUlEQVR4XrVUPU/EMAxtBBsgBlQqXU5J2g5dWdlZEL+FEWaG29gZuOH4f/wJkmuaPMduqfh4khXbz352UqlVlaCiFUf0v0OuXdnA8ePGCLFfyUuJySm19vpLHOJPRMvGuboSK+tCGZbiu41cZvEq4JJJMyQVGaOmac6stQ+0CtUmn0wo4jQlaZw61764tm2gigI3WwVfa4y5s8Y8soEEkJNoDqX8Ezx5u08ZpLu+v/bkq7f3YCae1DfRgDPmwxr76f19sI3WV/8rKr5pGdMPdYRz7sYLH7TebIMhx/sBmaMfQ2u99du9dX13OXF5OcUb2RCWOH79Z/80txJXiALE5Jiu6/rcb7kLZ16v+GuV/UiKt1DVyTAMF4mKHInnRYWnWIXFrmL6FONVAPRWNCOUB4yijFvsWyQDfimawf+nDEIqJ2cmZh82FYVgNp4TypgNjDGrA3wBOYgwgSOmB34AAAAASUVORK5CYII=>

[image2]: <data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACoAAAAZCAYAAABHLbxYAAADIUlEQVR4XtVVv2sUQRTeJUYUDxXJGTzOmd27s8gVFlmwsIxiF9IGy1gY0oiif0BKW+38I6wEQSGIlVjZaesVBgSttLHR782P3TdvZ/YukMJ83LubeT+/efP2NsuOBblUWBh1whZDTp+2v9A023qVMxe/pF8vXC/Q5CBpO4XhhmGUZIMuW4CEIxVgVakYLxiu/TenKezRMlElwzy7Q1CWEff6sixXgXM1EWdYX6+WB4PBitfT72g0ujCZTM6TuPAF8H8RZWVYkgAxnYdjJcewKMqLIHhDa/1Ma3WolKq4nfyV0hXsvyG/lFYzpfV3rD8jTpNYRyd1IFvbvdSQMgeB4gyJOVfLpVGg4D3IHkg+JjKGKAuwRFXlDjGDzxfk3Y900sEzFkXFdgmJbuLEr5DsPgl0p0KXOEBisybatlFHn9udIyFPH26tg3xisVwCqS0kO9BK7wyHw7ONzfpFm+qVtmubiDVEJQdGNEfuSxVmNvSYg6qqlkFwG0neYda2aW8Mke7z2i3SWbujPBz6NeR/j4O8xAi8wP4D5AnVq2syfwPqFpx2nBwUZbEF9RL36YLslk/PO+q11pJn4/H4Ms2lJwWfEWp/A/ldEnIMbhjGuyD2EY63SLJOgpG2JpG3OtqFfr/fo1v0QntjYFdwAojizujPGIkewPjWyQYMlqzj09BalKSFvHppK4viob96RvSrkysyxoDm1M6qsnNa0Jw6wgQaRC9WUZtSaDqqK+7f76/0cAAi9QOyZnX9npIdnVfCP/mQNzh18+RH0JVLp64eQUVZ7Jdlecdts6vD4TX4HqLmLkngz8EvlRWnP/sNGgkaDxoTksbcwMbYLFSIXouI+wP5C/mJ+BnkNgl50tVCXqOLj9BxepN9QtxT+ffUBmdaq2pFjtNf1+a1qPem0+lpo4zEhIgbeSOo22Zey2KVHzaCmDK3LOznCBBF3LLRJEmk0e2esiYKiQPFO91SGJhbi5sSkMmDYGGUies985M+x4aTQtSXkPnlPkDSmDTMx+JnjZilSu4jiB06RLc1AHc94kuHgQLbIXIf03Sdxj5kueH0D0i1lBGwYHMcAAAAAElFTkSuQmCC>

[image3]: <data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABsAAAAZCAYAAADAHFVeAAAC4ElEQVR4Xr1VMWsUQRTe5UhxJCBqztW73ZnZNQqija4Kim20sgkIQgoLCyWFxTWHghAJwUYhYopYxh8gNjbR4tBGtBMCNkL8E7b6fbszu7Ozc2dAzHe825k333vfvDezd0FQIcSH3+arerTGTXjILsyal2Mr2q5p8BLsjTQI7cq0u0KWZaellG+0vYzj+IhbGR9SyKXCpLxaBTvoJElykiakuNbrzc/VOmEwGAxilaqdOE4WaGmaXkbCz0LIRfA6VImiaDZV6iFEXtPyPJ+xc9g4GDEkOY7Fd0KIpzSMl2GfYEuGI+gTctzr9eZojMZ8XSl1B/YKXAiLLcahvQs0S6IEJiHIGyBt6rViHRWeg28XVZ7lXAoxcsQCxN2HQG5yYX6PG62zlPegBEZJHA+QZE9KsVz5gX6/P49qdhE8JBHjoS3GJJg/RtwZ8rkpbpitq8U0zBgVXALpF3Z409pQwITwj7HbbbowvoLxF5xVRMMZHoVvHefXRcu6bCHP0uT3giII+s2n7ecFQTVjrH3gwQc8IylXUMl7GvhvsdGL5KamfaYEtzIDW8xXGc2cUQthEV+1L4qOzWL8DB34RsPaqGirgVcsaFQ2USxO4i6SvoApzvEajBCzhiR8FTrowBB5F6uAAxXzXpDAuiApL4j2mh7rOW7jXQjc4hi38TD4X+2zx1oG36NyFuqrL+Ue35EqDb5S3Dj4f7Dv2tUA1s7DnptfCQifQCU/CzGdCOvwoVIL/CXYBNH3Un/nky4jxvbS8JJvle0rVxqV6SwYZ8KujH5UIUlEggc0kHLk2QFxxdIpk5brtKJ9FkLEbKRKrRoxzG/Dbji8IMjzCzN8b2gQv46/k0MuBxfiFIKf0BpXWoMxWNtWqfpYmEpXa15jz9b/WdPt8zZQU9q82mNG04httwNN2A/Zu2wHegkaLQHngF14F/+XWAErb0vn79EW2mLufCJaYhMia94/iNWYHrJfsT8Kxqr+mTvTlgAAAABJRU5ErkJggg==>

[image4]: <data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAZCAYAAABQDyyRAAACmklEQVR4Xs1UPYsUQRCdZj1RDAxXYXe6exNhFYMdRDCSUwQROdlo0URMDkS4wF8hGF4k/gIDo0tUWI3N/BUGBv6E81V310xNdd/MHYj42Nrp7nr1qvqzMhVgwq8Mwx7NiH36NwO+s4GSGZYT4bKG1G69Mgv7w5ggirgWup+jKyPjGh7P5342jEaPEYRfUak059wF593d+Xx+g9p9RiCNYYzxFwro4saSdZALzy0d7Z17Yq09gr22df0V39/W1g/J2nM9hCFOmI0k9G6Kqbz31+q6/gTeFRptmmYHBXyA/SKD7/rI+clcEwTeSTM6wrLug3JOk3hFkeAxeMe1te/Zgf4zGiNDYfsxQ5anQ3JNQN5D0Bb2YjabXSTrB+q2qWxtr4J/iJV4wMNY+jXsmIwKaONKNTTNasc7t4HIN5A31JdMbunUGmGMtgerjcSHcgsy5hwzo1km2zrv9uCZ9IkRMVmeMu5rPjPn/O2U/BVZrCqBWhh8ipl+R2X3yAwnllp5vgF0ZO895O0P6D9PjlBjy/gvCphOp5dwYg9A/JJst1LLbwwvsKpEdKN6h8VicRlaH2GPyEPXkYzytQHyy6c8nIMa58CdfA4idEqGqVZIhPg3WNH7PArdNRkmu5bsIqhSugGwzzhEGxLs5TspN7BahYfnrY3X950w6m/xNN8iHq1qgtyUDPQI7dLWYDYHZHEJs81ogRniyY2PTh3ufniU6PuTDBoLQU8yai8L6sZ7f5MMIi+Xy+V5WX/G1/1BlASKiMQ+vd86lUwGjpIK6lsUVhm7rhbMb89IsdLFFQzQtbwZFD8N/mEBQ8TgY4LOwP+lwrjm9FdgFDDAahNRgwU1P/lKBev9j5Bjyt/XkKqdox0VZPlU96N0AV3/DzA/bDcjXVCrAAAAAElFTkSuQmCC>