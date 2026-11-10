# **Product Requirements Document (PRD)**

## **Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang**

## **1\. Kontrol Dokumen (Document Control)**

* **Nama Resmi Proyek:** Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang  
* **Penulis (Author):** Tim Pengembang PKL / Product Manager  
* **Versi:** v2.1 (Final Scope, Verification Checklist & Scope Boundaries)  
* **Tanggal Terakhir Diperbarui:** 19 Agustus 2026  
* **Pemangku Kepentingan (Stakeholders):**  
  * Petinggi BPS Kabupaten Subang (Sponsor & Approver Utama)  
  * Bu Mega & Bu Bahrir / Tim Teknis BPS Subang (Key Users & End Users Aplikasi)  
  * Bendahara Pengeluaran BPS Subang (Validator Pencairan)  
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

Membangun web portal internal **Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang** yang:

* Memetakan hirarki POK BPS secara terstruktur (Program ![][image1] Kategori ![][image1] Output ![][image1] Sub-Output ![][image1] Komponen ![][image1] Sub-Komponen ![][image1] Akun ![][image1] Item Detail).  
* Mendukung *multi-file upload* per item dengan pengkategorian label dokumen (BAPP, Kuitansi, KAK, Daftar Hadir, dll) tanpa penggabungan manual.  
* Fleksibel diatur pengguna (*dynamic master data*).  
* Menyediakan pratinjau dokumen (*inline preview*) dan **Fitur Ceklis Verifikasi Berkas** untuk verifikasi cepat oleh Bendahara sebelum menyetujui pencairan.

### **Metrik Keberhasilan (KPI)**

* **Kecepatan Verifikasi:** Memangkas waktu verifikasi berkas pertanggungjawaban oleh Bendahara dari rata-rata 2 hari menjadi ![][image2] menit per kegiatan.  
* **Akurasi Kelengkapan Berkas:** 100% berkas pencairan honor Sensus Ekonomi terunggah lengkap dan terverifikasi ceklis sebelum pencairan dana disetujui.  
* **Efisiensi Beban Pengguna:** Eliminasi kebutuhan alat *PDF merger* terpisah (![][image3] proses *merge* manual di pihak pengguna).

## **3\. Batasan Ruang Lingkup (Scope & Phasing)**

### **Fase 1 (MVP \- Minimum Viable Product / Fokus Utama)**

* **Arsitektur Generik POK BPS Subang:** Sistem siap menampung struktur POK BPS Subang (Pagu Rp 28,6 Miliar).  
* **Cakupan Alur Induk:** Penguncian alur dan pengujian fokus pada program GG.2902 (*Penyediaan dan Pengembangan Statistik Distribusi*) mencakup cabang BMA Data dan Informasi Publik hingga batas akhir akun 524114 (*Belanja Perjalanan Dinas Paket Meeting Dalam Kota*) di bawah sub-output FAN.ZZ1.  
* **Fokus Menu Utama (Core Priority):** Modul **BMA.006 PUBLIKASI/LAPORAN SENSUS EKONOMI** (khususnya pencairan honor pendataan sensus 001366, 001211, 001510, serta dokumen BAPP & SPJ pendukung).  
* **Manajemen Multi-File Upload & Labeling:** Upload beberapa file PDF/Gambar terpisah pada 1 item kegiatan dengan label kategori spesifik (BAPP, Kuitansi, KAK, SK, Daftar Hadir).  
* **Interactive Bendahara Checklist:** Fitur centang kelengkapan berkas satu per satu oleh Bendahara sebelum tombol *Approve* aktif.  
* **RBAC (Role-Based Access Control):** Pengisian hak akses untuk Operator, Supervisor, Bendahara, dan Admin.  
* **Master Data Dinamis:** Penambahan/pengeditan struktur POK oleh Supervisor untuk fleksibilitas tahun anggaran mendatang.

### **Di Luar Batasan (Out of Scope \- Tegas Mencegah Scope Creep)**

