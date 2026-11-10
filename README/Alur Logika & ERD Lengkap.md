# **📊 Alur Logika & ERD Lengkap**

## **Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang**

**Stack:** Laravel 11 (PHP 8.2) · MySQL · Tailwind CSS · Alpine.js

## **1\. ERD — Entity Relationship Diagram (Skema Database Lengkap)**

erDiagram  
    fiscal\_years {  
        bigint id PK  
        smallint year UK "e.g. 2026"  
        boolean is\_active "default: true"  
        timestamp created\_at  
        timestamp updated\_at  
    }

    programs {  
        bigint id PK  
        bigint fiscal\_year\_id FK  
        varchar code "e.g. GG.2902"  
        varchar name  
        timestamp created\_at  
        timestamp updated\_at  
    }

    outputs {  
        bigint id PK  
        bigint program\_id FK  
        varchar code "e.g. BMA, FAN"  
        varchar name  
        timestamp created\_at  
        timestamp updated\_at  
    }

    sub\_outputs {  
        bigint id PK  
        bigint output\_id FK  
        varchar code "e.g. BMA.006, FAN.ZZ1"  
        varchar name  
        timestamp created\_at  
        timestamp updated\_at  
    }

    components {  
        bigint id PK  
        bigint sub\_output\_id FK  
        varchar code "e.g. 005, 051, 530"  
        varchar name  
        timestamp created\_at  
        timestamp updated\_at  
    }

    sub\_components {  
        bigint id PK  
        bigint component\_id FK  
        varchar code "e.g. 005.0A, 530.0B"  
        varchar name  
        timestamp created\_at  
        timestamp updated\_at  
    }

    accounts {  
        bigint id PK  
        bigint sub\_component\_id FK  
        varchar code "e.g. 521213, 524114"  
        varchar name  
        timestamp created\_at  
        timestamp updated\_at  
    }

    items {  
        bigint id PK  
        bigint account\_id FK  
        varchar code "e.g. 001366, 001211"  
        varchar name  
        decimal pagu "15,2 — default 0 (Raw numeric in DB, formatted as Rp in UI)"  
        enum verification\_status "PENDING|APPROVED|REJECTED"  
        text rejection\_note "nullable"  
        timestamp created\_at  
        timestamp updated\_at  
    }

    documents {  
        bigint id PK  
        bigint item\_id FK  
        bigint uploaded\_by\_user\_id FK  
        varchar file\_name "Original filename"  
        varchar stored\_file\_name "UUID-based on disk"  
        varchar file\_path "Relative private path"  
        bigint file\_size "bytes"  
        varchar file\_type "pdf|jpg|jpeg|png"  
        varchar label "nullable — BAPP, Kuitansi"  
        timestamp created\_at  
        timestamp updated\_at  
    }

    users {  
        bigint id PK  
        varchar nip\_username UK "max 50 char"  
        varchar name "max 100 char"  
        enum role "ADMIN|SUPERVISOR|OPERATOR|BENDAHARA"  
        varchar password "bcrypt hashed"  
        varchar remember\_token "nullable"  
        timestamp created\_at  
        timestamp updated\_at  
    }

    fiscal\_years ||--o{ programs        : "has many (cascade)"  
    programs      ||--o{ outputs         : "has many (cascade)"  
    outputs       ||--o{ sub\_outputs     : "has many (cascade)"  
    sub\_outputs   ||--o{ components      : "has many (cascade)"  
    components    ||--o{ sub\_components  : "has many (cascade)"  
    sub\_components||--o{ accounts        : "has many (cascade)"  
    accounts      ||--o{ items           : "has many (cascade)"  
    items         ||--o{ documents       : "has many (cascade)"  
    users         ||--o{ documents       : "uploaded\_by\_user\_id (cascade)"

## **2\. Hirarki POK 8-Level (Contoh Data Nyata MVP)**

graph TD  
    FY\["🗓️ fiscal\_years\\nTahun Anggaran: 2026\\nis\_active \= true"\]  
    P\["📁 programs\\nGG.2902\\nStatistik Dasar"\]  
    O1\["📂 outputs\\nBMA\\nStatistik Dasar BMA"\]  
    O2\["📂 outputs\\nFAN\\nFasilitasi & Pembinaan"\]  
    SO1\["📦 sub\_outputs\\nBMA.004"\]  
    SO2\["📦 sub\_outputs\\n⭐ BMA.006\\nPublikasi Sensus Ekonomi"\]  
    SO3\["📦 sub\_outputs\\nFAN.ZZ1"\]  
    C1\["🔶 components\\n005 / 051 / 052"\]  
    C2\["🔶 components\\n523 / 530 / 535"\]  
    C3\["🔶 components\\n524 / 529"\]  
    SC1\["🔷 sub\_components\\n005.0A / 005.0B"\]  
    SC2\["🔷 sub\_components\\n530.0B"\]  
    SC3\["🔷 sub\_components\\n524.0A"\]  
    ACC1\["💳 accounts\\n521213 — Honor Output\\n521211 — Uang Harian"\]  
    ACC2\["💳 accounts\\n524113 — Perjalanan Biasa"\]  
    ACC3\["💳 accounts\\n524114 — Perjalanan Dinas"\]  
    I1\["📋 items\\n001366 — Honor Petugas Pendataan\\nPagu: Rp 925.600.000"\]  
    I2\["📋 items\\n001211 — Honor Petugas Lapangan\\nPagu: Rp 325.200.000"\]  
    I3\["📋 items\\n001351 — Paket Meeting Fullboard\\nPagu: Rp 1.200.000.000"\]  
    DOC\["📄 documents\\nBAPP.pdf / Kuitansi.png\\nSPJ Honor Sensus"\]

    FY \--\> P  
    P \--\> O1  
    P \--\> O2  
    O1 \--\> SO1  
    O1 \--\> SO2  
    O2 \--\> SO3  
    SO1 \--\> C1  
    SO2 \--\> C2  
    SO3 \--\> C3  
    C1 \--\> SC1  
    C2 \--\> SC2  
    C3 \--\> SC3  
    SC1 \--\> ACC1  
    SC2 \--\> ACC2  
    SC3 \--\> ACC3  
    ACC1 \--\> I1  
    ACC1 \--\> I2  
    ACC2 \--\> I3  
    I1 \--\> DOC  
    I2 \--\> DOC

    style FY fill:\#e0f2fe,stroke:\#0284c7  
    style P fill:\#dbeafe,stroke:\#2563eb  
    style SO2 fill:\#fef3c7,stroke:\#d97706,color:\#92400e,font-weight:bold  
    style I1 fill:\#fef3c7,stroke:\#d97706,color:\#92400e,font-weight:bold  
    style I2 fill:\#fef3c7,stroke:\#d97706,color:\#92400e,font-weight:bold  
    style DOC fill:\#dcfce7,stroke:\#16a34a

## **3\. RBAC — Hak Akses Per Role (Role-Based Access Control)**

graph LR  
    subgraph ADMIN\["👑 ADMIN — Akses Penuh"\]  
        A1\["/dashboard"\]  
        A2\["/items — Browse"\]  
        A3\["/items/{id} — Workspace"\]  
        A4\["/verification — Inbox"\]  
        A5\["/master — CRUD POK"\]  
        A6\["/reports"\]  
        A7\["/users — Kelola User"\]  
        A8\["Upload Dokumen"\]  
        A9\["Hapus Dokumen"\]  
        A10\["Verify/Approve/Reject"\]  
    end

    subgraph SUPERVISOR\["🔵 SUPERVISOR"\]  
        S1\["/dashboard"\]  
        S2\["/items — Browse"\]  
        S3\["/items/{id} — Workspace"\]  
        S4\["/master — CRUD POK"\]  
        S5\["/reports"\]  
        S6\["Upload Dokumen"\]  
        S7\["Hapus Dokumen"\]  
    end

    subgraph OPERATOR\["🟢 OPERATOR"\]  
        OP1\["/dashboard"\]  
        OP2\["/items — Browse"\]  
        OP3\["/items/{id} — Workspace"\]  
        OP4\["/reports"\]  
        OP5\["Upload Dokumen milik sendiri"\]  
        OP6\["Hapus Dokumen milik sendiri"\]  
    end

    subgraph BENDAHARA\["🟡 BENDAHARA"\]  
        B1\["/dashboard"\]  
        B2\["/items — Browse"\]  
        B3\["/items/{id} — Workspace"\]  
        B4\["/verification — Inbox"\]  
        B5\["/reports"\]  
        B6\["Approve / Reject Item"\]  
        B7\["Preview Dokumen PDF"\]  
    end

    subgraph BLOCKED\["🚫 DILARANG / TIDAK ADA AKSES"\]  
        X1\["BENDAHARA: Upload/Hapus Dok"\]  
        X2\["OPERATOR: /verification"\]  
        X3\["OPERATOR: /master"\]  
        X4\["OPERATOR: /users"\]  
        X5\["SUPERVISOR: /verification"\]  
        X6\["SUPERVISOR: /users"\]  
    end

## **4\. Alur Autentikasi & Middleware**

sequenceDiagram  
    actor User as 👤 User (Browser)  
    participant Auth as Laravel Auth (Breeze)  
    participant Role as RoleMiddleware  
    participant Controller as Controller  
    participant View as Blade View

    User-\>\>Auth: POST /login (nip\_username \+ password)  
    Auth-\>\>Auth: Validasi kredensial DB users

    alt Gagal Login  
        Auth--\>\>User: Redirect /login \+ error "Kredensial tidak valid"  
    else Berhasil Login  
        Auth--\>\>User: Redirect /dashboard (Session aktif)  
    end

    User-\>\>Role: GET /master (akses menu terproteksi)  
    Role-\>\>Role: Cek auth()-\>user()-\>role  
      
    alt Role TIDAK sesuai  
        Role--\>\>User: abort(403) atau redirect /dashboard  
    else Role SESUAI  
        Role-\>\>Controller: Teruskan request  
        Controller-\>\>View: return view('master.index', \[...\])  
        View--\>\>User: Render halaman  
    end

## **5\. Alur Utama: Upload Dokumen SPJ (Operator)**

flowchart TD  
    A(\[🟢 Operator Login\]) \--\> B\[Buka /items — Search-First Directory\]  
    B \--\> C\[Pilih Item Kegiatan\\ne.g. 001366 Honor Sensus\]  
    C \--\> D\[Klik 'Workspace →'\]  
    D \--\> E\[Halaman /items/id\\nBreadcrumb \+ Header Card\]  
    E \--\> F\[Drag & Drop file ke Dropzone\\nPDF/JPG/PNG — max 15MB\]  
    F \--\> G\[Isi Label Dokumen\\ne.g. BAPP Honor, Kuitansi\]  
    G \--\> H{POST /items/id/documents}  
      
    H \--\> I{Guard 1:\\nverification\_status \=== APPROVED?}  
    I \-- YA \--\> J\[❌ Error Toast:\\n'Item sudah disetujui Bendahara.\\nDokumen tidak dapat diubah.'\]  
    J \--\> E  
      
    I \-- TIDAK \--\> K{Validasi File:\\nmimes:pdf,jpg,jpeg,png\\nmax:15360 KB?}  
    K \-- GAGAL \--\> L\[❌ Validation Error\\nKembali ke Form\]  
    L \--\> E  
      
    K \-- LOLOS \--\> M\[Generate UUID filename\\nTentukan storagePath:\\nuploads/year/subOutput/itemCode\]  
    M \--\> N\[Storage::disk'private'\\n→storeAs path, UUID.ext\]  
    N \--\> O\[Document::create record DB\\nitem\_id, file\_path, file\_size,\\nfile\_type, uploaded\_by\_user\_id, label\]  
    O \--\> P{Apakah status Item\\nsebelumnya REJECTED?}  
    P \-- YA \--\> Q\[UPDATE items SET\\nverification\_status \= PENDING,\\nrejection\_note \= NULL\]  
    P \-- TIDAK \--\> R\[Tetapkan/Pertahankan\\nstatus PENDING\]  
    Q \--\> S\[✅ Success Toast:\\n'Dokumen berhasil diunggah.\\nStatus direset ke PENDING untuk re-review'\]  
    R \--\> S  
    S \--\> E

    style J fill:\#fee2e2,stroke:\#dc2626  
    style L fill:\#fee2e2,stroke:\#dc2626  
    style S fill:\#dcfce7,stroke:\#16a34a  
    style I fill:\#fef3c7,stroke:\#d97706  
    style P fill:\#fef3c7,stroke:\#d97706

## **6\. Alur Utama: Verifikasi Pencairan (Bendahara)**

flowchart TD  
    A(\[🟡 Bendahara Login\]) \--\> B\[Buka /verification\\nInbox Verifikasi Pencairan\]  
    B \--\> C\[Lihat daftar Item PENDING\\nyang memiliki dokumen terunggah\]  
    C \--\> D\[Klik item untuk membuka\\n/items/id Workspace\]  
    D \--\> E\[Review Dokumen:\\nKlik 'Pratinjau' → Modal PDF Inline\]  
    E \--\> F{Keputusan Bendahara}

    F \-- SETUJUI \--\> G{PATCH /items/id/verify\\naction \= APPROVED}  
    G \--\> H{Guard 2:\\ndocuments count \=== 0?}  
    H \-- YA 0 dokumen \--\> I\[❌ Error Toast:\\n'Gagal menyetujui: Minimal harus\\nada 1 dokumen SPJ/BAPP terunggah.'\]  
    I \--\> D

    H \-- TIDAK Ada Dok \--\> J\[UPDATE items SET\\nverification\_status \= APPROVED\\nrejection\_note \= NULL\]  
    J \--\> K\[✅ Toast: 'Item 001366\\nberhasil disetujui — Siap Cair'\]  
    K \--\> L\[Badge Item berubah\\nmenjadi 🟢 SIAP CAIR\]

    F \-- TOLAK \--\> M\[Isi Catatan Penolakan\\nwajib input rejection\_note\]  
    M \--\> N{PATCH /items/id/verify\\naction \= REJECTED}  
    N \--\> O{rejection\_note terisi?}  
    O \-- KOSONG \--\> P\[❌ Validation Error:\\n'Catatan penolakan wajib diisi'\]  
    P \--\> D  
    O \-- TERISI \--\> Q\[UPDATE items SET\\nverification\_status \= REJECTED\\nrejection\_note \= catatan\]  
    Q \--\> R\[❌ Badge Item berubah\\nmenjadi 🔴 DITOLAK\]  
    R \--\> S\[Operator dapat membaca\\ncatatan revisi di Workspace\]

    style I fill:\#fee2e2,stroke:\#dc2626  
    style P fill:\#fee2e2,stroke:\#dc2626  
    style K fill:\#dcfce7,stroke:\#16a34a  
    style L fill:\#dcfce7,stroke:\#16a34a  
    style H fill:\#fef3c7,stroke:\#d97706

## **7\. Alur Guard 1: Lock Dokumen Item APPROVED**

flowchart LR  
    A\["Operator/User\\ncoba Upload/Hapus Dokumen"\] \--\> B{"Item.verification\_status\\n=== APPROVED?"}  
    B \-- YA \--\> C\["❌ return back()\\n-\>with('error', ...)\\n\\nHTTP 302 Redirect\\n+ Session Error Flash"\]  
    B \-- TIDAK \--\> D\["✅ Lanjut proses\\nUpload / Hapus normal"\]

    C \--\> E\["Toast Error muncul di UI:\\n'Item ini sudah disetujui oleh Bendahara.\\nDokumen tidak dapat diubah lagi.'"\]

    style C fill:\#fee2e2,stroke:\#dc2626  
    style D fill:\#dcfce7,stroke:\#16a34a  
    style E fill:\#fef3c7,stroke:\#d97706

## **8\. Alur Guard 3: Garbage Collection File Fisik**

flowchart TD  
    A\["User / Cascade Delete\\ntrigger Document::delete()"\] \--\> B\["Eloquent Model Event:\\nstatic::deleting listener di Document.php"\]  
    B \--\> C{"document-\>file\_path\\nada & tidak null?"}  
    C \-- TIDAK \--\> D\["Skip — tidak ada file\\ndi disk untuk dihapus"\]  
    C \-- YA \--\> E{"Storage::disk('private')\\n-\>exists(file\_path)?"}  
    E \-- TIDAK ADA \--\> F\["File sudah hilang\\n(ghost record) — Skip"\]  
    E \-- ADA \--\> G\["Storage::disk('private')\\n-\>delete(file\_path)\\n\\nFile fisik dihapus dari:\\nstorage/app/private/uploads/..."\]  
    G \--\> H\["Database row Document\\ndihapus oleh Eloquent\\n(normal delete flow)"\]  
    D \--\> H  
    F \--\> H

    style G fill:\#dcfce7,stroke:\#16a34a,font-weight:bold  
    style H fill:\#dbeafe,stroke:\#2563eb

## **9\. Alur Penyimpanan & Streaming File Privat**

flowchart LR  
    subgraph UPLOAD \["📤 Upload Phase"\]  
        U1\["File di-upload\\nvia Dropzone"\] \--\> U2\["storeAs:\\nstorage/app/private/\\nuploads/2026/BMA\_006/001366/\\nUUID.pdf"\]  
        U2 \--\> U3\["Record DB:\\ndocuments.file\_path \=\\n'uploads/2026/BMA\_006/001366/UUID.pdf'"\]  
    end

    subgraph STREAM \["📺 Stream / Preview Phase"\]  
        S1\["User klik 'Pratinjau'\\nGET /documents/id/stream"\] \--\> S2{"auth()-\>user()\\nexists?"}  
        S2 \-- TIDAK \--\> S3\["abort(403)\\nAutentikasi diperlukan"\]  
        S2 \-- YA \--\> S4\["Storage::disk('private')\\n-\>path(document-\>file\_path)"\]  
        S4 \--\> S5{"file\_exists\\ndi disk?"}  
        S5 \-- TIDAK \--\> S6\["abort(404)\\nFile tidak ditemukan"\]  
        S5 \-- YA \--\> S7\["response()-\>file(path)\\nContent-Type: application/pdf\\nContent-Disposition: inline"\]  
        S7 \--\> S8\["PDF terbuka di\\nModal Browser\\ntanpa download paksa"\]  
    end

    UPLOAD \--\> STREAM

    style U2 fill:\#dbeafe,stroke:\#2563eb  
    style S8 fill:\#dcfce7,stroke:\#16a34a  
    style S3 fill:\#fee2e2,stroke:\#dc2626  
    style S6 fill:\#fee2e2,stroke:\#dc2626

## **10\. State Machine: Status Verifikasi Item**

stateDiagram-v2  
    \[\*\] \--\> PENDING : Item dibuat\\n(Master CRUD)

    PENDING \--\> APPROVED : Bendahara APPROVE\\n\[Guard: docs count ≥ 1\]  
    PENDING \--\> REJECTED : Bendahara REJECT\\n\[Wajib rejection\_note\]  
    REJECTED \--\> PENDING : Operator upload dokumen\\nbaru & minta re-review  
    APPROVED \--\> APPROVED : LOCKED — tidak bisa\\nkembali ke PENDING/REJECTED

    note right of PENDING  
        Operator bisa:  
        ✅ Upload dokumen  
        ✅ Hapus dokumen sendiri  
    end note

    note right of APPROVED  
        🔒 Guard 1 AKTIF  
        ❌ Upload diblokir  
        ❌ Hapus diblokir  
        ✅ Preview masih bisa  
        ✅ Download masih bisa  
    end note

    note right of REJECTED  
        Operator bisa:  
        ✅ Baca rejection\_note  
        ✅ Upload perbaikan  
        ✅ Hapus & ganti dokumen  
    end note

## **11\. Alur Lengkap: End-to-End Workflow (Satu Siklus Pencairan)**

sequenceDiagram  
    actor Admin as 👑 Admin/Supervisor  
    actor Operator as 🟢 Operator  
    actor Bendahara as 🟡 Bendahara  
    participant DB as 🗄️ MySQL DB  
    participant Disk as 💾 Private Disk\\nstorage/app/private

    Note over Admin,DB: FASE 1 — SETUP MASTER DATA  
    Admin-\>\>DB: POST /master/items\\n\[Tambah Item 001366, Pagu, Akun\]  
    DB--\>\>Admin: Item PENDING dibuat ✅

    Note over Operator,Disk: FASE 2 — UPLOAD SPJ  
    Operator-\>\>DB: GET /items/001366 (Workspace)  
    DB--\>\>Operator: Item detail \+ dokumen kosong  
    Operator-\>\>Disk: POST /items/001366/documents\\n\[Upload BAPP.pdf \+ Kuitansi.png\]  
    Disk--\>\>Disk: Simpan UUID.pdf di\\nuploads/2026/BMA\_006/001366/  
    Disk--\>\>DB: Document::create record\\n\[file\_path, file\_size, label, user\_id\]  
    DB--\>\>Operator: ✅ "2 dokumen berhasil diunggah"

    Note over Bendahara,DB: FASE 3 — VERIFIKASI  
    Bendahara-\>\>DB: GET /verification (Inbox)  
    DB--\>\>Bendahara: List item PENDING dengan dokumen  
    Bendahara-\>\>Disk: GET /documents/1/stream (Preview PDF)  
    Disk--\>\>Bendahara: Stream inline PDF (Content-Disposition: inline)

    alt DISETUJUI  
        Bendahara-\>\>DB: PATCH /items/001366/verify\\n\[action=APPROVED\]  
        DB--\>\>DB: Guard 2: docs.count() ≥ 1? ✅  
        DB--\>\>DB: UPDATE items SET\\nverification\_status=APPROVED  
        DB--\>\>Bendahara: ✅ "Siap Cair" — Badge Hijau

        Note over Operator,DB: FASE 4 — PROTEKSI POST-APPROVAL (Guard 1\)  
        Operator-\>\>DB: POST /items/001366/documents\\n\[coba tambah file baru\]  
        DB--\>\>DB: Guard 1: status=APPROVED → BLOKIR  
        DB--\>\>Operator: ❌ "Dokumen tidak dapat diubah"

    else DITOLAK  
        Bendahara-\>\>DB: PATCH /items/001366/verify\\n\[action=REJECTED, rejection\_note=...\]  
        DB--\>\>DB: UPDATE items SET\\nverification\_status=REJECTED\\nrejection\_note=catatan  
        DB--\>\>Bendahara: ❌ "Ditolak" — Badge Merah  
        DB--\>\>Operator: Operator membaca catatan revisi  
        Note over Operator,DB: Operator perbaiki & upload ulang → kembali FASE 2 (Status reset ke PENDING)  
    end

    Note over Admin,Disk: FASE 5 — HAPUS (Guard 3 Garbage Collection)  
    Admin-\>\>DB: DELETE /documents/1  
    DB--\>\>DB: Eloquent deleting event terpanggil  
    DB-\>\>Disk: Storage::disk('private')-\>delete(file\_path)  
    Disk--\>\>Disk: File fisik dihapus ✅  
    DB--\>\>DB: Database row Document dihapus ✅  
    DB--\>\>Admin: ✅ "Dokumen berhasil dihapus"

## **12\. Route Map & Controller Mapping**

graph LR  
    subgraph PUBLIC\["🔓 Public Routes (auth.php)"\]  
        R0\["GET /login\\nPOST /login\\nPOST /logout"\]  
    end

    subgraph AUTH\["🔐 Auth Middleware Group"\]  
        subgraph ALL\_ROLES\["Semua Role"\]  
            R1\["GET / → DashboardController@index"\]  
            R2\["GET /items → ArsipController@index"\]  
            R3\["GET /items/{item} → ItemController@show"\]  
            R5\["POST /items/{item}/documents → DocumentController@store"\]  
            R6\["GET /documents/{doc}/stream → DocumentController@stream"\]  
            R7\["GET /documents/{doc}/download → DocumentController@download"\]  
            R8\["DELETE /documents/{doc} → DocumentController@destroy"\]  
            R9\["GET /reports → ReportController@index"\]  
        end

        subgraph BEND\_ADMIN\["role:BENDAHARA,ADMIN"\]  
            R4\["PATCH /items/{item}/verify → ItemController@verify"\]  
            R10\["GET /verification → VerificationController@index"\]  
        end

        subgraph SUP\_ADMIN\["role:SUPERVISOR,ADMIN"\]  
            R11\["GET /master → MasterController@index"\]  
            R12\["POST /master/fiscal-years"\]  
            R13\["POST|PATCH|DELETE /master/programs"\]  
            R14\["POST /master/outputs"\]  
            R15\["POST /master/sub-outputs"\]  
            R16\["POST /master/components"\]  
            R17\["POST /master/sub-components"\]  
            R18\["POST /master/accounts"\]  
            R19\["POST|PATCH|DELETE /master/items"\]  
        end

        subgraph ADMIN\_ONLY\["role:ADMIN"\]  
            R20\["GET /users → UserController@index"\]  
            R21\["POST /users → UserController@store"\]  
            R22\["PATCH /users/{user} → UserController@update"\]  
            R23\["POST /users/{user}/reset-password"\]  
            R24\["DELETE /users/{user} → UserController@destroy"\]  
        end  
    end

    PUBLIC \--\> AUTH

## **13\. Ringkasan Tabel Guard Condition**

| Guard | Lokasi Kode | Kondisi Pemicu | Tindakan Sistem |
| :---- | :---- | :---- | :---- |
| 🔒 **Guard 1A** | DocumentController@store | item.verification\_status \=== 'APPROVED' | return back()-\>with('error', ...) — upload DIBLOKIR |
| 🔒 **Guard 1B** | DocumentController@destroy | item.verification\_status \=== 'APPROVED' | return back()-\>with('error', ...) — hapus DIBLOKIR |
| 🛑 **Guard 2** | ItemController@verify | action=APPROVED AND documents()-\>count() \=== 0 | return back()-\>with('error', ...) — approval DIBLOKIR |
| 🧹 **Guard 3** | Document::booted() Eloquent event | deleting event terpanggil saat Document::delete() | Storage::disk('private')-\>delete(file\_path) — file fisik dihapus otomatis |

## **14\. Struktur Direktori Storage Fisik**

storage/  
└── app/  
    └── private/  
        └── uploads/  
            └── {fiscal\_year}/          ← e.g. 2026  
                └── {sub\_output\_code}/  ← e.g. BMA\_006  
                    └── {item\_code}/    ← e.g. 001366  
                        ├── 550e8400-e29b-41d4-a716-446655440000.pdf  ← BAPP Honor  
                        ├── 6ba7b810-9dad-11d1-80b4-00c04fd430c8.png  ← Kuitansi  
                        └── ...  
