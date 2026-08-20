# **🛡️ Katalog Lengkap Celah Keamanan Web (Web Vulnerabilities) & Panduan Mitigasi**

Dokumen ini menyajikan panduan komprehensif mengenai jenis-jenis celah keamanan yang ada pada aplikasi berbasis web global (berdasarkan standar **OWASP Top 10**, **CWE Top 25**, dan **NIST**), penjelasan teknis cara kerjanya, dampak yang ditimbulkan, serta cara penanganan (*remediation*) yang presisi untuk developer dan tim *security*.

## **📌 Daftar Isi**

1. [Broken Access Control & IDOR](#bookmark=id.6bikg5v62o5x)  
2. [Injection (SQLi, Command Injection, SSTI)](#bookmark=id.msssz6ucfbi2)  
3. [Cryptographic Failures & Sensitive Data Exposure](#bookmark=id.omnte1y5fsuw)  
4. [Cross-Site Scripting (XSS)](#bookmark=id.i49rgphmxvle)  
5. [Insecure Design & Business Logic Flaws](#bookmark=id.wwnhdtm0i2mv)  
6. [Security Misconfiguration & Default Credentials](#bookmark=id.ygips71fh5yb)  
7. [Vulnerable and Outdated Components](#bookmark=id.yom74zh8k03y)  
8. [Identification & Authentication Failures](#bookmark=id.68mpv33uouuv)  
9. [Software and Data Integrity Failures](#bookmark=id.uubjxh8bobo5)  
10. [Server-Side Request Forgery (SSRF)](#bookmark=id.an24pmw0asme)  
11. [Cross-Site Request Forgery (CSRF)](#bookmark=id.xx8dia2y53vu)  
12. [File Upload Vulnerabilities](#bookmark=id.jzajqmmhxnyv)  
13. [XML External Entity (XXE) Injection](#bookmark=id.kfzx9i8oo7fp)  
14. [Insecure Deserialization](#bookmark=id.c248u7p5zy50)  
15. [Insufficient Rate Limiting & DoS Vulnerabilities](#bookmark=id.mdq6p4qjemq)  
16. [API-Specific Vulnerabilities (BOLA, BFLA)](#bookmark=id.gibmyc3xzpdv)

## **1\. Broken Access Control & IDOR**

### **🔍 Deskripsi**

Kegagalan sistem dalam membatasi hak akses pengguna sesuai peran (*role*) dan wewenangnya. Termasuk di dalamnya adalah **Insecure Direct Object Reference (IDOR)**, di mana peretas dapat mengakses resource pengguna lain hanya dengan mengubah parameter ID pada URL/API Request.

### **💥 Dampak**

Penyerang dapat melihat, mengubah, atau menghapus data pengguna lain, hingga melakukan pengambilalihan wewenang admin (*privilege escalation*).

### **🛠️ Cara Penanganan**

* **Enforce Role-Based Access Control (RBAC) / Attribute-Based Access Control (ABAC):** Selalu lakukan verifikasi otorisasi di tingkat server pada setiap *endpoint* API atau *controller action*.  
* **Gunakan Indirect/Randomized References:** Jangan gunakan ID auto-increment mendasar (misal /invoice/102). Gunakan **UUID v4** (misal /invoice/f47ac10b-58cc-4372-a567-0e02b2c3d479).  
* **Prinsip Deny by Default:** Secara otomatis tolak seluruh akses API kecuali jika otorisasi didefinisikan secara eksplisit.

## **2\. Injection (SQLi, Command Injection, SSTI)**

### **🔍 Deskripsi**

Terjadi ketika data tidak terpercaya (*untrusted input*) dikirimkan ke interpreter sebagai bagian dari perintah atau kueri tanpa validasi atau sanitasi terlebih dahulu.

* **SQL Injection (SQLi):** Memanipulasi query database.  
* **OS Command Injection:** Eksekusi perintah sistem operasi server.  
* **Server-Side Template Injection (SSTI):** Memanipulasi *template engine* (Jinja, Blade, Twig) untuk mengeksekusi kode di server.

### **💥 Dampak**

Pencurian seluruh isi database (SQLi), kebocoran kredensial, hingga *Remote Code Execution (RCE)* yang mengambil alih kontrol penuh server.

### **🛠️ Cara Penanganan**

* **SQLi:** Selalu gunakan **Prepared Statements / Parameterized Queries** atau ORM (Eloquent, Hibernate, TypeORM). Jangan pernah melakukan konkatenasi string secara langsung pada kueri database.  
  // BAD (Rentan SQLi):  
  $user \= DB::select("SELECT \* FROM users WHERE email \= '$input'");

  // GOOD (Aman):  
  $user \= DB::select("SELECT \* FROM users WHERE email \= ?", \[$input\]);

* **Command Injection:** Hindari fungsi eksekusi perintah OS lokal (seperti exec(), shell\_exec(), system()). Jika terpaksa, gunakan library aman dengan sanitasi argumen ketat.  
* **SSTI:** Bebaskan variabel input dari *raw rendering* pada template engine; gunakan metode *escaping* bawaan.

## **3\. Cryptographic Failures & Sensitive Data Exposure**

### **🔍 Deskripsi**

Kegagalan dalam melindungi data sensitif (password, nomor kartu kredit, token JWT, data pribadi/PII) saat ditransmisikan (*in transit*) maupun disimpan (*at rest*).

### **💥 Dampak**

Kebocoran data massal (data breach), identitas pengguna tercuri, dan sanksi regulasi hukum (GDPR, UU PDP).

### **🛠️ Cara Penanganan**

* **Enkripsi Data in Transit:** Wajibkan HTTPS (TLS 1.2 / TLS 1.3) dan aktifkan **HSTS (HTTP Strict Transport Security)**.  
* **Hashing Password Aman:** Jangan gunakan algoritma usang seperti MD5 atau SHA1. Wajib gunakan **Bcrypt, Argon2id, atau PBKDF2**.  
* **Enkripsi Data at Rest:** Gunakan algoritma enkripsi standar industri seperti **AES-256-GCM** untuk penyimpanan database/file sensitif.  
* **Sembunyikan Secrets:** Jangan memasukkan kunci API, password DB, atau master key ke dalam repository *source code* (Gunakan .env & Secret Manager).

## **4\. Cross-Site Scripting (XSS)**

### **🔍 Deskripsi**

Terjadi ketika aplikasi memasukkan data yang disuplai pengguna ke dalam halaman web tanpa validasi atau penanganan karakter khusus HTML/JS.

* **Stored XSS:** Script jahat disimpan permanen di database server (misal di kolom komentar).  
* **Reflected XSS:** Script jahat dikirim via parameter URL dan dipantulkan langsung oleh server.  
* **DOM-based XSS:** Manipulasi struktur DOM aplikasi oleh script sisi klien (*JavaScript*).

### **💥 Dampak**

Pencurian cookie sesi (*session hijacking*), pembacaan token autentikasi, *redirect* ke situs phishing, dan eksekusi aksi atas nama pengguna (deface/keylogging).

### **🛠️ Cara Penanganan**

* **Context-Aware Output Encoding/Escaping:** Selalu lakukan sanitasi output saat menampilkan data pengguna ke HTML. Konversi karakter berbahaya (\<, \>, &, ", ') menjadi HTML Entity.  
* **Gunakan Content Security Policy (CSP):** Atur header HTTP Content-Security-Policy untuk melarang eksekusi *inline script* dan membatasi sumber script eksternal.  
* **Proteksi Cookie Sesi:** Atur atribut cookie sesi ke HttpOnly (mencegah JS membaca cookie), Secure (hanya via HTTPS), dan SameSite=Strict/Lax.

## **5\. Insecure Design & Business Logic Flaws**

### **🔍 Deskripsi**

Celah yang bukan disebabkan oleh bug kodingan tunggal, melainkan kegagalan arsitektur dan logika bisnis aplikasi. Contohnya: diskon harga barang yang bisa diisi nilai negatif (-100000), alur checkout tanpa validasi stok, atau reset password tanpa batasan verifikasi.

### **💥 Dampak**

Kerugian finansial langsung bagi pemilik bisnis, pemanfaatan sistem secara gratis, atau manipulasi fitur transaksi.

### **🛠️ Cara Penanganan**

* **Threat Modeling:** Lakukan pemodelan ancaman (*Threat Modeling*) saat fase perancangan arsitektur sistem.  
* **Validasi Server-Side Logika Bisnis:** Jangan memercayai input harga, kuantitas, atau status transaksi dari sisi frontend/client.  
* **Atomic Transactions & Lock:** Gunakan transaksi database dan mekanisme penguncian (*pessimistic/optimistic locking*) untuk mencegah manipulasi paralel (*race conditions*).

## **6\. Security Misconfiguration & Default Credentials**

### **🔍 Deskripsi**

Pengaturan sistem yang tidak aman atau ketinggalan zaman pada web server, database, framework, atau *cloud infrastructure*. Contohnya: *Debug mode* dinyalakan di production, direktori *Directory Listing* terbuka, atau password bawaan belum diubah.

### **💥 Dampak**

Memudahkan penyerang mengumpulkan informasi arsitektur internal server, membaca *stack trace*, hingga masuk dengan kredensial default.

### **🛠️ Cara Penanganan**

* **Matikan Debug Mode:** Matikan mode debug di lingkungan *Production* (misal pada Laravel: APP\_DEBUG=false).  
* **Hapus Akun & Password Default:** Ganti kredensial bawaan router, server, database, dan CMS saat pertama kali deployment.  
* **Gunakan Security Headers:** Konfigurasi web server (Nginx/Apache) dengan header keamanan wajib:  
  * X-Content-Type-Options: nosniff  
  * X-Frame-Options: DENY / SAMEORIGIN  
  * Referrer-Policy: strict-origin-when-cross-origin  
  * Permissions-Policy

## **7\. Vulnerable and Outdated Components**

### **🔍 Deskripsi**

Menggunakan library, pustaka pihak ketiga, atau framework open-source yang memiliki celah keamanan (*CVE*) yang telah dipublikasikan dan belum diperbarui (*patch*). Contoh: Menggunakan versi Laravel, React, atau WordPress plugin yang usang.

### **💥 Dampak**

Penyerang dapat menggunakan *exploit script* publik (seperti di Exploit-DB/Metasploit) untuk meretas aplikasi tanpa perlu menemukan celah baru secara manual.

### **🛠️ Cara Penanganan**

* **Gunakan Tools Automated SCA (Software Composition Analysis):** Integrasikan pemindaian dependen pada CI/CD pipeline (misal: npm audit, composer audit, Snyk, Dependabot).  
* **Manajemen Patch Rutin:** Lakukan pembaruan versi library dan prapustaka secara berkala.  
* **Minimalisir Dependency:** Hanya gunakan paket/library yang terpercaya dan aktif dipelihara oleh komunitas.

## **8\. Identification & Authentication Failures**

### **🔍 Deskripsi**

Celah pada mekanisme otentikasi yang memungkinkan penyerang menebak, mencuri, atau memalsukan identitas pengguna lain. Termasuk serangan *Credential Stuffing*, *Brute Force*, *Session Fixation*, dan password lemah.

### **💥 Dampak**

Pengambilalihan akun pengguna/admin (*Account Takeover \- ATO*).

### **🛠️ Cara Penanganan**

* **Terapkan Multi-Factor Authentication (MFA/2FA):** Wajibkan MFA terutamanya untuk akun bertipe Administrator/Privileged.  
* **Rate Limiting & Account Lockout:** Batasi percobaan login gagal (misal maksimal 5 kali per menit) untuk mencegah *brute-force*.  
* **Kebijakan Password Kuat:** Hindari pembatasan karakter yang kaku, tetapi larang password pasaran (Gunakan NIST SP 800-63B guidelines).  
* **Proteksi Sesi:** Regenerasi ID Sesi secara otomatis setiap kali pengguna berhasil login/logout (*Session Regeneration*).

## **9\. Software and Data Integrity Failures**

### **🔍 Deskripsi**

Terjadi ketika kode atau infrastruktur tidak memverifikasi integritas software update, data sensitif, atau CI/CD pipeline. Contoh: Mengunduh library via koneksi HTTP tak terenkripsi atau menerima update firmware tanpa verifikasi tanda tangan digital (*digital signature*).

### **💥 Dampak**

Serangan rantai pasokan (*Supply Chain Attack*), pemuatan malware berbahaya langsung ke dalam sistem aplikasi utama.

### **🛠️ Cara Penanganan**

* **Verifikasi Tanda Tangan Digital:** Wajibkan pemeriksaan *checksum* (SHA-256) atau *digital signature* sebelum memuat pembaruan sistem atau library eksternal.  
* **Amankan CI/CD Pipeline:** Terapkan kontrol akses ketat, autentikasi kuat, dan audit log pada repositori kode dan server deployment.

## **10\. Server-Side Request Forgery (SSRF)**

### **🔍 Deskripsi**

Celah di mana penyerang dapat memaksa server aplikasi web untuk membuat HTTP Request ke destinasi internal atau eksternal yang tidak semestinya.

### **💥 Dampak**

Penyerang dapat memindai port jaringan internal server, mengakses metadata *cloud* (misal AWS EC2 Instance Metadata Service 169.254.169.254), dan bypass firewall internal.

### **🛠️ Cara Penanganan**

* **Allowlist IP & Domain:** Batasi server aplikasi agar hanya dapat melakukan permintaan keluar (*outbound request*) ke domain/IP spesifik yang diizinkan saja.  
* **Blokir Alamat IP Lokal/Privat:** Larang server memanggil IP internal loopback (127.0.0.1, localhost) dan rentang IP privat (10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16, 169.254.169.254).  
* **Matikan HTTP Redirect Handling:** Konfigurasi HTTP Client agar tidak otomatis mengikuti pengalihan URL (*HTTP Redirects*).

## **11\. Cross-Site Request Forgery (CSRF)**

### **🔍 Deskripsi**

Serangan yang memaksa pengguna terautentikasi untuk mengeksekusi aksi yang tidak diinginkan pada aplikasi web di mana mereka sedang aktif login.

### **💥 Dampak**

Perubahan email, perubahan password, transaksi transfer dana otomatis tanpa sepengetahuan korban.

### **🛠️ Cara Penanganan**

* **Anti-CSRF Token:** Sertakan token rahasia acak (*CSRF Token*) unik per sesi pada setiap formulir POST, PUT, PATCH, DELETE.  
* **Cookie SameSite Attribute:** Atur atribut cookie ke SameSite=Lax atau SameSite=Strict.  
* **Konfirmasi Kritis:** Wajibkan verifikasi ulang password atau input OTP/MFA untuk tindakan sensitif.

## **12\. File Upload Vulnerabilities**

### **🔍 Deskripsi**

Pengunggahan file tanpa validasi tipe, ukuran, atau konten yang benar, memungkinkan penyerang mengunggah skrip berbahaya (seperti file Web Shell .php, .jsp, .asp).

### **💥 Dampak**

*Remote Code Execution (RCE)*, pengambilalihan kontrol penuh server web, dan serangan phishing via SVG terinfeksi.

### **🛠️ Cara Penanganan**

* **Validasi MIME Type & Extention (Allowlist):** Hanya izinkan ekstensi spesifik (misal .pdf, .jpg, .png). Jangan pernah gunakan *blocklist*.  
* **Re-encode & Strip Metadata:** Lakukan proses ulang gambar (misal re-sampling via GD/ImageMagick) untuk membersihkan skrip tersembunyi.  
* **Gunakan Random Filename:** Ganti nama file yang diunggah dengan string acak (misal UUID).  
* **Simpan di Luar Web Root / Private Storage:** Simpan file di direktori non-executable yang terpisah dari root web aplikasi atau simpan di Cloud Storage (AWS S3, Google Cloud Storage) yang terisolasi.

## **13\. XML External Entity (XXE) Injection**

### **🔍 Deskripsi**

Terjadi pada parser XML usang yang memproses masukan XML yang memuat referensi ke entitas eksternal.

### **💥 Dampak**

Pembacaan file sensitif di server (/etc/passwd, file konfigurasi), SSRF, dan Denial of Service (*Billion Laughs Attack*).

### **🛠️ Cara Penanganan**

* **Matikan DTD (Document Type Definition):** Konfigurasi parser XML agar menonaktifkan pemrosesan external entity dan DTD secara total.  
  // PHP (libxml)  
  libxml\_disable\_entity\_loader(true);

* **Gunakan Format Data yang Lebih Aman:** Utamakan penggunaan format data JSON dibandingkan XML jika memungkinkan.

## **14\. Insecure Deserialization**

### **🔍 Deskripsi**

Terjadi ketika data yang diserialisasi (*serialized object*) dibaca dan diubah kembali menjadi objek hidup oleh aplikasi tanpa validasi integritas.

### **💥 Dampak**

*Remote Code Execution (RCE)*, bypass autentikasi, dan manipulasi status aplikasi.

### **🛠️ Cara Penanganan**

* **Hindari Deserialisasi Data dari Client:** Jangan menerima objek ter-serialisasi dari masukan pengguna yang tidak terpercaya.  
* **Digital Signature / HMAC:** Jika harus menggunakan objek ter-serialisasi, gunakan tanda tangan digital (HMAC) untuk memverifikasi integritas data sebelum proses deserialisasi dijalankan.

## **15\. Insufficient Rate Limiting & DoS Vulnerabilities**

### **🔍 Deskripsi**

Ketidakmampuan server membatasi frekuensi dan jumlah permintaan HTTP dari satu alamat IP atau pengguna dalam rentang waktu tertentu.

### **💥 Dampak**

Layanan *down* (Denial of Service \- DoS), biaya infrastruktur/cloud membengkak, dan *resource exhaustion* (CPU/RAM/Database Lock).

### **🛠️ Cara Penanganan**

* **Implementasi Rate Limiter:** Terapkan pembatasan rate limit berbasis IP atau User ID (misal: maksimum 60 request per menit per endpoint).  
* **Web Application Firewall (WAF) & DDoS Protection:** Gunakan layanan proteksi seperti Cloudflare atau AWS Shield.  
* **Asynchronous Queue:** Gunakan sistem antrean (*message queue* seperti Redis/RabbitMQ) untuk memproses pekerjaan berat secara terpisah.

## **16\. API-Specific Vulnerabilities (BOLA, BFLA, Excessive Data Exposure)**

### **🔍 Deskripsi**

Celah keamanan spesifik pada REST API atau GraphQL:

* **BOLA (Broken Object Level Authorization):** Sama seperti IDOR pada API.  
* **BFLA (Broken Function Level Authorization):** Kegagalan membatasi akses ke metode API sensitif (misal endpoint /api/admin/users bisa diakses oleh user biasa).  
* **Excessive Data Exposure:** API mengembalikan data JSON lengkap (termasuk hash password atau data pribadi) dan hanya menyaringnya di sisi frontend UI.

### **💥 Dampak**

Kebocoran massal data JSON sensitif dan manipulasi sistem via endpoint API publik yang tidak terproteksi.

### **🛠️ Cara Penanganan**

* **Saring Data di Backend (API Response DTO / Transformers):** Jangan kirim data penuh ke frontend. Gunakan API Transformer/Resources untuk membuang atribut sensitif sebelum dikirim ke pengguna.  
* **Terapkan Guard pada Seluruh HTTP Verb:** Pastikan otorisasi berlaku ketat untuk metode GET, POST, PUT, DELETE, dan PATCH.

## **📋 Ringkasan Check-List Keamanan Developer (Developer Security Checklist)**

| No | Area Keamanan | Aksi Wajib |
| :---- | :---- | :---- |
| 1 | **Database** | Gunakan Prepared Statements / ORM untuk semua query. |
| 2 | **Authentication** | Aktifkan MFA, Rate Limiting, dan Hashing Argon2/Bcrypt. |
| 3 | **Authorization** | Terapkan pengecekan RBAC/ABAC di tiap endpoint backend. |
| 4 | **Input Handling** | Validasi input (*allowlist*) & lakukan output encoding (Sanitasi). |
| 5 | **Data Protection** | Wajibkan HTTPS (TLS 1.3), HSTS, dan atur cookie HttpOnly, Secure, SameSite. |
| 6 | **File Upload** | Ubah nama file, validasi ekstensi, simpan di private storage terisolasi. |
| 7 | **Infrastructure** | Matikan Debug Mode di Production, perbarui library rutin (audit). |

