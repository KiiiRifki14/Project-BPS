# **🚨 PROMPT PERBAIKAN LOGIKA VERIFIKASI & UI BENDAHARA**

Mohon perbaiki 4 bug berikut secara langsung pada source code Blade & Alpine.js Laravel:

### **1\. Tambahkan Search Box Kode Item pada Menu Verifikasi (/verification) & Directory (/items)**

**Penyebab:** Belum ada input pencarian di halaman /verification, memaksa pengguna mencari secara manual.

**Langkah Perbaikan di resources/views/verification/index.blade.php & resources/views/arsip/index.blade.php:**

Tambahkan input pencarian real-time/form pencarian kode item di bagian atas tabel antrean:

\<\!-- Input Search Box Kode Item / Nama Kegiatan \--\>  
\<div class="mb-4 flex gap-3"\>  
    \<div class="relative flex-1"\>  
        \<input   
            type="text"   
            name="search"   
            value="{{ request('search') }}"   
            placeholder="🔍 Ketik Kode Item (misal: 001366\) atau Kata Kunci Kegiatan..."   
            class="w-full rounded-xl border-slate-300 pl-10 pr-4 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"  
        \>  
    \</div\>  
    \<button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl transition-colors shrink-0"\>  
        Cari Item  
    \</button\>  
\</div\>

### **2\. Perbaiki Tombol Pratinjau (Preview / Eye Icon) yang Tidak BISA Dipencet**

**Penyebab:** Event handler @click atau pemicu modal PDF openPreview() di file Blade belum terhubung dengan state Alpine.js modal previewer.

**Langkah Perbaikan di resources/views/items/show.blade.php:**

Pastikan tombol preview di dalam tabel dokumen maupun di panel ceklis memanggil fungsi modal previewer dengan benar:

\<button   
    type="button"   
    @click="$dispatch('open-preview-modal', { url: '{{ route('documents.stream', $doc-\>id) }}', title: '{{ $doc-\>file\_name }}' })"   
    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors shrink-0"  
\>  
    \<i data-lucide="eye" class="w-3.5 h-3.5 text-slate-600"\>\</i\>  
    \<span\>Preview\</span\>  
\</button\>

### **3\. Ketat Kunci Tombol "Setujui Pencairan (APPROVED)" Saat Ceklis Belum 100%**

**Penyebab:** Tombol Setujui Pencairan belum memiliki atribut :disabled dan class disabled: pada Alpine.js state.

**Langkah Perbaikan di resources/views/items/show.blade.php:**

Bungkus Panel Verifikasi Bendahara dalam Alpine.js state yang memperhitungkan jumlah dokumen yang dicentang vs total dokumen terunggah:

\<div x-data="{  
    checkedDocs: {},  
    totalDocs: {{ $item-\>documents-\>count() }},  
    get checkedCount() {  
        return Object.values(this.checkedDocs).filter(Boolean).length;  
    },  
    get canApprove() {  
        return this.totalDocs \> 0 && this.checkedCount \=== this.totalDocs;  
    }  
}"\>  
    \<\!-- Checklist Items \--\>  
    \<template x-for="docId in docIds"\>  
        \<\!-- Render Checkbox bound to checkedDocs\[docId\] \--\>  
    \</template\>

    \<\!-- Tombol Setujui Pencairan \--\>  
    \<button   
        type="submit"   
        name="action"   
        value="APPROVED"   
        :disabled="\!canApprove"  
        :class="canApprove ? 'bg-emerald-600 hover:bg-emerald-700 cursor-pointer text-white shadow-md' : 'bg-slate-300 text-slate-500 cursor-not-allowed opacity-60'"  
        class="w-full py-3 px-4 font-semibold text-sm rounded-xl transition-all duration-200 flex items-center justify-center gap-2"  
    \>  
        \<i data-lucide="check-circle" class="w-4 h-4"\>\</i\>  
        \<span\>Setujui Pencairan (Approved)\</span\>  
    \</button\>  
\</div\>

### **4\. Sembunyikan / Matikan Tombol Merah "Tolak / Minta Revisi" Jika Status Sudah APPROVED**

**Penyebab:** Tombol penolakan masih dirender aktif meskipun status item sudah APPROVED.

**Langkah Perbaikan di resources/views/items/show.blade.php:**

Lakukan kondisional Blade @if($item-\>verification\_status \!== 'APPROVED') di sekitar tombol penolakan merah:

@if($item-\>verification\_status \=== 'APPROVED')  
    \<\!-- Banner Informasi Item Locked \--\>  
    \<div class="p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-2.5 text-emerald-800 text-xs font-semibold"\>  
        \<i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0"\>\</i\>  
        \<span\>Item ini telah disetujui oleh Bendahara. Status terkunci.\</span\>  
    \</div\>  
@else  
    \<\!-- Tombol Merah Tolak / Minta Revisi (Hanya tampil jika BELUM Approved) \--\>  
    \<button   
        type="button"   
        @click="showRejectModal \= true"   
        class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-sm rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2"  
    \>  
        \<i data-lucide="x-circle" class="w-4 h-4"\>\</i\>  
        \<span\>Tolak / Minta Revisi\</span\>  
    \</button\>  
@endif

### **📝 CATATAN EKSEKUSI OLEH AI DEV**

*Mohon isi bagian di bawah ini setelah selesai mengeksekusi seluruh perbaikan di atas:*

* Status Perbaikan Bug ini (Selesai/Belum selesai) : Selesai
* Hari dan tanggal perbaikan : Rabu, 19 Agustus 2026