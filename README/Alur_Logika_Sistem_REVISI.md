# 🔧 Alur Logika Sistem — REVISI

## Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang

**Dokumen ini melengkapi:** `PRD_Sistem_Data_Digital_Arsip_Keuangan_BPS.md` (v2.1) dan `Alur Logika & ERD Lengkap.md`
**Sifat dokumen:** Perbaikan logika alur — bukan menggantikan dokumen sebelumnya, tapi menambal celah yang ditemukan dan menjelaskan ulang alurnya dengan bahasa yang lebih sederhana.

---

## 0. Kenapa dokumen ini dibuat

Setelah membaca PRD, ERD, Matriks Sidebar, dan catatan diskusi tim (`Bahas_Project_pt2.docx`), ditemukan **3 celah logika** antara apa yang dijanjikan di PRD dengan apa yang benar-benar dikunci di alur/ERD. Ketiganya diperbaiki di sini:

| # | Celah yang ditemukan | Dampak kalau dibiarkan |
| :-- | :-- | :-- |
| 1 | PRD & sidebar menjanjikan **"Interactive Bendahara Checklist"** (centang file satu per satu), tapi Guard 2 di ERD hanya mengecek **"jumlah dokumen ≥ 1"**. Tidak ada kolom checklist di tabel `documents`. | Bendahara bisa klik Approve walau belum benar-benar memeriksa satu pun file — fitur andalan sistem ini jadi hiasan, bukan aturan sungguhan. |
| 2 | RBAC menyatakan Operator hanya boleh hapus **"Dokumen milik sendiri"**, tapi tidak ada Guard yang benar-benar mengecek kepemilikan saat hapus dokumen. | Operator A bisa menghapus dokumen yang diunggah Operator B. |
| 3 | Saat item `REJECTED` lalu Operator upload ulang → status otomatis balik ke `PENDING`. Tapi tidak dijelaskan **apa yang terjadi pada status checklist dokumen lama**. | Kalau checklist lama tetap "tercentang", Bendahara bisa Approve tanpa sadar sedang menyetujui dokumen yang belum direvisi. |

Bagian di bawah ini berisi alur yang **sudah diperbaiki**. Bagian angka mengikuti penomoran di `Alur Logika & ERD Lengkap.md` supaya mudah dicocokkan.

---

## 1. Ringkasan Alur Bisnis (versi sederhana)

Ini gambaran alur nyata di kantor, disederhanakan dari hasil diskusi tim:

```
1. ADMIN / SUPERVISOR   → Menyiapkan struktur POK & Item kegiatan (Master Data)
2. OPERATOR              → Menelusuri Item di Arsip POK → Upload berkas SPJ/BAPP/Kuitansi per label
3. BENDAHARA              → Buka inbox Verifikasi → Preview tiap file → CENTANG satu per satu
                             → Kalau semua sudah dicentang & lengkap → Approve (Siap Cair)
                             → Kalau ada yang kurang/salah → Reject + catatan revisi
4. OPERATOR (jika ditolak) → Baca catatan revisi → Upload ulang → otomatis masuk antrean PENDING lagi
5. SEMUA ROLE              → Bisa lihat rekap status semua item di Laporan & Rekapitulasi
```

**Prinsip kunci yang harus dipegang sistem:**
- Bendahara **tidak bisa** approve tanpa benar-benar mencentang tiap dokumen (bukan sekadar "ada dokumennya").
- Operator **hanya** bisa mengubah/menghapus dokumen miliknya sendiri (Supervisor & Admin bebas, sebagai pengawas).
- Begitu status `APPROVED`, item terkunci total — tidak ada yang bisa ubah dokumen lagi (kecuali lewat proses resmi di luar sistem).
- Dokumen fisik (BAPP, SK, dll.) tetap dibuat & ditandatangani manual di luar sistem — sistem ini murni tempat arsip hasil scan, sesuai batasan *Out of Scope* di PRD.

---

## 2. Perbaikan ERD — Tabel `documents`

Tambahkan 3 kolom baru pada tabel `documents` agar checklist punya tempat penyimpanan nyata di database:

```
documents {
    bigint id PK
    bigint item_id FK
    bigint uploaded_by_user_id FK
    varchar file_name
    varchar stored_file_name
    varchar file_path
    bigint file_size
    varchar file_type
    varchar label "nullable — BAPP, Kuitansi"
    boolean is_checked "default: false  ← BARU"
    bigint checked_by_user_id FK "nullable, users.id  ← BARU"
    timestamp checked_at "nullable  ← BARU"
    timestamp created_at
    timestamp updated_at
}

users ||--o{ documents : "checked_by_user_id (nullable, set null on delete)"
```

