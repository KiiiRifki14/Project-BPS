# **Evaluasi Tampilan UI & Solusi Perbaikan Layout (Konsep Hybrid Directory & Search-First)**

## **❌ Diagnosa Masalah UX Tampilan**

1. **Efek "Box Inception" (Accordion Bertumpuk di Body Utama)**:  
   * AI merender 8 tingkat hirarki POK sebagai *accordion* yang bersarang di tengah layar.  
   * Tampilan menjadi sangat sempit, penuh garis batas (*border*), dan membingungkan.  
2. **Jebakan "Akar Liar" jika Seluruh Tree Masuk Sidebar**:  
   * Menaruh 8 tingkat pohon POK di sidebar selebar w-80 akan membuat sidebar sangat panjang, memicu *horizontal scrollbar*, dan memaksa pengguna mengeklik panah *expand* 7 kali untuk sampai ke level Item.

## **✅ Konsep Tampilan Ideal (Search-First \+ Cascading Directory)**

Sistem profesional untuk data keuangan berhirarki dalam menggunakan 3 pilar UX:

1. **Sidebar Tetap Ringkas**: Hanya memuat **6 Menu Utama Sistem** (Dashboard, Arsip POK, Verifikasi, Master, Laporan, User) \+ Tombol Filter Cepat.  
2. **Pencarian Cepat / Instant Jump (Search-First)**:  
   * Operator/Bendahara biasanya sudah tahu kode item (misal: 001366\) atau kata kunci ("Honor Sensus").  
   * Cukup ketik di **Kotak Pencarian Utama**, pilih hasil pencarian, dan sistem langsung membuka **Workspace Item Detail** dalam 1 klik\!  
3. **Cascading Filter Bar / Folder Directory (Jika Ingin Browsing Manual)**:  
   * Di bagian atas halaman utama, sediakan *Dropdown Filter* bertahap atau *Folder Card Grid*:  
     \[Pilih Program ▾\] \-\> \[Pilih Output ▾\] \-\> \[Pilih Sub-Output ▾\]  
   * Setelah Sub-Output dipilih (misal: BMA.006 Sensus Ekonomi), halaman tengah langsung menampilkan **Tabel Daftar Item Kegiatan** yang rapi lengkap dengan nominal Pagu, Badge Status, dan Jumlah File Terunggah.

## **🎨 Visualisasi Mockup Layout V2**

