# **Matriks Navigasi Sidebar per Role (RBAC)**

**Nama Resmi Sistem:** Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang

**Dokumen Acuan:** PRD v2.0 & Implementation Plan Laravel 11

## **📌 Ringkasan Tampilan Sidebar per Peran (Actor Visibility)**

Sistem menggunakan **Dynamic Sidebar Rendering** berdasarkan kolom role pada tabel users. Setiap pengguna hanya melihat menu yang relevan dengan tanggung jawab kerjanya.

| Nama Menu & Rute | Icon Lucide | Operator | Bendahara | Supervisor | Admin | Deskripsi & Indikator Khusus |
| :---- | :---- | :---- | :---- | :---- | :---- | :---- |
| **Dashboard Utama** /dashboard | layout-dashboard | ✅ | ✅ | ✅ | ✅ | Ringkasan KPI Pagu, Realisasi, & Banner Sensus Ekonomi (BMA.006). |
| **Arsip Keuangan POK** /items | folder-tree | ✅ | ✅ | ✅ | ✅ | Treeview 8-level POK \+ Search Box. Workspace Upload / Review Berkas. |
| **Verifikasi Pencairan** /verification | check-circle-2 | ❌ | ✅ | ❌ | ✅ | Inbox persetujuan Bendahara \+ Badge Counter PENDING (e.g., 12 Pending). |
| **Kelola Master POK** /master | database | ❌ | ❌ | ✅ | ✅ | CRUD struktur POK dinamis per tahun anggaran (2026, 2027, dst.). |
| **Laporan & Rekapitulasi** /reports | file-bar-chart | ✅ | ✅ | ✅ | ✅ | Rekapitulasi status kelengkapan SPJ per unit \+ Ekspor Excel/PDF. |
| **Manajemen Pengguna** /users | users | ❌ | ❌ | ❌ | ✅ | Portal Admin untuk kelola akun, atur role, dan reset password. |

## **🎨 Visualisasi Tampilan Sidebar Menurut Peran Pengguna**

### **1\. Tampilan Sidebar: OPERATOR (Petugas Input Berkas SPJ)**

> **Fokus Peran:** Menelusuri POK, mengunggah multi-file SPJ/BAPP/Kuitansi, dan mengecek status verifikasi.

\+-------------------------------------------------------+  
|  \[LOGO BPS\] BPS KABUPATEN SUBANG                      |  
|  Sistem Data Digital Arsip Keuangan                   |  
\+-------------------------------------------------------+  
|  NAVIGASI UTAMA                                       |  
|  \[📊\] Dashboard Utama                                 |  
|  \[📁\] Arsip Keuangan POK (Treeview)                   |  
|  \[📈\] Laporan & Rekapitulasi                          |  
\+-------------------------------------------------------+  
|  INFO PENGGUNA                                        |  
|  👤 Ahmad Operator                                    |  
|  🏷️ Role: OPERATOR                                    |  
|  \[🚪\] Keluar                                          |  
\+-------------------------------------------------------+

### **2\. Tampilan Sidebar: BENDAHARA (Pencairan & Validator)**

> **Fokus Peran:** Memeriksa berkas pertanggungjawaban via *inline preview* dan mengeksekusi *Approve / Reject*.

\+-------------------------------------------------------+  
|  \[LOGO BPS\] BPS KABUPATEN SUBANG                      |  
|  Sistem Data Digital Arsip Keuangan                   |  
\+-------------------------------------------------------+  
|  NAVIGASI UTAMA                                       |  
|  \[📊\] Dashboard Utama                                 |  
|  \[📁\] Arsip Keuangan POK (Treeview)                   |  
|  \[✅\] Verifikasi Pencairan          \[ 8 Pending \] 🟡   | \<--- Badge Khusus Antrean  
|  \[📈\] Laporan & Rekapitulasi                          |  
\+-------------------------------------------------------+  
|  INFO PENGGUNA                                        |  
|  👤 Hj. Didin Bendahara                               |  
|  🏷️ Role: BENDAHARA                                   |  
|  \[🚪\] Keluar                                          |  
\+-------------------------------------------------------+

### **3\. Tampilan Sidebar: SUPERVISOR (Penanggung Jawab Kegiatan)**

> **Fokus Peran:** Memantau ketercapaian rekapitulasi dan memelihara struktur master data POK jika ada revisi DIPA.