* **In-App Document Generator / Builder:** Sistem **TIDAK** membuat atau mencetak otomatis template BAPP/SPJ (Pembuatan dokumen, penandatanganan, dan stempel tetap dilakukan manual di luar sistem, lalu hasil scan akhir diunggah ke aplikasi).  
* **Integrasi Langsung (API sync):** Tidak ada koneksi otomatis dengan aplikasi nasional Kemenkeu seperti SAKTI atau SPAN (Sistem ini murni sebagai arsip digital internal BPS Subang).  
* **Penyediaan Server Fisik / Hosting:** Infrastruktur server, kapasitas harddisk/SSD, dan *hosting* lokal sepenuhnya menjadi tanggung jawab tim IT BPS Subang (Pengembang hanya menyerahkan *source code* dan melakukan *deployment*).  
* **Transaksi Perbankan Otomatis:** Pencairan tetap dilakukan secara manual oleh Bendahara setelah status dokumen diverifikasi di aplikasi.

## **4\. Analisis Pengguna & Persona (User Persona)**

| **Persona** | **Role** | **Kebutuhan Utama** | **Tantangan** |

| **Operator / Petugas Input (Pihak Teknis)** | OPERATOR | Mengunggah bukti kuitansi, SPJ, KAK, dan BAPP honor petugas sensus secara terpisah dengan label yang jelas tanpa *merge* PDF. | Banyak potongan scan PDF terpisah untuk 1 item kegiatan. |

| **Supervisor / PJ Kegiatan** | SUPERVISOR | Mengelola struktur POK jika ada revisi DIPA/POK dan mengecek kelengkapan tim. | Struktur anggaran sering berubah di tengah tahun berjalan. |

| **Bendahara Pengeluaran** | BENDAHARA | Memeriksa kelengkapan file via *checklist* dan pratinjau (*preview*) *inline* sebelum menyetujui pencairan. | Takut salah cair jika dokumen pertanggungjawaban fisik belum lengkap. |

| **System Administrator** | ADMIN | Mengelola akun pengguna, mengatur reset password, dan melakukan pemeliharaan database. | Memastikan keamanan akses dan hak peran sistem. |

## **5\. Rincian 6 Menu Utama Sistem Web**

Aplikasi dispesifikasikan menjadi **6 Menu Utama** yang bersih dan terstruktur:

### **1\. Dashboard Utama (/dashboard)**

* Menampilkan ringkasan Pagu Anggaran (Rp 28,6 Miliar), Realisasi, Sisa Anggaran, dan jumlah berkas (APPROVED, PENDING, REJECTED).  
* Banner Akses Cepat Langsung ke Kegiatan Prioritas **BMA.006 PUBLIKASI/LAPORAN SENSUS EKONOMI**.  
* Tabel riwayat pengunggahan berkas terbaru.

### **2\. Arsip Keuangan POK (/items)**

* Workspace kerja utama Operator & Bendahara.  
* Top Bar *Search-First Box* (pencarian kode item 001366 atau kata kunci instan) & *Cascading Filter Dropdown* (Program ![][image1] Output ![][image1] SubOutput).  
* Detail workspace per item: *Header Pagu*, *Multi-File Dropzone* dengan pemilih label kategori (BAPP, Kuitansi, KAK, SK, Daftar Hadir), dan *Tabel Berkas Terunggah*.

### **3\. Verifikasi Pencairan (/verification)**

* Panel antrean (*inbox*) khusus Bendahara untuk meninjau berkas yang berstatus PENDING.  
* Integrasi *Inline PDF Previewer Modal*.  
* **Fitur Checklist Verifikasi:** Daftar centang (*checkbox*) kelengkapan berkas terunggah. Tombol *Setujui Pencairan* baru dapat diaktifkan setelah semua indikator berkas wajib tercentang.  
* Tombol Aksi: **Setujui Pencairan (APPROVED)** atau **Tolak/Revisi (REJECTED)** yang wajib menyertakan catatan revisi.

### **4\. Kelola Master Data POK (/master)**

* Khusus SUPERVISOR & ADMIN.  
* Kelola data dinamis Program, Output, Sub-Output, Komponen, Sub-Komponen, Akun, dan Item tanpa merubah kode pemrograman.