Kenapa disimpan sebagai kolom di `documents` (bukan tabel checklist terpisah): setiap dokumen hanya perlu satu status centang aktif pada satu waktu, jadi tidak perlu tabel pivot — cukup 3 kolom tambahan ini.

---

## 3. Guard yang Diperbarui & Guard Baru

| Guard | Lokasi Kode | Kondisi Pemicu | Tindakan Sistem |
| :-- | :-- | :-- | :-- |
| 🔒 Guard 1A | `DocumentController@store` | `item.verification_status === 'APPROVED'` | Upload diblokir (tidak berubah) |
| 🔒 Guard 1B | `DocumentController@destroy` | `item.verification_status === 'APPROVED'` | Hapus diblokir (tidak berubah) |
| 🛑 **Guard 2 (REVISI)** | `ItemController@verify` | `action=APPROVED` DAN (`documents.count() === 0` **ATAU** ada dokumen dengan `is_checked = false`) | Approve diblokir — pesan: *"Masih ada N dokumen yang belum dicentang/diperiksa."* |
| 🆕 **Guard 4** | `DocumentController@destroy` | `role === 'OPERATOR'` DAN `document.uploaded_by_user_id !== auth()->id()` | `abort(403)` — Operator hanya boleh hapus dokumen unggahannya sendiri. Supervisor & Admin dikecualikan dari Guard ini. |
| 🧹 Guard 3 | `Document::booted()` | `deleting` event | File fisik dihapus otomatis (tidak berubah) |
| 🆕 **Guard 5 — Reset Checklist** | `ItemController@verify` (alur REJECTED) dan `DocumentController@store` (alur re-upload) | Status item berubah dari `REJECTED` → `PENDING` karena ada upload baru | Semua dokumen milik item tsb: `is_checked = false`, `checked_by_user_id = null`, `checked_at = null` — checklist wajib diulang dari nol |

---

## 4. Alur Baru: Checklist Verifikasi Bendahara (menggantikan Section 6 lama)

```
A[🟡 Bendahara buka /verification] --> B[Klik item → masuk /items/{id}]
B --> C[Lihat daftar dokumen dengan checkbox di tiap baris]
C --> D[Klik "Pratinjau" → modal PDF inline]
D --> E[Centang checkbox "Sudah diperiksa" per dokumen]
E --> F{PATCH /documents/{doc}/check}
F --> G[UPDATE documents SET is_checked=true, checked_by_user_id=auth id, checked_at=now]
G --> H{Semua dokumen item ini sudah is_checked=true?}
H -- BELUM --> I[Tombol "Setujui Pencairan" tetap disabled\nProgress: "3/5 dokumen diverifikasi"]
I --> C
H -- SUDAH SEMUA --> J[Tombol "Setujui Pencairan" aktif]

J --> K{Bendahara klik Setujui}
K --> L{PATCH /items/{id}/verify, action=APPROVED}
L --> M{Guard 2 REVISI: docs=0 ATAU ada is_checked=false?}
M -- YA, masih ada yang kurang --> N[❌ Error: sistem tetap blokir walau tombol sempat aktif\n(safety net server-side)]
M -- TIDAK, semua aman --> O[UPDATE items SET verification_status=APPROVED]
O --> P[✅ Badge Hijau — Siap Cair]

K --> Q{Bendahara klik Tolak}
Q --> R[Isi rejection_note wajib]
R --> S{PATCH /items/{id}/verify, action=REJECTED}
S --> T[UPDATE items SET verification_status=REJECTED, rejection_note=...]
T --> U[🆕 Guard 5: reset is_checked semua dokumen item ini ke false]
U --> V[🔴 Badge Merah — Ditolak, Operator baca catatan]
```

**Catatan penting:** validasi "semua dokumen tercentang" harus tetap dicek ulang di server (Guard 2) saat tombol Approve ditekan — bukan cuma di sisi tampilan (disable button). Ini mencegah kasus tombol sempat aktif lalu ada dokumen baru masuk di detik terakhir.

---

## 5. Alur Baru: Hapus Dokumen dengan Guard Kepemilikan

```
A[User klik Hapus Dokumen] --> B{item.verification_status === APPROVED?}
B -- YA --> C[❌ Diblokir — Guard 1B]
B -- TIDAK --> D{role === OPERATOR?}
D -- TIDAK (Supervisor/Admin) --> G[Lanjut hapus]
D -- YA --> E{document.uploaded_by_user_id === auth id?}
E -- TIDAK --> F[❌ abort 403 — Guard 4\n"Anda hanya bisa menghapus dokumen unggahan sendiri"]
E -- YA --> G[Lanjut hapus]
G --> H[Guard 3: file fisik dihapus dari storage]
H --> I[Row documents dihapus dari DB]
```