\+-------------------------------------------------------+  
|  \[LOGO BPS\] BPS KABUPATEN SUBANG                      |  
|  Sistem Data Digital Arsip Keuangan                   |  
\+-------------------------------------------------------+  
|  NAVIGASI UTAMA                                       |  
|  \[📊\] Dashboard Utama                                 |  
|  \[📁\] Arsip Keuangan POK (Treeview)                   |  
|  \[⚙️\] Kelola Master Data POK                          |  
|  \[📈\] Laporan & Rekapitulasi                          |  
\+-------------------------------------------------------+  
|  INFO PENGGUNA                                        |  
|  👤 Budi Supervisor                                   |  
|  🏷️ Role: SUPERVISOR                                  |  
|  \[🚪\] Keluar                                          |  
\+-------------------------------------------------------+

### **4\. Tampilan Sidebar: ADMIN (System Administrator)**

> **Fokus Peran:** Akses penuh ke seluruh fitur sistem, termasuk manajemen user dan master data.

\+-------------------------------------------------------+  
|  \[LOGO BPS\] BPS KABUPATEN SUBANG                      |  
|  Sistem Data Digital Arsip Keuangan                   |  
\+-------------------------------------------------------+  
|  NAVIGASI UTAMA                                       |  
|  \[📊\] Dashboard Utama                                 |  
|  \[📁\] Arsip Keuangan POK (Treeview)                   |  
|  \[✅\] Verifikasi Pencairan                            |  
|  \[⚙️\] Kelola Master Data POK                          |  
|  \[📈\] Laporan & Rekapitulasi                          |  
|  \[👥\] Manajemen Pengguna                              |  
\+-------------------------------------------------------+  
|  INFO PENGGUNA                                        |  
|  👤 Admin Utama                                       |  
|  🏷️ Role: ADMIN                                       |  
|  \[🚪\] Keluar                                          |  
\+-------------------------------------------------------+

## **🛠️ Logika Implementasi Kode Blade Sidebar (sidebar.blade.php)**

\<\!-- Sidebar Navigation Item List \--\>  
\<nav class="mt-4 space-y-1 px-3"\>

    \<\!-- 1\. Dashboard (Semua Role) \--\>  
    \<a href="{{ route('dashboard') }}" class="nav-item"\>  
        \<i data-lucide="layout-dashboard"\>\</i\>  
        \<span\>Dashboard Utama\</span\>  
    \</a\>

    \<\!-- 2\. Arsip Keuangan POK Treeview (Semua Role) \--\>  
    \<a href="{{ route('items.index') }}" class="nav-item"\>  
        \<i data-lucide="folder-tree"\>\</i\>  
        \<span\>Arsip Keuangan POK\</span\>  
    \</a\>

    \<\!-- 3\. Verifikasi Pencairan (Khusus BENDAHARA & ADMIN) \--\>  
    @if(in\_array(auth()-\>user()-\>role, \['BENDAHARA', 'ADMIN'\]))  
    \<a href="{{ route('verification.index') }}" class="nav-item flex justify-between items-center"\>  
        \<div class="flex items-center space-x-2"\>  
            \<i data-lucide="check-circle-2"\>\</i\>  
            \<span\>Verifikasi Pencairan\</span\>  
        \</div\>  
        @if($pendingCount \> 0\)  
            \<span class="bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"\>  
                {{ $pendingCount }}  
            \</span\>  
        @endif  
    \</a\>  
    @endif

    \<\!-- 4\. Kelola Master Data POK (Khusus SUPERVISOR & ADMIN) \--\>  
    @if(in\_array(auth()-\>user()-\>role, \['SUPERVISOR', 'ADMIN'\]))  
    \<a href="{{ route('master.index') }}" class="nav-item"\>  
        \<i data-lucide="database"\>\</i\>  
        \<span\>Kelola Master POK\</span\>  
    \</a\>  
    @endif

    \<\!-- 5\. Laporan & Rekapitulasi (Semua Role) \--\>  
    \<a href="{{ route('reports.index') }}" class="nav-item"\>  
        \<i data-lucide="file-bar-chart"\>\</i\>  
        \<span\>Laporan & Rekapitulasi\</span\>  
    \</a\>

    \<\!-- 6\. Manajemen Pengguna (Khusus ADMIN) \--\>  
    @if(auth()-\>user()-\>role \=== 'ADMIN')  
    \<a href="{{ route('users.index') }}" class="nav-item"\>  
        \<i data-lucide="users"\>\</i\>  
        \<span\>Manajemen Pengguna\</span\>  
    \</a\>  
    @endif

\</nav\>  
