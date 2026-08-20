# PROMPT UNTUK AI DEV — IMPLEMENTASI DESIGN SYSTEM SAKDI BPS
# Salin prompt di bawah ini dan kirim ke AI Dev kamu.
# Ganti bagian [DALAM KURUNG KOTAK] sesuai konteks proyekmu.
# ─────────────────────────────────────────────────────────────────

---

## PROMPT (MULAI DARI SINI)

Kamu adalah senior frontend developer yang bertugas mengimplementasikan dan memperbaiki tampilan sistem **SAKDI (Sistem Administrasi Keuangan dan Pengarsipan Digital)** milik instansi **Badan Pusat Statistik (BPS)** agar sesuai dengan design system yang sudah ditetapkan.

---

### KONTEKS PROYEK

- **Nama Sistem:** SAKDI — Sistem Administrasi Keuangan dan Pengarsipan Digital
- **Instansi:** Badan Pusat Statistik (BPS) Indonesia
- **Stack:** [Tulis stack kamu di sini, contoh: React + Tailwind CSS / Next.js / Vue 3 / Laravel Blade]
- **Halaman yang perlu diperbaiki:** [Tulis nama halaman/komponen yang kamu mau diperbaiki, contoh: Dashboard, Form Pengajuan, Tabel Arsip, Login Page]

---

### TUGASMU

Perbaiki seluruh tampilan UI pada [nama halaman/komponen] agar **100% sesuai** dengan design system SAKDI BPS berikut ini. Jangan membuat keputusan desain sendiri — semua nilai warna, ukuran, spacing, radius, shadow, dan tipografi **harus diambil dari token di bawah ini**.

---

### DESIGN SYSTEM SAKDI BPS v2.0.0

#### A. COLOR TOKENS

```
/* ── Brand BPS ── */
--color-bps-blue:       #0057A8;   /* "B" logo — Sensus Penduduk */
--color-bps-orange:     #E8601C;   /* "P" logo — Sensus Ekonomi */
--color-bps-green:      #00873E;   /* "S" logo — Sensus Pertanian */

/* ── Primary (BPS Blue) ── */
--color-primary-900:    #002D5C;
--color-primary-700:    #004A9E;
--color-primary:        #0057A8;
--color-primary-400:    #3D87CC;
--color-primary-100:    #D6E8F7;
--color-primary-50:     #EEF5FB;

/* ── Accent (BPS Orange) ── */
--color-accent-700:     #B84A12;
--color-accent:         #E8601C;
--color-accent-200:     #F9C9A8;
--color-accent-50:      #FEF3EC;

/* ── Positive (BPS Green) ── */
--color-positive-700:   #006030;
--color-positive:       #00873E;
--color-positive-200:   #A8DFC0;
--color-positive-50:    #E6F5EC;

/* ── Semantic ── */
--color-error:          #C62828;
--color-error-light:    #FDECEA;
--color-warning:        #E65100;
--color-warning-light:  #FFF3E0;
--color-info:           #0057A8;
--color-info-light:     #EEF5FB;

/* ── Neutral ── */
--color-neutral-900:    #0F1923;
--color-neutral-700:    #1E293B;
--color-neutral-500:    #475569;
--color-neutral-300:    #CBD5E1;
--color-neutral-100:    #F1F5F9;
--color-neutral-50:     #F8FAFC;
--color-white:          #FFFFFF;

/* ── Dark Mode (aktif via .dark atau prefers-color-scheme: dark) ── */
--dm-bg-base:           #0D1117;
--dm-bg-surface:        #161B22;
--dm-bg-elevated:       #1C2128;
--dm-bg-overlay:        #21262D;
--dm-primary:           #4D9EE0;
--dm-primary-light:     #A8CCEE;
--dm-accent:            #F09060;
--dm-positive:          #3DB86B;
--dm-error:             #F77171;
--dm-text-primary:      #E6EDF3;
--dm-text-secondary:    #8D96A0;
--dm-text-muted:        #525C65;
--dm-border:            #30363D;
--dm-border-strong:     #484F58;
--dm-sidebar-bg:        #010409;
```

#### B. TYPOGRAPHY TOKENS

```
/* ── Font Family ── */
--font-display:  'Plus Jakarta Sans', sans-serif;
--font-body:     'Plus Jakarta Sans', sans-serif;
--font-mono:     'JetBrains Mono', monospace;

/* ── Font Scale (Perfect Fourth ×1.333) ── */
--text-xs:    0.75rem;    /* 12px */
--text-sm:    0.875rem;   /* 14px */
--text-base:  1rem;       /* 16px */
--text-md:    1.125rem;   /* 18px */
--text-lg:    1.333rem;   /* ~21px */
--text-xl:    1.777rem;   /* ~28px */
--text-2xl:   2.369rem;   /* ~38px */
--text-3xl:   3.157rem;   /* ~51px */

/* ── Font Weight ── */
--weight-regular:   400;
--weight-medium:    500;
--weight-semibold:  600;
--weight-bold:      700;

/* ── Line Height ── */
--leading-tight:    1.2;
--leading-snug:     1.4;
--leading-normal:   1.6;
--leading-relaxed:  1.75;

/* ── Letter Spacing ── */
--tracking-tight:   -0.02em;
--tracking-normal:  0em;
--tracking-wide:    0.04em;
--tracking-wider:   0.08em;
```

