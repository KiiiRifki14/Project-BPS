# **🚨 PROMPT PERBAIKAN: KHUSUSKAN PANEL VERIFIKASI UNTUK ROLE BENDAHARA ONLY**

Mohon sesuaikan hak akses Panel Verifikasi Bendahara agar secara ketat (*Separation of Duties*) **HANYA** dapat diakses dan dieksekusi oleh pengguna dengan role **BENDAHARA**.

Role ADMIN tidak boleh melihat atau mengeksekusi verifikasi pencairan keuangan.

### **1\. Perbaikan Tampilan Blade (resources/views/items/show.blade.php)**

Ubah pengecekan kondisi @if pada container Panel Verifikasi Bendahara:

**Sebelum:**

@if(in\_array(auth()-\>user()-\>role, \['BENDAHARA', 'ADMIN'\]))  
    \<\!-- Panel Verifikasi Bendahara \--\>  
@endif

**Sesudah:**

@if(auth()-\>user()-\>role \=== 'BENDAHARA')  
    \<\!-- Panel Verifikasi Bendahara (Khusus Bendahara) \--\>  
@endif

### **2\. Perbaikan Navigasi Sidebar (resources/views/layouts/sidebar.blade.php)**

Pastikan menu **Verifikasi Pencairan** (/verification) di sidebar juga hanya tampil untuk role BENDAHARA:

@if(auth()-\>user()-\>role \=== 'BENDAHARA')  
    \<a href="{{ route('verification.index') }}" class="nav-item"\>  
        \<i data-lucide="check-circle-2"\>\</i\>  
        \<span\>Verifikasi Pencairan\</span\>  
    \</a\>  
@endif

### **3\. Perbaikan Backend Guard (app/Http/Controllers/ItemController.php & VerificationController.php)**

Pada method verify() yang menangani aksi persetujuan/penolakan, pastikan hanya role BENDAHARA yang diizinkan memproses:

if (auth()-\>user()-\>role \!== 'BENDAHARA') {  
    abort(403, 'Akses ditolak: Hanya Bendahara Pengeluaran yang berwenang melakukan verifikasi pencairan.');  
}

### **📝 CATATAN EKSEKUSI OLEH AI DEV**

*Mohon isi bagian di bawah ini setelah selesai mengeksekusi perbaikan di atas:*

* Status Perbaikan Bug ini (Selesai/Belum selesai) : Selesai
* Hari dan tanggal perbaikan : Kamis, 20 Agustus 2026