### **5\. Laporan & Rekapitulasi Digital (/reports)**

* Laporan pemantauan status kelengkapan berkas pertanggungjawaban per unit/kegiatan.  
* Fitur ekspor laporan rekapitulasi ke format PDF/Excel.

### **6\. Manajemen Pengguna (/users)**

* Khusus ADMIN.  
* Manajemen akun pengguna (Tambah pengguna, edit role, dan reset password).

## **6\. Kebutuhan Fungsional & Kriteria Penerimaan (User Stories)**

### **Modul A: Autentikasi & RBAC**

* **US-01:** Sebagai pengguna, saya ingin login menggunakan NIP/Username dan Password.  
* **Kriteria Penerimaan:**  
  * Operator hanya dapat mengakses workspace upload dan laporan.  
  * Supervisor dapat mengedit master data POK.  
  * Bendahara memiliki akses khusus ke panel verifikasi persetujuan pencairan.

### **Modul B: Multi-File Upload & Labeling**

* **US-02:** Sebagai Operator, saya ingin mengunggah beberapa file PDF/Gambar terpisah (KAK, BAPP, Kuitansi) pada item 001366 Honor petugas pendataan sensus.  
* **Kriteria Penerimaan:**  
  * Terdapat area *drag-and-drop* multi-file (Maksimal 15MB per file).  
  * Terdapat dropdown pilihan Label Kategori (misal: "BAPP Honor", "Kuitansi", "KAK", "Daftar Hadir").  
  * File disimpan pada direktori private server dan terdaftar pada database terikat ID Item.

### **Modul C: Inline Preview, Interactive Checklist & Verification**

* **US-03:** Sebagai Bendahara, saya ingin memeriksa kelengkapan file satu per satu dengan fitur ceklis sebelum menyetujui pencairan.  
* **Kriteria Penerimaan:**  
  * Pratinjau PDF terbuka dalam modal overlay tanpa mengunduh file ke PC lokal.  
  * Terdapat *checkbox* verifikasi di samping daftar file terunggah.  
  * Menyetujui pencairan (APPROVED) mengubah indikator status item menjadi Hijau (Siap Cair).  
  * Menolak pencairan (REJECTED) wajib mengisi catatan revisi dan mengubah indikator menjadi Merah (Ditolak).

## **7\. Kebutuhan Non-Fungsional**

* **Keamanan (Security):** Enkripsi kata sandi menggunakan Bcrypt. Akses file private dijamin melalui *Authenticated Stream Controller* Laravel.  
* **Performa:** Waktu muat halaman ![][image4] detik pada jaringan internal BPS Subang.  
* **Lokalisasi:** Bahasa Indonesia dengan terminologi resmi BPS (DIPA, POK, SPJ, SPM, BAPP, KAK, Pagu Anggaran). Format Rupiah Indonesia (Rp xx.xxx.xxx).  
* **Sifat Penyimpanan File:** File fisik disimpan di server lokal/VPS BPS Subang. *Garbage collection* otomatis menghapus file fisik di storage saat data dokumen dihapus di database.

## **8\. Navigasi Hirarki POK Fase 1 (Cascading Scope)**

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

[image1]: <data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAYCAYAAAAVibZIAAABR0lEQVR4XpWTsU7EMAyGU4nhBkAnwQ3QNkmPQ30AeBgmRgYmXoKRCd3MwMhL4pzSxs5vt/BLVZ3P9u/4TnXOUKNEtSAjwHIWUY6hCoBT+tbBumBlMJIIbAEILWc1iflWO9waTkqnMF3cKcuqydxqQ0Gzc0OMG+/9y253fV54VahPyJONZAjhnYwf6oRRP4mvI1dOr2QYgv+g8KyqElF9sIpm7xjjG5k/F8SUCBVsaaUbT096q49Pbz+f9/s78vRfFH8eDveXwtG6EZdW0/fdIxn+tLdtN8M/6eSBfxoZXZHh9zAMQdzFvheT0UCGr/STPRlpTjLGrEBktiHTY9f1baG8t1E9TiochjXjOF4IIg6TKThLoNdkwUxTK99yngI1ABQyCTL8dlZSHK11OPjPTYup4qzE6LEoq1w661UTVbKA+AYFKGrcL8YoFwHVPcdhAAAAAElFTkSuQmCC>

