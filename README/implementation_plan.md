# Implementasi SAKDI BPS Design System v2.0.0 — FINAL PLAN

Menerapkan design system resmi SAKDI v2.0.0 ke seluruh tampilan SAKDI BPS Kabupaten Subang, berdasarkan [`DESIGN.md`](file:///d:/Project%20BPS/app/DESIGN.md).

---

## Status Konfirmasi User

| Poin | Jawaban |
|---|---|
| Warna brand | ✅ Ganti semua ke `#0057A8` |
| Dark mode | ✅ Aktifkan via class `.dark` pada `<html>` + media query |
| Print CSS | ✅ Implementasi penuh Section 14 DESIGN.md |
| Font loading | ✅ Verifikasi + pastikan preload `Plus Jakarta Sans` & `JetBrains Mono` |
| Skeleton loader & tooltip | ✅ Tambahkan ke dashboard & tabel arsip |
| Hapus CSS lama | ✅ Purge semua hardcode hex lama setelah token baru diterapkan |
| Verification plan | ✅ Diperluas: form error state, tablet responsive, print |

---

## Audit Warna Lama — Hasil Temuan

> [!WARNING]
> **14 instance** warna hardcode lama ditemukan di **6 file**. Semua akan diganti token.

| File | Baris | Warna Lama | Token Baru |
|---|---|---|---|
| `app.blade.php` | L29 | `#003087`, `#001F54` (progress bar gradient) | `#0057A8`, `#002D5C` |
| `app.blade.php` | L174, L178 | `#003087`, `#001F54` (btn-bps-primary) | `#0057A8`, `#004A9E` |
| `app.blade.php` | L222 | `#003087` (form-input focus border) | `#0057A8` |
| `app.blade.php` | L251 | `bg-[#001F54]` (topbar BPS icon) | `bg-[#002D5C]` |
| `sidebar.blade.php` | L146 | `#001F54` (sidebar bg) | `#002D5C` |
| `sidebar.blade.php` | L196 | `#003087` (nav active bg) | `#0057A8` |
| `items/show.blade.php` | L427 | `bg-[#001F54]` (modal header) | `bg-[#002D5C]` |
| `dashboard.blade.php` | L12 | `bg-[#001F54]` (hero banner) | `bg-[#002D5C]` |
| `arsip/index.blade.php` | L8 | `bg-[#001F54]` (page header) | `bg-[#002D5C]` |
| `auth/login.blade.php` | L14 | `#001a5c`, `#003087`, `#0d47a1` (bg gradient) | `#002D5C`, `#0057A8`, `#004A9E` |
| `auth/login.blade.php` | L52 | `#003087` (focus border) | `#0057A8` |
| `auth/login.blade.php` | L54 | `#003087`, `#0d47a1` (btn gradient) | `#0057A8`, `#004A9E` |
| `auth/login.blade.php` | L127 | `accent-color:#003087` (checkbox) | `#0057A8` |
| `auth/login.blade.php` | L47, L137 | `#374151` (label color) | `var(--color-neutral-700)` |

> [!NOTE]
> **JS files**: `app.js` dan `bootstrap.js` — **tidak ada hardcode warna** (clean ✓). Welcome page — tidak ada legacy hex (clean ✓). Reports & users views — tidak ada legacy hex (clean ✓).

---

## Proposed Changes

---

### 1. Font Loading Verification + `app.css` — Design Tokens

#### [MODIFY] [app.blade.php](file:///d:/Project%20BPS/app/resources/views/layouts/app.blade.php) — Font preload
Tambah `<link rel="preload">` untuk font files di `<head>` untuk mencegah FOUT:
```html
<!-- Preconnect sudah ada, tambah display=swap dan font-display -->
<link href="...Plus+Jakarta+Sans...display=swap" rel="stylesheet">
<link href="...JetBrains+Mono...display=swap" rel="stylesheet">
```
Tambah CSS `font-display: swap` di `@font-face` override jika diperlukan.

#### [MODIFY] [app.css](file:///d:/Project%PS/app/resources/css/app.css)
Ganti isi sepenuhnya dengan:

**A. CSS Custom Properties (Design Tokens v2.0.0)**
```css
:root {
  /* Brand Colors */
  --color-bps-blue:    #0057A8;
  --color-bps-orange:  #E8601C;
  --color-bps-green:   #00873E;

  /* Primary scale */
  --color-primary-900: #002D5C;
  --color-primary-700: #004A9E;
  --color-primary:     #0057A8;
  --color-primary-400: #3D87CC;
  --color-primary-100: #D6E8F7;
  --color-primary-50:  #EEF5FB;

  /* Accent scale */
  --color-accent-700:  #B84A12;
  --color-accent:      #E8601C;
  --color-accent-200:  #F9C9A8;
  --color-accent-50:   #FEF3EC;

  /* Positive scale */
  --color-positive-700: #006030;
  --color-positive:     #00873E;
  --color-positive-200: #A8DFC0;
  --color-positive-50:  #E6F5EC;

  /* Semantic */
  --color-error:        #C62828;
  --color-error-light:  #FDECEA;
  --color-warning:      #E65100;
  --color-warning-light:#FFF3E0;

  /* Neutral */
  --color-neutral-900: #0F1923;
  --color-neutral-700: #1E293B;
  --color-neutral-500: #475569;
  --color-neutral-300: #CBD5E1;
  --color-neutral-100: #F1F5F9;
  --color-neutral-50:  #F8FAFC;
  --color-white:       #FFFFFF;

  /* Typography */
  --font-sans: 'Plus Jakarta Sans', system-ui, sans-serif;
  --font-mono: 'JetBrains Mono', monospace;

  /* Spacing (Fibonacci 8-point) */
  --sp-1: 4px;  --sp-2: 8px;  --sp-3: 12px; --sp-4: 16px;
  --sp-5: 20px; --sp-6: 24px; --sp-8: 32px; --sp-10: 40px;
  --sp-12: 48px; --sp-16: 64px; --sp-20: 80px; --sp-24: 96px;

  /* Elevation */
  --shadow-1: 0 1px 3px rgba(0,0,0,0.08);
  --shadow-2: 0 4px 12px rgba(0,0,0,0.10);
  --shadow-3: 0 8px 24px rgba(0,0,0,0.12);
  --shadow-4: 0 16px 40px rgba(0,0,0,0.14);

  /* Z-index */
  --z-raised:   10;
  --z-dropdown: 100;
  --z-sticky:   200;
  --z-header:   300;
  --z-sidebar:  400;
  --z-overlay:  500;
  --z-modal:    600;
  --z-toast:    700;
  --z-tooltip:  800;

  /* Border radius */
  --r-xs: 2px; --r-sm: 4px; --r-md: 8px;
  --r-lg: 12px; --r-xl: 16px; --r-full: 9999px;

  /* Layout */
  --sidebar-w: 256px;
}
```

**B. Dark Mode Tokens**
```css
@media (prefers-color-scheme: dark), .dark {
  --color-bg-base:      #0D1117;
  --color-bg-surface:   #161B22;
  --color-bg-elevated:  #1C2128;
  --color-primary:      #4D9EE0;
  --color-accent:       #F09060;
  --color-positive:     #3DB86B;
  --color-error:        #F77171;
  --color-neutral-900:  #E6EDF3;
  --color-neutral-700:  #8D96A0;
  --color-neutral-300:  #30363D;
  --color-neutral-100:  #1C2128;
  --color-white:        #161B22;
}
```

**C. Component Classes (pengganti hardcode inline)**
- `.sakdi-btn-primary`, `.sakdi-btn-secondary`, `.sakdi-btn-danger`, `.sakdi-btn-ghost`
- `.sakdi-badge-success`, `.sakdi-badge-warning`, `.sakdi-badge-error`, `.sakdi-badge-primary`
- `.sakdi-card`, `.sakdi-card-stat`, `.sakdi-card-stat-positive`, `.sakdi-card-stat-error`
- `.sakdi-input`, `.sakdi-label`, `.sakdi-textarea`
- `.sakdi-table`, `.sakdi-th`, `.sakdi-td`
- `.sakdi-skeleton`, `.sakdi-skeleton-text`, `.sakdi-skeleton-avatar`
- `.sakdi-tooltip`, `[data-tooltip]::after`
- `.sakdi-alert-success`, `.sakdi-alert-error`, `.sakdi-alert-warning`, `.sakdi-alert-info`

**D. Animations**
```css
@keyframes shimmer { ... } /* skeleton loader */
@keyframes pageFadeIn { ... } /* page entrance */
@keyframes toastSlideIn { ... } /* toast notification */
```

**E. Print CSS (@media print) — Section 14 DESIGN.md**
```css
@media print {
  /* A4, margins 20mm 25mm 20mm 25mm */
  /* Font: Plus Jakarta Sans, Arial */
  /* font-size: 10pt, line-height: 1.5 */
  /* Hide: sidebar, topbar, buttons, file-upload */
  /* Table: headerBg #E8F0FA, striped #F5F8FC */
  /* Watermark: "DOKUMEN RESMI BPS" 48pt -45deg rgba(0,87,168,0.08) */
  /* Page break: avoid table-row, figure, h3/h4; before h2 */
  /* Footer: page number + tanggal cetak */
}
```

**F. Reduced Motion**
```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation-duration: 0.01ms !important; }
}
```

---

### 2. Layout — `layouts/app.blade.php`

#### [MODIFY] [app.blade.php](file:///d:/Project%20BPS/app/resources/views/layouts/app.blade.php)
- **Hapus** semua `<style>` inline hardcode hex (`.btn-bps-primary`, `.form-input-v4`, dll.) — ganti referensi ke class `.sakdi-*` dari `app.css`
- Update `--sidebar-w: 270px` → `256px`
- Topbar progress bar: `#003087 → #0057A8`, `#001F54 → #002D5C`
- Topbar BPS icon: `bg-[#001F54]` → `bg-[#002D5C]`
- Button `.btn-bps-primary`: `#003087 → #0057A8`, hover `#001F54 → #004A9E`
- Input focus: `#003087 → #0057A8`
- Session flash: gunakan class `.sakdi-alert-success` / `.sakdi-alert-error`
- Z-index topbar: `z-index: 30` → `var(--z-header)` (300)

---

### 3. Sidebar — `layouts/sidebar.blade.php`

#### [MODIFY] [sidebar.blade.php](file:///d:/Project%20BPS/app/resources/views/layouts/sidebar.blade.php)
- Sidebar bg: `#001F54` → `#002D5C` (primary-900)
- Sidebar width: `270px` → `256px`
- Nav active bg: `#003087` → `#0057A8` (primary)
- Nav hover: `rgba(255,255,255,0.1)` → `rgba(255,255,255,0.08)` (spec)
- Z-index: `z-index: 40` → `var(--z-sidebar)` (400)
- Tambah **collapsed behavior** md: `@media (min-width: 768px) and (max-width: 1023px)` → width 64px, text hidden

---

### 4. Dashboard — `dashboard.blade.php`

#### [MODIFY] [dashboard.blade.php](file:///d:/Project%20BPS/app/resources/views/dashboard.blade.php)
- Hero banner: `bg-[#001F54]` → `bg-[#002D5C]`
- Stat cards: ganti class ke `.sakdi-card-stat`, `.sakdi-card-stat-positive`, `.sakdi-card-stat-error`
- Tombol CTA: gunakan `.sakdi-btn-primary`
- Badges status tabel: `.badge-corp-approved` → `.sakdi-badge-success`, dst.
- **Tambah Skeleton Loader** pada initial page load untuk stat cards (5 cards) — gunakan `.sakdi-skeleton` dengan x-show conditional
- **Tambah Tooltip** pada kolom "Pagu Anggaran" tabel — hover menampilkan format lengkap

---

### 5. Item Detail — `items/show.blade.php`

#### [MODIFY] [show.blade.php](file:///d:/Project%20BPS/app/resources/views/items/show.blade.php)
- Modal header: `bg-[#001F54]` → `bg-[#002D5C]`
- Tombol approval: gunakan `.sakdi-btn-primary` (#0057A8)
- Tombol reject: gunakan `.sakdi-btn-danger` (#C62828)
- Tambah stepper verifikasi (Pending → Verifikasi → Approved/Rejected) di bagian atas item card
- Modal z-index: selaraskan ke `var(--z-modal)` (600) dan overlay `var(--z-overlay)` (500)

---

### 6. Arsip — `arsip/index.blade.php`

#### [MODIFY] [index.blade.php](file:///d:/Project%20BPS/app/resources/views/arsip/index.blade.php)
- Page header: `bg-[#001F54]` → `bg-[#002D5C]`
- Gunakan `.sakdi-card`, `.sakdi-table`, `.sakdi-badge-*`, `.sakdi-btn-*`
- **Tambah Skeleton Loader** untuk tabel saat filter berubah
- **Tambah Tooltip** pada kolom kode item dan badge status

---

### 7. Verifikasi — `verification/index.blade.php`

#### [MODIFY] [index.blade.php](file:///d:/Project%20BPS/app/resources/views/verification/index.blade.php)
- Update token warna, card, table, badge, button
- Tab filter: gunakan styling `.sakdi-tabs`/`.sakdi-tab-item`

---

### 8. Auth / Login — `auth/login.blade.php`

#### [MODIFY] [login.blade.php](file:///d:/Project%20BPS/app/resources/views/auth/login.blade.php)
- Login bg gradient: `#001a5c, #003087, #0d47a1` → `#002D5C, #0057A8, #004A9E`
- Input focus: `#003087` → `#0057A8`
- Button gradient: `#003087, #0d47a1` → `#0057A8, #004A9E`
- Checkbox `accent-color`: `#003087` → `#0057A8`
- Label color `#374151` → `var(--color-neutral-700)`
- Tambah **error state** styling untuk input dengan class `.sakdi-input--error`

---

## Verification Plan (Expanded)

### Automated Tests
```
php artisan test --filter BpsSystemTest
```

### Manual Verification Checklist

#### A. Warna & Token
- [ ] Sidebar background: `#002D5C` (primary-900)
- [ ] Sidebar nav active: `#0057A8` (primary)
- [ ] Topbar progress bar: gradient `#0057A8 → #004A9E`
- [ ] Dashboard hero banner: `#002D5C`
- [ ] Tombol primary di semua halaman: `#0057A8`
- [ ] Login page gradient: `#002D5C → #0057A8`
- [ ] Pastikan **tidak ada** `#003087` atau `#001F54` via browser DevTools Inspector

#### B. Form Error State
- [ ] Submit login form kosong → border merah `#C62828` + teks error muncul di bawah input
- [ ] Upload file melebihi 10MB → area upload berubah ke `.file-upload _error` state
- [ ] Form reject item tanpa catatan → validasi muncul dengan aria-describedby

#### C. Skeleton Loader & Tooltip
- [ ] Dashboard: skeleton muncul saat pertama kali load (sebelum data tersedia)
- [ ] Arsip tabel: skeleton muncul saat filter berubah
- [ ] Tooltip pada kolom pagu: hover muncul dengan delay 150ms, font mono, bg `#0F1923`
- [ ] Tooltip tidak keluar dari viewport (flip position)

#### D. Dark Mode
- [ ] Aktifkan dark mode via OS → semua surface berubah ke token dark
- [ ] Teks tetap terbaca (kontras ≥ AA) di semua dark surface
- [ ] Sidebar dark: `#010409` background, `#E6EDF3` text
- [ ] Tombol primary dark: `#4D9EE0` (5.1:1 kontras ✓)

#### E. Responsif — Tablet (768px–1023px)
- [ ] Sidebar collapsed ke 64px (icon only)
- [ ] Konten utama mengisi sisa lebar
- [ ] Tabel dapat di-scroll horizontal
- [ ] Card KPI dashboard: 2 kolom (tidak overflow)

#### F. Responsif — Mobile (< 640px)
- [ ] Sidebar tersembunyi, hamburger button muncul
- [ ] Overlay gelap muncul saat sidebar dibuka
- [ ] Tabel hanya tampil kolom esensial (atau scroll horizontal)

#### G. Print / PDF — Section 14
- [ ] `Ctrl+P` dari halaman item detail → tampilan A4
- [ ] Sidebar, topbar, tombol, file-upload **tidak tercetak**
- [ ] Tabel memiliki header bg `#E8F0FA`, striped `#F5F8FC`
- [ ] Font `Plus Jakarta Sans` 10pt, line-height 1.5
- [ ] Footer: nomor halaman + tanggal cetak
- [ ] Watermark "DOKUMEN RESMI BPS" hanya muncul jika class `.draft` aktif

#### H. Font Loading
- [ ] DevTools → Network → filter `font` → Plus Jakarta Sans & JetBrains Mono ter-load dengan `font-display: swap`
- [ ] Tidak ada FOIT (flash of invisible text) pada first load
- [ ] Angka di kolom pagu/kode: font mono terpakai (bukan sans)

#### I. Aksesibilitas
- [ ] Semua tombol: outline `3px solid #3D87CC` saat focused
- [ ] Icon dekoratif: `aria-hidden="true"`
- [ ] Contrast ratio setiap pasangan warna ≥ 4.5:1 (gunakan browser DevTools)