\+-------------------------------------------------------------------------------------------------------+  
|  \[LOGO BPS\] Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang      \[2026\] \[User Info / Role\]    |  
\+----------------------------------+--------------------------------------------------------------------+  
|  SIDEBAR UTAMA (Fixed w-64)      | MAIN WORKSPACE / EXPLORER AREA                                     |  
|  \------------------------------  |                                                                    |  
|  \[📊\] Dashboard Utama            | 🔍 KOTAK PENCARIAN INSTAN (SEARCH-FIRST):                          |  
|  \[📁\] Arsip Keuangan POK  ⭐     | \[ 🔍 Ketik Kode Item (001366), Akun (521213), atau Kata Kunci... \] |  
|  \[✅\] Verifikasi Pencairan \[8\]   |                                                                    |  
|  \[⚙️\] Kelola Master POK           | \--- ATAU FILTER MANUAL (CASCADING BREADCRUMB FILTER) \------------- |  
|  \[📈\] Laporan & Rekapitulasi     | \[ Program: GG.2902 ▾ \]  \[ Output: BMA ▾ \]  \[ SubOutput: BMA.006 ▾ \]|  
|  \[👥\] Manajemen Pengguna         |                                                                    |  
|                                  | 📌 PETA KEGIATAN: BMA.006 \- PUBLIKASI/LAPORAN SENSUS EKONOMI       |  
|                                  | \+------------------------------------------------------------------+  
|  QUICK JUMP FAVORIT:             | | TABEL DAFTAR ITEM KEGIATAN UNDER BMA.006                          |  
|  • BMA.006 Sensus Ekonomi 🟠     | \+--------+-----------------------------+----------------+----------+  
|  • 001366 Honor Sensus 🟢        | | Kode   | Nama Item Kegiatan          | Pagu (Rp)      | Status   |  
|  • FAN.ZZ1 Direktif Presiden     | \+--------+-----------------------------+----------------+----------+  
|                                  | | 001366 | Honor Petugas Pendataan S...| 925.600.000    | \[PENDING\]| \-\> Klik Buka Workspace  
|                                  | | 001510 | Honor Pemeriksa Lapangan ...|  12.190.000    | \[APPROVED|  
|                                  | | 001211 | Honor Petugas Lapangan S...| 12.176.268.000 | \[PENDING\]|  
|                                  | \+--------+-----------------------------+----------------+----------+  
\+----------------------------------+--------------------------------------------------------------------+

## **🚀 PROMPT REVISI LAYOUT V2 UNTUK AI CODING AGENT**

Salin (*copy*) teks prompt di bawah ini dan berikan ke AI Coding Agent kamu untuk merombak UI menjadi sangat rapi dan mudah digunakan:

HALT AND REFACTOR UI LAYOUT IMMEDIATELY TO "SEARCH-FIRST DIRECTORY BROWSER"\!

The current layout uses 8-level nested accordions inside the main content ("box inception"), which is unusable. Do NOT move all 8 levels into a cramped sidebar either, as it creates a messy "tree depth hell".

Implement a clean, modern "Search-First \+ Cascading Directory Explorer" UI following these exact rules:

1\. SIDEBAR NAVIGATION (Left Column \- Fixed Width w-64, Dark Navy bg-\[\#001F54\]):  
   \- Keep sidebar simple with ONLY the 6 Core System Menus:  
     1\. Dashboard (\`/dashboard\`)  
     2\. Arsip Keuangan POK (\`/items\`)  
     3\. Verifikasi Pencairan (\`/verification\`)  
     4\. Kelola Master POK (\`/master\`)  
     5\. Laporan & Rekapitulasi (\`/reports\`)  
     6\. Manajemen Pengguna (\`/users\`)  
   \- Add a "Quick Access / Shortcuts" section at the bottom of the sidebar for priority modules:  
     \* \`BMA.006 Sensus Ekonomi\` (Highlighted in BPS Orange \`\#F39C12\`)  
     \* \`Item 001366 Honor Sensus\`

2\. ARCHIVE BROWSER PAGE (\`/items\` \- Main Body Area):  
   \- TOP SECTION: Prominent Real-Time Search Bar. Typing item code (e.g., "001366") or name immediately filters and displays matching items in a clean dropdown list for 1-click navigation.  
   \- MIDDLE SECTION: Cascading Filter Dropdowns (\`Program\` \-\> \`Output\` \-\> \`SubOutput\`). Selecting \`BMA.006\` filters the item table below automatically.  
   \- MAIN SECTION: Clean Data Table listing Items under the selected SubOutput/Account:  
     \* Columns: Item Code, Item Title/Name, Pagu Anggaran (\`font-mono text-emerald-700 font-bold\`), Uploaded Docs Count, Verification Status Badge (\`APPROVED\`/\`PENDING\`/\`REJECTED\`), Action Button ("Buka Workspace / Detail").

3\. ITEM WORKSPACE DETAIL PAGE (\`/items/{id}\`):  
   When an item (e.g. 001366\) is selected:  
   \- Clean Breadcrumb trail showing POK path (\`GG.2902 \> BMA \> BMA.006 \> 005 \> 521213 \> 001366\`).  
   \- Header Card: Item Title, Large Bold Pagu Badge, and Status Badge.  
   \- Drag-and-Drop Multi-file Dropzone Area supporting PDF/PNG/JPG uploads with custom Label input (e.g., "BAPP Honor", "Kuitansi").  
   \- Uploaded Documents Table with Inline Stream PDF Preview Modal (Eye icon), Download, and Delete actions.  
   \- Bendahara Action Control Bar (Visible for BENDAHARA/ADMIN role): Approve button (\`APPROVED\`) and Reject button (\`REJECTED\` with mandatory rejection note modal).

4\. BRANDING & HEADER:  
   \- Header brand title: "Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang".

Apply this refactoring now to make the web app clean, highly performant, and intuitive for daily administrative use.  
