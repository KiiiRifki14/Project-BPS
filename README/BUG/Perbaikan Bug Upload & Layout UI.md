# **🚨 PROMPT PERBAIKAN BUG UPLOAD & TAMPILAN LAYOUT UI**

Mohon perbaiki 3 bug berikut secara langsung pada source code proyek Laravel:

### **1\. Fix Fatal Error: Disk \[private\] does not have a configured driver.**

**Penyebab:** Controller memanggil Storage::disk('private') atau $file-\>storeAs(..., 'private'), namun disk 'private' belum terdaftar di config/filesystems.php.

**Langkah Perbaikan:**

Buka file config/filesystems.php dan tambahkan konfigurasi disk 'private' di dalam array 'disks':

'disks' \=\> \[

    'local' \=\> \[  
        'driver' \=\> 'local',  
        'root' \=\> storage\_path('app/private'),  
        'serve' \=\> true,  
        'throw' \=\> false,  
    \],

    'private' \=\> \[  
        'driver' \=\> 'local',  
        'root' \=\> storage\_path('app/private'),  
        'serve' \=\> true,  
        'throw' \=\> false,  
    \],

    'public' \=\> \[  
        'driver' \=\> 'local',  
        'root' \=\> storage\_path('app/public'),  
        'url' \=\> env('APP\_URL').'/storage',  
        'visibility' \=\> 'public',  
        'throw' \=\> false,  
    \],

    // ...  
\],

### **2\. Perbaiki Tampilan Saling Tumpah Tindih pada List File Dipilih**

**Penyebab:** Input label memiliki ukuran tidak fleksibel dan menimpa teks nama/ukuran file di dalam kontainer flex.

**Langkah Perbaikan di resources/views/items/show.blade.php:**

Bungkus setiap item berkas yang dipilih dalam kontainer flexbox yang rapi (flex items-center gap-3 w-full p-3 bg-white border border-slate-200 rounded-lg shadow-sm). Berikan min-w-0 flex-1 pada bagian informasi nama file agar tidak saling bertabrakan.

### **3\. Ubah Input Label Manual Menjadi Dropdown \<select\>**

**Langkah Perbaikan di resources/views/items/show.blade.php:**

Ganti elemen \<input type="text" placeholder="Label (misal: BAPP / Kuitansi)"\> menjadi elemen \<select\> dengan pilihan kategori resmi BPS berikut:

\<select name="labels\[\]" class="text-xs rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 bg-slate-50 py-1.5 px-2.5 text-slate-700"\>  
    \<option value=""\>-- Pilih Label Dokumen \--\</option\>  
    \<option value="BAPP Honor"\>BAPP Honor\</option\>  
    \<option value="Kuitansi"\>Kuitansi\</option\>  
    \<option value="KAK"\>KAK (Kerangka Acuan Kerja)\</option\>  
    \<option value="SK Petugas"\>SK Petugas\</option\>  
    \<option value="Daftar Hadir"\>Daftar Hadir / Penerima\</option\>  
    \<option value="SPJ Perjalanan Dinas"\>SPJ Perjalanan Dinas\</option\>  
    \<option value="Lainnya"\>Dokumen Pendukung Lainnya\</option\>  
\</select\>

### **Contoh Struktur HTML / Alpine.js Berkas Dipilih yang Rapi:**

\<\!-- Container Berkas Dipilih \--\>  
\<div class="space-y-2 mt-4"\>  
    \<template x-for="(file, index) in selectedFiles" :key="index"\>  
        \<div class="flex items-center justify-between gap-3 p-3 bg-white border border-slate-200 rounded-xl shadow-sm"\>  
            \<\!-- Icon & File Info \--\>  
            \<div class="flex items-center gap-3 min-w-0 flex-1"\>  
                \<div class="p-2 bg-blue-50 text-blue-600 rounded-lg shrink-0"\>  
                    \<i data-lucide="file-text" class="w-5 h-5"\>\</i\>  
                \</div\>  
                \<div class="min-w-0 flex-1"\>  
                    \<p class="text-xs font-semibold text-slate-800 truncate" x-text="file.name"\>\</p\>  
                    \<p class="text-\[10px\] text-slate-500" x-text="(file.size / 1024).toFixed(1) \+ ' KB'"\>\</p\>  
                \</div\>  
            \</div\>

            \<\!-- Dropdown Label Kategori \--\>  
            \<div class="shrink-0"\>  
                \<select :name="'labels\[' \+ index \+ '\]'" class="text-xs rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 bg-slate-50 py-1.5 px-2.5 text-slate-700"\>  
                    \<option value=""\>-- Pilih Label \--\</option\>  
                    \<option value="BAPP Honor"\>BAPP Honor\</option\>  
                    \<option value="Kuitansi"\>Kuitansi\</option\>  
                    \<option value="KAK"\>KAK\</option\>  
                    \<option value="SK Petugas"\>SK Petugas\</option\>  
                    \<option value="Daftar Hadir"\>Daftar Hadir\</option\>  
                    \<option value="SPJ Perjalanan Dinas"\>SPJ Perjalanan Dinas\</option\>  
                    \<option value="Lainnya"\>Lainnya\</option\>  
                \</select\>  
            \</div\>

            \<\!-- Tombol Hapus Pilihan \--\>  
            \<button type="button" @click="removeFile(index)" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors shrink-0"\>  
                \<i data-lucide="x" class="w-4 h-4"\>\</i\>  
            \</button\>  
        \</div\>  
    \</template\>  
\</div\>  

======
Status: Selesai Diperbaiki
Hari/Tanggal: Rabu, 19 Agustus 2026