[image2]: <data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACoAAAAZCAYAAABHLbxYAAADBUlEQVR4Xq1XMYjUQBTdcAoeB14h7nJsksnsLoiNgkFEC1E7EbUREVtB7AQLD0QQWwVBzkIEGwsRLCxtBEFB1AO1ELGwUEFLOwWr9f1kNvMz8yfZXe/BJ5n/3/x5/2dmlu10GCI+mAl8Zj1LOLKRmGGVUDjkb0dUzpUSWJ8UbcbsMyy4IPvOHW1oIckVe47/wyRdqIt85FVpve5DwkKapqezTGfcSfwMUEqd7ff7MfEw3KK13g3+eXoPafNlCCIaBFWI43gRix3HYrch5AfsN8a5Wyv8B2B/YWOVwugJLuyYZYXQqCTqdHu9pXxPvlmswjhIKBY7ii4egsBrNaEMqUpzxD7BPsM+oLDreK5M4i5/KuhM70CSx7CHo9Fouxv3YFaBwNVSqPI6SuIRW7Mec9LnQIQq9yHZc9id4XCYsJB9dWAjEROa5i7DdHQtlCnk51hAgiOwl7Cb2OzbylktU4WwLLSKkdAnsAfYo18w/q6wVeIkXnS5teo75ck7iYmvYVfxibdW4TkhC7Udhf8N9vSIxtQQrP8W/Ht5TufAATlBOAPCO0y82Ot1l3jca6Z5Fxroge/RwsGS0bo9HE7rJ/Hqiipvgv2lhwHOw7CvEHuBTmxdyfwbnCB3NFxkwS+vqkuFwyVSddp29XLjZ/daHAI/TKajBvSZ4V+HfcQh7VYdNULBX+X82nLmHftU0z5dx4TiIFldLd0VCggJxSdegf9bJdRAlZ9+nCTJCc4PAstF/GpS5iK2MsKSecQI/ZMk8V7mJs4mxO7SGhPfYDBcxvgF+M8Gg8Ey50+DKNN6FyY/VSq9j6eeKAlJpQOCE/0I3F/UndLSMbr4E++3JjPxI4JhWlyBsHOw9+C8Mr/9ArzvL0sgkbAbuOdGFUegCi4LZ63ibGh9EN09hdw74V1gDAdeZs8hwrKm43M09GN+yEnDHQ2hkSqkCzSiMU0r5GI4GoMGDX8/OORqwpiGI2P+mR1Xptd2U2pjMZy60ZBbXXd44RC8KqaeOQt1mnY0BlvDHCFqyP8PRjyPcGeE0usAAAAASUVORK5CYII=>