#### C. SPACING TOKENS (Fibonacci 8-point Grid)

```
--space-0:   0px;
--space-1:   4px;
--space-2:   8px;
--space-3:   12px;
--space-4:   16px;
--space-5:   20px;
--space-6:   24px;
--space-8:   32px;
--space-10:  40px;
--space-12:  48px;
--space-16:  64px;
--space-20:  80px;
--space-24:  96px;
```

#### D. BORDER RADIUS TOKENS

```
--radius-none:  0px;
--radius-xs:    2px;
--radius-sm:    4px;
--radius-md:    8px;
--radius-lg:    12px;
--radius-xl:    16px;
--radius-full:  9999px;
```

#### E. ELEVATION TOKENS

```
--shadow-0:  none;
--shadow-1:  0 1px 3px rgba(0,0,0,0.08);
--shadow-2:  0 4px 12px rgba(0,0,0,0.10);
--shadow-3:  0 8px 24px rgba(0,0,0,0.12);
--shadow-4:  0 16px 40px rgba(0,0,0,0.14);
```

#### F. Z-INDEX TOKENS

```
--z-below:    -1;
--z-base:      0;
--z-raised:    10;
--z-dropdown:  100;
--z-sticky:    200;
--z-header:    300;
--z-sidebar:   400;
--z-overlay:   500;
--z-modal:     600;
--z-toast:     700;
--z-tooltip:   800;
```

#### G. LAYOUT

```
Grid:              12 kolom, gutter 24px
Max width:         1280px
Sidebar:           256px (expanded) / 64px (collapsed)
Konten:            sisa lebar (≈61.8% — golden ratio)
Breakpoints:
  xs  375px  → sidebar: hidden (hamburger overlay)
  sm  640px  → sidebar: hidden
  md  768px  → sidebar: collapsed (64px, ikon saja)
  lg  1024px → sidebar: expanded (256px)
  xl  1280px → sidebar: expanded
```

---

### ATURAN KOMPONEN YANG WAJIB DIIKUTI

#### Button
| Varian | Kapan Digunakan |
|---|---|
| `button-primary` | Aksi utama — **maksimal 1 per halaman** |
| `button-secondary` | Aksi alternatif, selalu berpasangan dengan primary |
| `button-accent` | CTA penting non-utama (contoh: "Ekspor Data") |
| `button-danger` | Aksi destruktif — **wajib konfirmasi modal dulu** |
| `button-ghost` | Aksi tersier (batal, kembali) |
| `button-icon` | Tombol ikon saja, ukuran minimum 44×44px |

- Semua button: `min-height: 44px`, `border-radius: var(--radius-md)`, `font-weight: var(--weight-semibold)`
- State yang harus ada: `:hover`, `:active`, `:focus-visible`, `:disabled`
- Focus ring: `outline: 3px solid var(--color-primary-400); outline-offset: 2px`

#### Form
- Setiap `<input>`, `<select>`, `<textarea>` **wajib** punya `<label>` terhubung via `for`/`id`
- Field required ditandai dengan tanda `*` berwarna `var(--color-error)` setelah label
- State error harus tampil sebagai **teks pesan** di bawah input (jangan hanya border merah)
- `_disabled`: bg `var(--color-neutral-100)`, cursor `not-allowed`
- `_readonly`: bg `var(--color-neutral-50)`, border normal
- Min height input: `44px`

#### Tabel Data
- Header: bg `var(--color-neutral-100)`, font semibold, font size `--text-sm`
- Row hover: bg `var(--color-primary-50)`
- Row striped: bg `var(--color-neutral-50)` pada baris genap
- Kolom angka/ID: gunakan `font-family: var(--font-mono)`
- Selalu sertakan komponen `pagination` untuk tabel > 20 baris

#### Kartu KPI / Statistik
- Gunakan `card-stat` dengan `border-left: 4px solid` sesuai konteks:
  - Data netral/informasi → `var(--color-primary)`
  - Peringatan / anggaran hampir habis → `var(--color-accent)`
  - Realisasi sukses / lunas → `var(--color-positive)`
  - Defisit / gagal → `var(--color-error)`

#### File Upload
- Format yang diizinkan: PDF, XLSX, DOCX, JPG, PNG
- Batas ukuran: 10MB per file, 50MB per batch
- Wajib tampilkan: drag & drop area, progress upload, dan status error per file