---

## 6. State Machine Item — Versi Revisi

```
[*] --> PENDING : Item dibuat (Master CRUD)

PENDING --> APPROVED : Bendahara approve
  [Guard 2 REVISI: docs ≥ 1 DAN semua is_checked = true]

PENDING --> REJECTED : Bendahara reject [wajib rejection_note]

REJECTED --> PENDING : Operator upload dokumen baru
  [Guard 5: reset seluruh is_checked dokumen lama ke false]

APPROVED --> APPROVED : LOCKED, tidak bisa kembali ke status lain
```

Catatan tambahan di tiap status:

- **PENDING:** Operator bisa upload/hapus dokumen miliknya sendiri. Bendahara bisa mencentang checklist kapan saja, meski belum semua file lengkap (centang berjalan progresif, tidak harus sekali duduk).
- **APPROVED:** Terkunci total. Upload, hapus, dan ubah checklist semuanya diblokir. Preview & download tetap bisa (untuk arsip/audit).
- **REJECTED:** Operator baca catatan revisi, upload dokumen pengganti/tambahan. Checklist lama otomatis direset (Guard 5) agar Bendahara memeriksa ulang dari awal — bukan asumsi "yang lama sudah pasti benar".

---

## 7. Route Tambahan

Tambahkan ke grup middleware `role:BENDAHARA,ADMIN` pada route map:

```
PATCH /documents/{document}/check → DocumentController@toggleCheck
```

Body request: `{ "is_checked": true|false }`
Efek: update `is_checked`, `checked_by_user_id`, `checked_at` pada 1 dokumen. Tidak mengubah status item — status item hanya berubah lewat `PATCH /items/{id}/verify`.

---

## 8. Ringkasan Perubahan pada Dokumen Lain

Agar semua dokumen proyek tetap sinkron, berikut yang perlu disesuaikan di file-file lama (di luar dokumen ini):

| Dokumen | Bagian yang perlu disesuaikan |
| :-- | :-- |
| `Alur Logika & ERD Lengkap.md` | ERD tabel `documents` (tambah 3 kolom), Section 6 (alur verifikasi), Section 10 (state machine), Section 12 (tambah route baru), Section 13 (tabel Guard) — semua sudah ditulis ulang di dokumen ini, tinggal disalin/merge. |
| `PRD_Sistem_Data_Digital_Arsip_Keuangan_BPS.md` | Tidak perlu berubah — PRD sudah benar menjanjikan checklist interaktif (US-03). Dokumen ini justru **menepati** janji PRD tersebut yang sebelumnya belum konsisten di level teknis. |
| `Matriks Navigasi Sidebar per Role.md` | Tidak perlu berubah — sudah konsisten dengan RBAC. |
| `DESIGN.md` | Nama sistem sudah diperbaiki dari "SAKDI" menjadi nama resmi, dan komponen "File Upload" & checklist verifikasi sudah ditambahkan catatannya (lihat file `DESIGN.md` versi revisi). |

---

## 9. Pertanyaan yang Masih Perlu Dikonfirmasi ke Pak Cecep / Bu Mega

Berdasarkan catatan diskusi tim, ini poin yang masih abu-abu dan sebaiknya dikonfirmasi sebelum lanjut development, bukan diasumsikan sendiri:

1. **Auto-generate dokumen (BAPP dari isian NIP/nama):** PRD sudah tegas menyatakan ini *Out of Scope*. Perlu dikonfirmasi ulang ke Bu Mega apakah tetap disepakati di luar cakupan, karena masih disebut sebagai "kalau bisa" di diskusi tim.
2. **Reset checklist saat REJECTED (Guard 5 di dokumen ini):** ini adalah keputusan desain baru yang diambil demi konsistensi logika — perlu divalidasi ke pengguna (Bu Mega/Bu Bahrir) apakah alur "harus dicek ulang semua dari nol" ini sesuai kebiasaan kerja mereka, atau cukup dokumen barunya saja yang perlu dicek ulang.
3. **Kebutuhan server:** seperti dicatat di diskusi tim, ini di luar tanggung jawab tim developer (sudah sesuai PRD Section 3 - Out of Scope), tapi tetap perlu dikomunikasikan lebih awal ke pihak BPS karena menyangkut kapasitas penyimpanan arsip yang akan terus bertambah tiap tahun anggaran.
