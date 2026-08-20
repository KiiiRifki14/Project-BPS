# **🚨 PROMPT PERBAIKAN: WAJIB PILIH LABEL DOKUMEN SEBELUM UNGGAH**

Mohon perbaiki logika pengunggahan dokumen agar setiap berkas **WAJIB** dipilihkan label kategorinya (seperti BAPP Honor, Kuitansi, KAK, dll) sebelum tombol unggah dapat diproses:

### **1\. Perbaikan Validasi Frontend (resources/views/items/show.blade.php)**

Pada elemen \<select\> pilihan label untuk setiap berkas yang dipilih, tambahkan atribut required dan jadikan opsi default bertipe disabled selected:

\<select   
    :name="'labels\[' \+ index \+ '\]'"   
    required  
    class="text-xs rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 bg-slate-50 py-1.5 px-2.5 text-slate-700"  
\>  
    \<option value="" disabled selected\>-- Pilih Label Dokumen (Wajib) \--\</option\>  
    \<option value="BAPP Honor"\>BAPP Honor\</option\>  
    \<option value="Kuitansi"\>Kuitansi\</option\>  
    \<option value="KAK"\>KAK (Kerangka Acuan Kerja)\</option\>  
    \<option value="SK Petugas"\>SK Petugas\</option\>  
    \<option value="Daftar Hadir"\>Daftar Hadir / Penerima\</option\>  
    \<option value="SPJ Perjalanan Dinas"\>SPJ Perjalanan Dinas\</option\>  
    \<option value="Lainnya"\>Dokumen Pendukung Lainnya\</option\>  
\</select\>

### **2\. Perbaikan Validasi Backend (app/Http/Controllers/DocumentController.php)**

Di dalam method store() pada DocumentController.php, pastikan array labels wajib diisi (required) untuk setiap item berkas yang dikirim:

$request-\>validate(\[  
    'documents'   \=\> 'required|array|min:1',  
    'documents.\*' \=\> 'required|file|mimes:pdf,jpg,jpeg,png|max:15360',  
    'labels'      \=\> 'required|array',  
    'labels.\*'    \=\> 'required|string',  
\], \[  
    'documents.required' \=\> 'Minimal harus memilih 1 berkas untuk diunggah.',  
    'labels.\*.required'  \=\> 'Setiap berkas yang diunggah wajib dipilihkan label kategorinya.',  
\]);

### **📝 CATATAN EKSEKUSI OLEH AI DEV**

*Mohon isi bagian di bawah ini setelah selesai mengeksekusi perbaikan di atas:*

* Status Perbaikan Bug ini (Selesai/Belum selesai) : Selesai
* Hari dan tanggal perbaikan : Rabu, 19 Agustus 2026