#### Stepper (Alur Persetujuan)
- State: `pending` → `active` → `done` (atau `error` jika ditolak)
- Jangan skip state — setiap langkah harus bisa dilacak statusnya

#### Skeleton Loader
- Gunakan skeleton, **bukan spinner**, untuk loading konten berstruktur (tabel, kartu, form)
- Spinner hanya untuk aksi singkat (submit, upload progress)

#### Modal
- Overlay: `rgba(15, 25, 35, 0.6)`, z-index: `var(--z-overlay)`
- Dialog: z-index: `var(--z-modal)`, max-width: `560px`, `border-radius: var(--radius-lg)`
- Wajib ada: header, body, footer dengan tombol aksi rata kanan
- `button-danger` di modal **tidak** perlu konfirmasi tambahan

#### Sidebar & Navigasi
- Background sidebar: `var(--color-primary-900)`
- Nav item default: `color: rgba(255,255,255,0.75)`
- Nav item active: bg `var(--color-primary)`, teks putih
- Group label: uppercase, `var(--tracking-wider)`, `color: rgba(255,255,255,0.4)`

---

### ATURAN DESAIN YANG TIDAK BOLEH DILANGGAR

1. **Jangan hard-code nilai hex atau pixel** — selalu gunakan CSS variable dari token di atas
2. **Jangan campur border-radius** — pilih satu konsistensi per halaman (`--radius-sm` untuk input, `--radius-md` untuk kartu dan tombol)
3. **Jangan gunakan shadow > `--shadow-2`** untuk elemen yang bukan modal atau drawer
4. **Jangan gunakan warna sebagai satu-satunya penanda** — selalu sertakan ikon atau teks pendukung (untuk aksesibilitas buta warna)
5. **Jangan gunakan placeholder sebagai pengganti label** — label wajib selalu tampil
6. **Jangan memutar animasi** jika `prefers-reduced-motion: reduce` aktif — bungkus semua transition dalam media query
7. **Z-index wajib dari token** — jangan pakai angka arbitrer seperti `z-index: 9999`
8. **Minimum tap target 44×44px** untuk semua elemen interaktif (tombol, link, checkbox, radio)
9. **Satu `button-primary` per halaman** — jika butuh lebih dari satu aksi utama, evaluasi ulang hierarki halamannya

---

### AKSESIBILITAS YANG WAJIB DIPENUHI (WCAG 2.1 AA)

- Semua ikon dekoratif: `aria-hidden="true"`
- Semua ikon fungsional: `aria-label` wajib diisi
- Setiap input: terhubung ke label via `for`/`id`, pesan error via `aria-describedby`
- Focus ring visible di semua elemen interaktif saat navigasi keyboard
- Kontras warna minimum 4.5:1 untuk teks normal, 3:1 untuk teks besar

---

### DATA VISUALIZATION (jika halaman ini punya chart/grafik)

Gunakan palet warna berikut secara berurutan untuk setiap series baru:

```
Series 1: #0057A8  (BPS Blue)
Series 2: #E8601C  (BPS Orange)
Series 3: #00873E  (BPS Green)
Series 4: #7B61FF  (Ungu)
Series 5: #00AABB  (Teal)
Series 6: #E8B800  (Kuning)
```

- Label dan nilai chart: `font-family: var(--font-mono)`
- Garis grid: `var(--color-neutral-100)`
- Label aksis: `var(--color-neutral-500)`, `var(--text-xs)`
- Judul chart: `var(--text-md)`, `var(--weight-semibold)`
- Data divergen (pertumbuhan/defisit): gunakan `#C62828` (negatif) → `#F1F5F9` (netral) → `#00873E` (positif)

---

### OUTPUT YANG DIHARAPKAN

Berikan output dalam bentuk:

1. **Kode lengkap** komponen/halaman yang sudah diperbaiki
2. **Daftar perubahan** yang kamu buat, dikelompokkan per kategori:
   - Warna yang diubah
   - Tipografi yang diubah
   - Spacing yang diubah
   - Komponen yang ditambah atau diperbaiki
   - Aksesibilitas yang diperbaiki
3. **Catatan** jika ada bagian yang tidak bisa diimplementasikan karena keterbatasan stack, beserta alasannya

---

### KONTEKS KODE YANG PERLU DIPERBAIKI

[Tempel kode komponen atau halaman yang ingin diperbaiki di sini]

```
[KODE KAMU DI SINI]
```

---

Mulai sekarang. Perbaiki kode di atas agar sesuai dengan semua token dan aturan design system SAKDI BPS v2.0.0 yang sudah dijelaskan. Jangan mengubah logika bisnis atau struktur data — hanya perbaiki bagian tampilan (styling, komponen UI, aksesibilitas).