[image3]: <data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABsAAAAZCAYAAADAHFVeAAADJUlEQVR4XpVWPWgUQRTe5RQSSAyBnPGyu/Ozd8kSbHIcCoJYWWgREU2jphIkjYKehZJKCBaK2InBSrHQShstNIViGsXGxp/G4mystNLGJn5vdmdndnf2og8eN/O97/3PhnheLr7rx5xt3DbaUnGyxXdgDjEJ/Gpih7jxEuomFfFmsznWbrd349io8E0RjWQuGa8Y7Uo1Vu5AC+f8BHQTugp9IoXYU2QoaQghrnLG+zlSrioMw1EEOA29B70ppExsErqJgH9gjB2kO2N8GfefCHwNvrM4t4QUR3B/Cc4LmoDxtiRuxxMgb0DXmlPNMTgsMM4/4X6S7JQT56PQwUwQhAQgYIz7bXQgkXgJ5zPwO04dQ7tpZDPXXOB4BYT3nLFJjZEzAn2WUk4Tl3HFGaCIVmZvIfgDuwPcVzhnZnwFSSucpETkaJuiKNoH/FcUhcdUZ0zta0BJyB4EMyGKW4ffCAXCeS9sDym5a98KAGEegX4IIa1kyrkH22/odULQYYLzlxBFkB+S0H4ukY32jaLvm/HVCMuCFjvzU5yluJ48eJdxf4Pfs9BnAe3Po4JZH/d+tZuSIOgigm4VOysWYcbiUxcd7G+x05ndRQh8u+A+1ruTUtAE8JrFqV6vtzMPmI2RXtlWeWd6jGXcFkpAifT4MOr9eJnv6I6CzsH3omdvzz3GDM/GqADHiPAy+9bHuwNxHvFsx2FEe+TrmERgHNLv5Xs5qC4CupqTjZkm0uX5+HwPfwSmgX2lz0hzcb5AE9J3bwpkkF5Dn0v1jHPiYWB/6Dcne2my8vhI6PtDlwM7GeznoQf0XQkIy9BvcJAZRC9vDfo2juMJw0z7otFhEisW5CVJMg7+ppWMYtyCzhdWQK8GpLswvBLpnxxK9BHnBYumhNHy8U2FYTSqACsQJYLfU4qnXi12FuEbdO3bb8fxHByW8KoOFZ9tKkg+gmB3KJDrxdDHDc4N6hDdbxSLrfLrxcF1QG5RRM3OLv/srGQb9nBz0Wrfhp4rgBMaLhVSJbIt9YVW5b9LIXEQ6+Los8NFicHrGEVxs9z/yyjZPoGjXDuaw802/wWzB6XUo0v2XQAAAABJRU5ErkJggg==>

[image4]: <data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAZCAYAAABQDyyRAAACy0lEQVR4XqVVMYgUMRSdYU9QDg9BvdHdmWTijnedhatnY6NYCBYWWlhcq9jZaSNY2WjpcYUodhYqWNoIgloIwmFhK6gIgoVWVhb6kkxmfn6S2QMf+8nk/fd/fn4ys1mW5Zk1Cj7XyEO6nXs01zAMuGOFaMS4CBsQ24ELSgYnBEGtkQSx7nTz3P0iCJJH5hwpv+GpkydPPTN4spwm8ROwFCMp5Dkp5aY2IcTFsix3+RIGk3tb8IU8bDab7cCi97DoraqqphgvY/4b9lEpJbsI2qHY4pbyxcXy8qJeoBMRuB7UtTqLxV5ixxOXVkixDu4v7AGUC17Z+tFNvTqIQym1ipY+RYLHTdPspzIjIlrs+Aa07WIWk8mkxPwb7BNyFS6kj4ojr+v6BIJewTYOTadV24h4lOZhVVkdg/4DCrniHJgfhH3RJvDsR3mjwQjC0xC+wXgX1e81CraqnSaqoVxuunISuf7AnmNTO4nXwwjO8xC9k1LcbA43S1wwBL+OvsH6zgghHyHvL6XUGvFaaAEWvgTBFiq9VhTFIvX/L5D3Auw7cp/hPgM4T6Hdn1HE1Srxrur2d1Wz9tqBkP3m9eVdQze38Doe7wURzGZH+y5IcR03fSk49Mzl7vlAQQi7uHyL17FpqQV0YR3r7OFaCncP3usLOG4vYAcXRDsSyYTFES6f6dFx4/F4H7j7Kyuru8OIELl7BXE8G9J/dQjCVIg7AHuNmJ/Y8VdnmP+APcnMhyhrQ8l52XMMEubYxREEvoA9hKnuaAKphfkQ6a+e/RgRE3q8rdcJ/jloMZSlFDqh+3qnLKv2TKk3CA6RliQ8CXou8kRolGwx5OuRyqzROvibQ+fJWIpONE/NEtN7YR55vJtzvkVP5/aj0yWKhCQIjw40Q+BiPp8LUm0M/FgoqMu/8277w8kjEQw969URFzv4XlMCoYZj07BxLtqO/wCdRmV3JvHirgAAAABJRU5ErkJggg==>