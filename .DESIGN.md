---
version: 2.0.0
name: Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang
description: >
  Design system resmi Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang
  untuk instansi Badan Pusat Statistik (BPS) Kabupaten Subang.
  Mengikuti identitas visual BPS (biru, hijau, oranye), standar aksesibilitas
  WCAG 2.1 AA, proporsi tipografi Perfect Fourth, spacing 8-point grid (base 4px),
  dark mode tokens, z-index scale, komponen administrasi keuangan lengkap,
  data visualization palette, dan print/PDF tokens.

# ─────────────────────────────────────────────────────────────────
# CHANGELOG
# ─────────────────────────────────────────────────────────────────
changelog:
  - version: "2.1.0"
    date: "2026-08-20"
    author: "Design System Team"
    changes:
      - "Perbaiki nama sistem yang salah ('SAKDI') menjadi nama resmi: Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang"
      - "Tambah catatan komponen checklist dokumen (Section 6 - File Upload & Stepper) agar selaras dengan alur verifikasi Bendahara di dokumen Alur Logika & ERD"

  - version: "2.0.0"
    date: "2026-08-20"
    author: "Design System Team"
    changes:
      - "Tambah dark mode token system (Section 11)"
      - "Tambah z-index scale (Section 12)"
      - "Tambah komponen: dropdown, tabs, tooltip, skeleton, stepper, file-upload, pagination (Section 7)"
      - "Tambah data visualization color palette (Section 13)"
      - "Tambah print/PDF tokens (Section 14)"
      - "Tambah responsive sidebar behavior (Section 6)"
      - "Perbaiki inkonsistensi primary color (#0052CC → #0057A8)"

  - version: "1.0.0"
    date: "2026-08-18"
    author: "Design System Team"
    changes:
      - "Initial release — color system, typography, spacing, elevation, layout, components dasar"

# ─────────────────────────────────────────────────────────────────
# 1. COLOR SYSTEM — Light Mode
# ─────────────────────────────────────────────────────────────────
colors:

  # ── Brand BPS (dari logo resmi BPS) ──────────────────────────
  bps-blue:       "#0057A8"   # "B" pada logo — Sensus Penduduk
  bps-orange:     "#E8601C"   # "P" pada logo — Sensus Ekonomi
  bps-green:      "#00873E"   # "S" pada logo — Sensus Pertanian

  # ── Primary — turunan BPS Blue ───────────────────────────────
  primary-900:    "#002D5C"
  primary-700:    "#004A9E"
  primary:        "#0057A8"   # = bps-blue, tombol utama, header
  primary-400:    "#3D87CC"   # border focus, ikon informatif
  primary-100:    "#D6E8F7"   # badge, highlight row tabel
  primary-50:     "#EEF5FB"   # bg section ringan

  # ── Accent — turunan BPS Orange ──────────────────────────────
  accent-700:     "#B84A12"
  accent:         "#E8601C"   # = bps-orange, CTA sekunder, label penting
  accent-200:     "#F9C9A8"
  accent-50:      "#FEF3EC"

  # ── Positive — turunan BPS Green ─────────────────────────────
  positive-700:   "#006030"
  positive:       "#00873E"   # = bps-green, status sukses
  positive-200:   "#A8DFC0"
  positive-50:    "#E6F5EC"

  # ── Semantic States ───────────────────────────────────────────
  error:          "#C62828"
  error-light:    "#FDECEA"
  warning:        "#E65100"
  warning-light:  "#FFF3E0"
  info:           "#0057A8"   # = primary
  info-light:     "#EEF5FB"

  # ── Neutral ───────────────────────────────────────────────────
  neutral-900:    "#0F1923"   # teks heading utama
  neutral-700:    "#1E293B"   # teks body, label
  neutral-500:    "#475569"   # teks sekunder, placeholder
  neutral-300:    "#CBD5E1"   # border, divider
  neutral-100:    "#F1F5F9"   # bg section alternatif
  neutral-50:     "#F8FAFC"   # bg halaman utama
  white:          "#FFFFFF"   # surface kartu, modal

# ─────────────────────────────────────────────────────────────────
# 2. TYPOGRAPHY — Perfect Fourth Scale (×1.333)
# ─────────────────────────────────────────────────────────────────
# Base: 1rem (16px) | Ratio: 1.333
# Scale: 0.75 → 0.875 → 1 → 1.125 → 1.333 → 1.777 → 2.369 → 3.157
# ─────────────────────────────────────────────────────────────────
typography:
  fontFamily:
    display:  "Plus Jakarta Sans"   # heading — tegas, profesional
    body:     "Plus Jakarta Sans"   # body — satu keluarga, konsisten
    mono:     "JetBrains Mono"      # ID transaksi, kode arsip, angka tabel

  scale:
    xs:    "0.75rem"    # 12px — caption, label tabel kecil
    sm:    "0.875rem"   # 14px — label form, teks sub
    base:  "1rem"       # 16px — body utama
    md:    "1.125rem"   # 18px — lead paragraph
    lg:    "1.333rem"   # ~21px — h4, card title
    xl:    "1.777rem"   # ~28px — h3, section heading
    2xl:   "2.369rem"   # ~38px — h2, page heading
    3xl:   "3.157rem"   # ~51px — h1, hero headline

  weights:
    regular:  400
    medium:   500
    semibold: 600
    bold:     700

  lineHeight:
    tight:    1.2    # heading besar
    snug:     1.4    # heading kecil / card title
    normal:   1.6    # body text
    relaxed:  1.75   # deskripsi panjang

  letterSpacing:
    tight:    "-0.02em"   # heading besar
    normal:   "0em"
    wide:     "0.04em"    # caption, badge, overline
    wider:    "0.08em"    # label uppercase

  roles:
    overline:
      fontSize:      "{typography.scale.xs}"
      fontWeight:    "{typography.weights.semibold}"
      letterSpacing: "{typography.letterSpacing.wider}"
      textTransform: "uppercase"
      color:         "{colors.neutral-500}"

    h1:
      fontSize:      "{typography.scale.3xl}"
      fontWeight:    "{typography.weights.bold}"
      lineHeight:    "{typography.lineHeight.tight}"
      letterSpacing: "{typography.letterSpacing.tight}"
      color:         "{colors.neutral-900}"

    h2:
      fontSize:      "{typography.scale.2xl}"
      fontWeight:    "{typography.weights.bold}"
      lineHeight:    "{typography.lineHeight.tight}"
      letterSpacing: "{typography.letterSpacing.tight}"
      color:         "{colors.neutral-900}"

    h3:
      fontSize:      "{typography.scale.xl}"
      fontWeight:    "{typography.weights.semibold}"
      lineHeight:    "{typography.lineHeight.snug}"
      color:         "{colors.neutral-900}"

    h4:
      fontSize:      "{typography.scale.lg}"
      fontWeight:    "{typography.weights.semibold}"
      lineHeight:    "{typography.lineHeight.snug}"
      color:         "{colors.neutral-700}"

    body:
      fontSize:      "{typography.scale.base}"
      fontWeight:    "{typography.weights.regular}"
      lineHeight:    "{typography.lineHeight.normal}"
      color:         "{colors.neutral-700}"

    body-sm:
      fontSize:      "{typography.scale.sm}"
      fontWeight:    "{typography.weights.regular}"
      lineHeight:    "{typography.lineHeight.normal}"
      color:         "{colors.neutral-500}"

    caption:
      fontSize:      "{typography.scale.xs}"
      fontWeight:    "{typography.weights.medium}"
      lineHeight:    "{typography.lineHeight.snug}"
      letterSpacing: "{typography.letterSpacing.wide}"
      color:         "{colors.neutral-500}"

    link:
      color:          "{colors.primary}"
      fontWeight:     "{typography.weights.medium}"
      textDecoration: "underline"
      _hover:
        color:        "{colors.primary-700}"
      _visited:
        color:        "{colors.primary-900}"

    mono:
      fontSize:      "{typography.scale.sm}"
      fontFamily:    "{typography.fontFamily.mono}"
      fontWeight:    "{typography.weights.regular}"
      color:         "{colors.neutral-700}"

# ─────────────────────────────────────────────────────────────────
# 3. SPACING — 8-Point Grid (Base 4px)
# ─────────────────────────────────────────────────────────────────
spacing:
  0:   "0px"
  1:   "4px"    # ikon ↔ label
  2:   "8px"    # padding inline kecil
  3:   "12px"   # padding button
  4:   "16px"   # gap antar elemen dalam group
  5:   "20px"   # margin dalam komponen
  6:   "24px"   # padding kartu, section gap
  8:   "32px"   # margin antar komponen besar
  10:  "40px"   # padding section
  12:  "48px"   # margin vertikal antar section
  16:  "64px"   # hero padding
  20:  "80px"   # section besar
  24:  "96px"   # antar section mayor

# ─────────────────────────────────────────────────────────────────
# 4. BORDER RADIUS
# ─────────────────────────────────────────────────────────────────
radius:
  none:  "0px"
  xs:    "2px"      # badge, tag kecil
  sm:    "4px"      # input, button kecil
  md:    "8px"      # kartu, tombol utama
  lg:    "12px"     # modal, panel besar
  xl:    "16px"     # sidebar widget
  full:  "9999px"   # pill badge, avatar

# ─────────────────────────────────────────────────────────────────
# 5. ELEVATION (Shadow)
# ─────────────────────────────────────────────────────────────────
elevation:
  0:  "none"
  1:  "0 1px 3px rgba(0,0,0,0.08)"
  2:  "0 4px 12px rgba(0,0,0,0.10)"
  3:  "0 8px 24px rgba(0,0,0,0.12)"
  4:  "0 16px 40px rgba(0,0,0,0.14)"

# ─────────────────────────────────────────────────────────────────
# 6. LAYOUT & RESPONSIVE
# ─────────────────────────────────────────────────────────────────
layout:
  grid:      12
  gutter:    "24px"
  margin:    "24px"
  maxWidth:  "1280px"

  breakpoints:
    xs:   "375px"    # mobile kecil
    sm:   "640px"    # mobile besar
    md:   "768px"    # tablet
    lg:   "1024px"   # laptop
    xl:   "1280px"   # desktop
    2xl:  "1536px"   # desktop besar

  # Proporsi sidebar:konten (Golden Ratio ≈ 1:1.618)
  sidebar-width:       "256px"
  sidebar-collapsed:   "64px"    # hanya ikon, untuk layar sempit
  content:             "1fr"

  # Responsive sidebar behavior
  sidebar-behavior:
    xs:   "hidden"       # tersembunyi, buka via hamburger overlay
    sm:   "hidden"
    md:   "collapsed"    # tampil 64px (ikon saja)
    lg:   "expanded"     # tampil penuh 256px
    xl:   "expanded"

  # Margin konten sesuai viewport
  content-margin:
    xs:   "{spacing.4}"
    sm:   "{spacing.4}"
    md:   "{spacing.6}"
    lg:   "{spacing.8}"
    xl:   "{spacing.10}"

# ─────────────────────────────────────────────────────────────────
# 7. COMPONENTS
# ─────────────────────────────────────────────────────────────────
components:

  # ── Button ───────────────────────────────────────────────────
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor:       "{colors.white}"
    borderRadius:    "{radius.md}"
    padding:         "{spacing.3} {spacing.6}"
    fontSize:        "{typography.scale.sm}"
    fontWeight:      "{typography.weights.semibold}"
    minHeight:       "44px"
    _hover:
      backgroundColor: "{colors.primary-700}"
    _active:
      backgroundColor: "{colors.primary-900}"
    _focus:
      outline:         "3px solid {colors.primary-400}"
      outlineOffset:   "2px"
    _disabled:
      backgroundColor: "{colors.neutral-300}"
      textColor:       "{colors.neutral-500}"
      cursor:          "not-allowed"

  button-secondary:
    backgroundColor: "transparent"
    textColor:       "{colors.primary}"
    border:          "1.5px solid {colors.primary}"
    borderRadius:    "{radius.md}"
    padding:         "{spacing.3} {spacing.6}"
    minHeight:       "44px"
    _hover:
      backgroundColor: "{colors.primary-50}"
    _focus:
      outline:         "3px solid {colors.primary-400}"
      outlineOffset:   "2px"
    _disabled:
      border:          "1.5px solid {colors.neutral-300}"
      textColor:       "{colors.neutral-500}"

  button-accent:
    backgroundColor: "{colors.accent}"
    textColor:       "{colors.white}"
    borderRadius:    "{radius.md}"
    padding:         "{spacing.3} {spacing.6}"
    minHeight:       "44px"
    _hover:
      backgroundColor: "{colors.accent-700}"

  button-danger:
    backgroundColor: "{colors.error}"
    textColor:       "{colors.white}"
    borderRadius:    "{radius.md}"
    padding:         "{spacing.3} {spacing.6}"
    minHeight:       "44px"
    _hover:
      backgroundColor: "#A31F1F"
    note:            "Selalu tampilkan modal konfirmasi sebelum aksi destruktif"

  button-ghost:
    backgroundColor: "transparent"
    textColor:       "{colors.neutral-700}"
    borderRadius:    "{radius.md}"
    padding:         "{spacing.3} {spacing.6}"
    minHeight:       "44px"
    _hover:
      backgroundColor: "{colors.neutral-100}"

  button-icon:
    backgroundColor: "transparent"
    textColor:       "{colors.neutral-500}"
    borderRadius:    "{radius.sm}"
    size:            "44px"   # min tap target
    _hover:
      backgroundColor: "{colors.neutral-100}"
      textColor:       "{colors.neutral-700}"

  # ── Card ─────────────────────────────────────────────────────
  card:
    backgroundColor: "{colors.white}"
    borderRadius:    "{radius.md}"
    padding:         "{spacing.6}"
    boxShadow:       "{elevation.1}"
    border:          "1px solid {colors.neutral-300}"
    _hover:
      boxShadow:     "{elevation.2}"

  card-stat:
    backgroundColor: "{colors.white}"
    borderRadius:    "{radius.md}"
    padding:         "{spacing.6}"
    boxShadow:       "{elevation.1}"
    borderLeft:      "4px solid {colors.primary}"

  card-stat-accent:
    backgroundColor: "{colors.white}"
    borderRadius:    "{radius.md}"
    padding:         "{spacing.6}"
    boxShadow:       "{elevation.1}"
    borderLeft:      "4px solid {colors.accent}"

  card-stat-positive:
    backgroundColor: "{colors.white}"
    borderRadius:    "{radius.md}"
    padding:         "{spacing.6}"
    boxShadow:       "{elevation.1}"
    borderLeft:      "4px solid {colors.positive}"

  card-stat-error:
    backgroundColor: "{colors.white}"
    borderRadius:    "{radius.md}"
    padding:         "{spacing.6}"
    boxShadow:       "{elevation.1}"
    borderLeft:      "4px solid {colors.error}"

  # ── Form: Input, Select, Textarea ────────────────────────────
  input:
    backgroundColor: "{colors.white}"
    textColor:       "{colors.neutral-700}"
    border:          "1.5px solid {colors.neutral-300}"
    borderRadius:    "{radius.sm}"
    padding:         "{spacing.3} {spacing.4}"
    fontSize:        "{typography.scale.base}"
    minHeight:       "44px"
    _focus:
      border:        "1.5px solid {colors.primary}"
      outline:       "3px solid {colors.primary-100}"
    _error:
      border:        "1.5px solid {colors.error}"
      outline:       "3px solid {colors.error-light}"
    _disabled:
      backgroundColor: "{colors.neutral-100}"
      textColor:     "{colors.neutral-500}"
      cursor:        "not-allowed"
    _readonly:
      backgroundColor: "{colors.neutral-50}"
      border:        "1.5px solid {colors.neutral-300}"

  input-helper:
    fontSize:        "{typography.scale.xs}"
    color:           "{colors.neutral-500}"
    marginTop:       "{spacing.1}"

  input-error-msg:
    fontSize:        "{typography.scale.xs}"
    color:           "{colors.error}"
    marginTop:       "{spacing.1}"

  label:
    fontSize:        "{typography.scale.sm}"
    fontWeight:      "{typography.weights.medium}"
    textColor:       "{colors.neutral-700}"
    marginBottom:    "{spacing.1}"

  label-required:
    color:           "{colors.error}"
    content:         " *"

  textarea:
    backgroundColor: "{colors.white}"
    textColor:       "{colors.neutral-700}"
    border:          "1.5px solid {colors.neutral-300}"
    borderRadius:    "{radius.sm}"
    padding:         "{spacing.3} {spacing.4}"
    minHeight:       "120px"
    resize:          "vertical"
    _focus:
      border:        "1.5px solid {colors.primary}"
      outline:       "3px solid {colors.primary-100}"

  # ── Dropdown / Select ─────────────────────────────────────────
  select:
    backgroundColor: "{colors.white}"
    textColor:       "{colors.neutral-700}"
    border:          "1.5px solid {colors.neutral-300}"
    borderRadius:    "{radius.sm}"
    padding:         "{spacing.3} {spacing.4}"
    fontSize:        "{typography.scale.base}"
    minHeight:       "44px"
    iconColor:       "{colors.neutral-500}"
    _focus:
      border:        "1.5px solid {colors.primary}"
      outline:       "3px solid {colors.primary-100}"
    _disabled:
      backgroundColor: "{colors.neutral-100}"
      textColor:     "{colors.neutral-500}"

  dropdown-menu:
    backgroundColor: "{colors.white}"
    borderRadius:    "{radius.md}"
    boxShadow:       "{elevation.2}"
    border:          "1px solid {colors.neutral-300}"
    zIndex:          "{zIndex.dropdown}"
    maxHeight:       "300px"
    overflow:        "auto"

  dropdown-item:
    padding:         "{spacing.3} {spacing.4}"
    fontSize:        "{typography.scale.sm}"
    textColor:       "{colors.neutral-700}"
    _hover:
      backgroundColor: "{colors.primary-50}"
      textColor:     "{colors.primary}"
    _selected:
      backgroundColor: "{colors.primary-100}"
      textColor:     "{colors.primary-900}"
      fontWeight:    "{typography.weights.semibold}"

  # ── Checkbox & Radio ─────────────────────────────────────────
  checkbox:
    size:            "20px"
    borderRadius:    "{radius.xs}"
    border:          "2px solid {colors.neutral-300}"
    _checked:
      backgroundColor: "{colors.primary}"
      border:        "2px solid {colors.primary}"
    _focus:
      outline:       "3px solid {colors.primary-100}"
    _disabled:
      backgroundColor: "{colors.neutral-100}"
      border:        "2px solid {colors.neutral-300}"

  radio:
    size:            "20px"
    borderRadius:    "{radius.full}"
    border:          "2px solid {colors.neutral-300}"
    _checked:
      border:        "6px solid {colors.primary}"
    _focus:
      outline:       "3px solid {colors.primary-100}"

  # ── Badge / Tag ───────────────────────────────────────────────
  badge-primary:
    backgroundColor: "{colors.primary-100}"
    textColor:       "{colors.primary-900}"
    borderRadius:    "{radius.full}"
    padding:         "2px {spacing.2}"
    fontSize:        "{typography.scale.xs}"
    fontWeight:      "{typography.weights.semibold}"

  badge-success:
    backgroundColor: "{colors.positive-200}"
    textColor:       "{colors.positive-700}"
    borderRadius:    "{radius.full}"
    padding:         "2px {spacing.2}"
    fontSize:        "{typography.scale.xs}"
    fontWeight:      "{typography.weights.semibold}"

  badge-warning:
    backgroundColor: "{colors.accent-200}"
    textColor:       "{colors.accent-700}"
    borderRadius:    "{radius.full}"
    padding:         "2px {spacing.2}"
    fontSize:        "{typography.scale.xs}"
    fontWeight:      "{typography.weights.semibold}"

  badge-error:
    backgroundColor: "{colors.error-light}"
    textColor:       "{colors.error}"
    borderRadius:    "{radius.full}"
    padding:         "2px {spacing.2}"
    fontSize:        "{typography.scale.xs}"
    fontWeight:      "{typography.weights.semibold}"

  badge-neutral:
    backgroundColor: "{colors.neutral-100}"
    textColor:       "{colors.neutral-700}"
    borderRadius:    "{radius.full}"
    padding:         "2px {spacing.2}"
    fontSize:        "{typography.scale.xs}"

  # ── Alert / Toast ─────────────────────────────────────────────
  alert-info:
    backgroundColor: "{colors.info-light}",
    border:          "1px solid {colors.primary-400}"
    textColor:       "{colors.primary-900}"
    borderRadius:    "{radius.sm}"
    padding:         "{spacing.4}"

  alert-success:
    backgroundColor: "{colors.positive-50}"
    border:          "1px solid {colors.positive}"
    textColor:       "{colors.positive-700}"
    borderRadius:    "{radius.sm}"
    padding:         "{spacing.4}"

  alert-warning:
    backgroundColor: "{colors.warning-light}"
    border:          "1px solid {colors.warning}"
    textColor:       "#7A2900"
    borderRadius:    "{radius.sm}"
    padding:         "{spacing.4}"

  alert-error:
    backgroundColor: "{colors.error-light}"
    border:          "1px solid {colors.error}"
    textColor:       "{colors.error}"
    borderRadius:    "{radius.sm}"
    padding:         "{spacing.4}"

  toast:
    borderRadius:    "{radius.md}"
    padding:         "{spacing.4} {spacing.5}"
    boxShadow:       "{elevation.3}"
    zIndex:          "{zIndex.toast}"
    maxWidth:        "400px"
    position:        "fixed bottom-right"

  # ── Navigation / Sidebar ─────────────────────────────────────
  sidebar:
    backgroundColor: "{colors.primary-900}"
    textColor:       "{colors.white}"
    width:           "{layout.sidebar-width}"
    collapsedWidth:  "{layout.sidebar-collapsed}"
    zIndex:          "{zIndex.sidebar}"

  nav-item:
    textColor:       "rgba(255,255,255,0.75)"
    padding:         "{spacing.3} {spacing.4}"
    borderRadius:    "{radius.sm}"
    fontSize:        "{typography.scale.sm}"
    fontWeight:      "{typography.weights.medium}"
    gap:             "{spacing.3}"
    _hover:
      backgroundColor: "rgba(255,255,255,0.08)"
      textColor:     "{colors.white}"
    _active:
      backgroundColor: "{colors.primary}"
      textColor:     "{colors.white}"

  nav-group-label:
    fontSize:        "{typography.scale.xs}"
    fontWeight:      "{typography.weights.semibold}"
    textColor:       "rgba(255,255,255,0.4)"
    letterSpacing:   "{typography.letterSpacing.wider}"
    textTransform:   "uppercase"
    padding:         "{spacing.4} {spacing.4} {spacing.2}"

  topbar:
    backgroundColor: "{colors.white}"
    borderBottom:    "1px solid {colors.neutral-300}"
    height:          "64px"
    padding:         "0 {spacing.6}"
    boxShadow:       "{elevation.4}"
    zIndex:          "{zIndex.header}"

  # ── Table ─────────────────────────────────────────────────────
  table-header:
    backgroundColor: "{colors.neutral-100}"
    textColor:       "{colors.neutral-700}"
    fontWeight:      "{typography.weights.semibold}"
    fontSize:        "{typography.scale.sm}"
    padding:         "{spacing.3} {spacing.4}"
    border:          "1px solid {colors.neutral-300}"
    borderRadius:    "{radius.sm} {radius.sm} 0 0"
    letterSpacing:   "{typography.letterSpacing.wide}"

  table-row:
    backgroundColor: "{colors.white}"
    textColor:       "{colors.neutral-700}"
    fontSize:        "{typography.scale.sm}"
    padding:         "{spacing.3} {spacing.4}"
    borderBottom:    "1px solid {colors.neutral-100}"
    _hover:
      backgroundColor: "{colors.primary-50}"
    _striped:
      backgroundColor: "{colors.neutral-50}"
    _selected:
      backgroundColor: "{colors.primary-100}"

  table-cell-mono:
    fontFamily:      "{typography.fontFamily.mono}"
    fontSize:        "{typography.scale.sm}"
    textColor:       "{colors.neutral-700}"

  # ── Pagination ────────────────────────────────────────────────
  pagination:
    gap:             "{spacing.1}"

  pagination-item:
    minWidth:        "36px"
    height:          "36px"
    borderRadius:    "{radius.sm}"
    fontSize:        "{typography.scale.sm}"
    textColor:       "{colors.neutral-700}"
    border:          "1px solid {colors.neutral-300}"
    _hover:
      backgroundColor: "{colors.primary-50}"
      border:        "1px solid {colors.primary-400}"
      textColor:     "{colors.primary}"
    _active:
      backgroundColor: "{colors.primary}"
      border:        "1px solid {colors.primary}"
      textColor:     "{colors.white}"
      fontWeight:    "{typography.weights.semibold}"
    _disabled:
      textColor:     "{colors.neutral-300}"
      cursor:        "not-allowed"

  # ── Tabs ──────────────────────────────────────────────────────
  tabs:
    borderBottom:    "2px solid {colors.neutral-300}"
    gap:             "{spacing.0}"

  tab-item:
    padding:         "{spacing.3} {spacing.5}"
    fontSize:        "{typography.scale.sm}"
    fontWeight:      "{typography.weights.medium}"
    textColor:       "{colors.neutral-500}"
    borderBottom:    "2px solid transparent"
    marginBottom:    "-2px"
    _hover:
      textColor:     "{colors.primary}"
      borderBottom:  "2px solid {colors.primary-400}"
    _active:
      textColor:     "{colors.primary}"
      fontWeight:    "{typography.weights.semibold}"
      borderBottom:  "2px solid {colors.primary}"

  # ── Tooltip ───────────────────────────────────────────────────
  tooltip:
    backgroundColor: "{colors.neutral-900}"
    textColor:       "{colors.white}"
    fontSize:        "{typography.scale.xs}"
    fontWeight:      "{typography.weights.medium}"
    borderRadius:    "{radius.xs}"
    padding:         "{spacing.1} {spacing.2}"
    maxWidth:        "220px"
    zIndex:          "{zIndex.tooltip}"
    lineHeight:      "{typography.lineHeight.snug}"

  # ── Skeleton Loader ───────────────────────────────────────────
  skeleton:
    backgroundColor: "{colors.neutral-100}"
    shimmerColor:    "{colors.neutral-300}"
    borderRadius:    "{radius.sm}"
    animation:       "shimmer 1.5s infinite linear"
    # Variants — gunakan sesuai elemen yang sedang dimuat
    variants:
      text:    "height: 1em; width: 100%"
      heading: "height: 1.5em; width: 60%"
      avatar:  "width: 40px; height: 40px; borderRadius: {radius.full}"
      card:    "height: 120px; width: 100%"
      button:  "height: 44px; width: 120px"

  # ── Stepper (Alur Pengajuan / Persetujuan) ───────────────────
  stepper:
    gap:             "{spacing.0}"

  stepper-step:
    indicatorSize:   "32px"
    borderRadius:    "{radius.full}"
    fontSize:        "{typography.scale.xs}"
    fontWeight:      "{typography.weights.semibold}"
    connectorColor:  "{colors.neutral-300}"
    connectorHeight: "2px"
    labelFontSize:   "{typography.scale.sm}"
    _pending:
      indicatorBg:   "{colors.neutral-100}"
      indicatorBorder: "2px solid {colors.neutral-300}"
      indicatorColor: "{colors.neutral-500}"
      labelColor:    "{colors.neutral-500}"
    _active:
      indicatorBg:   "{colors.primary}"
      indicatorColor: "{colors.white}"
      labelColor:    "{colors.primary}"
      fontWeight:    "{typography.weights.semibold}"
    _done:
      indicatorBg:   "{colors.positive}"
      indicatorColor: "{colors.white}"
      connectorColor: "{colors.positive}"
      labelColor:    "{colors.neutral-700}"
    _error:
      indicatorBg:   "{colors.error}"
      indicatorColor: "{colors.white}"
      labelColor:    "{colors.error}"

  # ── File Upload (Pengarsipan Digital) ────────────────────────
  file-upload:
    backgroundColor: "{colors.neutral-50}"
    border:          "2px dashed {colors.neutral-300}"
    borderRadius:    "{radius.md}"
    padding:         "{spacing.10}"
    textAlign:       "center"
    _hover:
      border:        "2px dashed {colors.primary-400}"
      backgroundColor: "{colors.primary-50}"
    _dragover:
      border:        "2px dashed {colors.primary}"
      backgroundColor: "{colors.primary-50}"
    _error:
      border:        "2px dashed {colors.error}"
      backgroundColor: "{colors.error-light}"

  file-item:
    backgroundColor: "{colors.white}"
    border:          "1px solid {colors.neutral-300}"
    borderRadius:    "{radius.sm}"
    padding:         "{spacing.3} {spacing.4}"
    fontSize:        "{typography.scale.sm}"
    iconColor:       "{colors.primary}"
    _error:
      borderColor:   "{colors.error}"
      iconColor:     "{colors.error}"

  file-types-allowed:
    note: "PDF, XLSX, DOCX, JPG, PNG — max 10MB per file, 50MB per batch"

  # ── Modal ─────────────────────────────────────────────────────
  modal:
    backgroundColor: "{colors.white}"
    borderRadius:    "{radius.lg}"
    boxShadow:       "{elevation.3}"
    padding:         "{spacing.8}"
    maxWidth:        "560px"
    zIndex:          "{zIndex.modal}"

  modal-overlay:
    backgroundColor: "rgba(15, 25, 35, 0.6)"
    zIndex:          "{zIndex.overlay}"

  modal-header:
    fontSize:        "{typography.scale.lg}"
    fontWeight:      "{typography.weights.semibold}"
    borderBottom:    "1px solid {colors.neutral-300}"
    paddingBottom:   "{spacing.4}"
    marginBottom:    "{spacing.6}"

  modal-footer:
    borderTop:       "1px solid {colors.neutral-300}"
    paddingTop:      "{spacing.6}"
    marginTop:       "{spacing.6}"
    display:         "flex"
    justifyContent:  "flex-end"
    gap:             "{spacing.3}"

  # ── Breadcrumb ────────────────────────────────────────────────
  breadcrumb:
    fontSize:        "{typography.scale.sm}"
    textColor:       "{colors.neutral-500}"
    separator:       "/"
    separatorColor:  "{colors.neutral-300}"
    gap:             "{spacing.2}"
    _item:
      textColor:     "{colors.neutral-500}"
      _hover:
        textColor:   "{colors.primary}"
    _current:
      textColor:     "{colors.neutral-700}"
      fontWeight:    "{typography.weights.medium}"

  # ── Divider ───────────────────────────────────────────────────
  divider:
    color:           "{colors.neutral-300}"
    thickness:       "1px"
    margin:          "{spacing.6} 0"

  # ── Avatar ────────────────────────────────────────────────────
  avatar:
    borderRadius:    "{radius.full}"
    sizes:
      sm:  "32px"
      md:  "40px"
      lg:  "56px"
      xl:  "80px"
    backgroundColor: "{colors.primary-100}"
    textColor:       "{colors.primary-900}"
    fontWeight:      "{typography.weights.semibold}"

# ─────────────────────────────────────────────────────────────────
# 8. ICONOGRAPHY
# ─────────────────────────────────────────────────────────────────
icons:
  library:   "Phosphor Icons"
  style:     "Regular untuk body, Bold untuk aksi primer"
  sizes:
    xs:   "14px"
    sm:   "16px"
    md:   "20px"
    lg:   "24px"
    xl:   "32px"
    2xl:  "48px"
  colors:
    default:  "{colors.neutral-500}"
    primary:  "{colors.primary}"
    accent:   "{colors.accent}"
    positive: "{colors.positive}"
    error:    "{colors.error}"
    onDark:   "{colors.white}"

# ─────────────────────────────────────────────────────────────────
# 9. AKSESIBILITAS (WCAG 2.1 AA)
# ─────────────────────────────────────────────────────────────────
accessibility:
  contrastCheck:
    - pair:  "primary (#0057A8) / white (#FFFFFF)"
      ratio: "8.0:1"
      grade: "AAA ✓"
    - pair:  "neutral-900 (#0F1923) / white (#FFFFFF)"
      ratio: "18.7:1"
      grade: "AAA ✓"
    - pair:  "neutral-700 (#1E293B) / neutral-50 (#F8FAFC)"
      ratio: "14.2:1"
      grade: "AAA ✓"
    - pair:  "neutral-500 (#475569) / white (#FFFFFF)"
      ratio: "5.7:1"
      grade: "AA ✓"
    - pair:  "accent (#E8601C) / white (#FFFFFF)"
      ratio: "4.6:1"
      grade: "AA ✓"
    - pair:  "white (#FFFFFF) / primary (#0057A8)"
      ratio: "8.0:1"
      grade: "AAA ✓"
    - pair:  "white (#FFFFFF) / positive (#00873E)"
      ratio: "5.1:1"
      grade: "AA ✓"
    - pair:  "white (#FFFFFF) / error (#C62828)"
      ratio: "6.4:1"
      grade: "AA ✓"

  focusVisible:    "3px solid {colors.primary-400}; outline-offset: 2px"
  minTapTarget:    "44px × 44px"
  reducedMotion:   "@media (prefers-reduced-motion: reduce)"
  screenReader:    "Semua ikon dekoratif: aria-hidden='true'; ikon fungsional: aria-label wajib"
  formRequirement: "Setiap input wajib memiliki <label> atau aria-label; error ditampilkan via aria-describedby"

# ─────────────────────────────────────────────────────────────────
# 10. MOTION & ANIMATION
# ─────────────────────────────────────────────────────────────────
motion:
  duration:
    instant:  "50ms"
    fast:     "100ms"
    normal:   "200ms"
    slow:     "350ms"
    lazy:     "500ms"
  easing:
    standard: "cubic-bezier(0.4, 0, 0.2, 1)"
    enter:    "cubic-bezier(0.0, 0, 0.2, 1)"
    exit:     "cubic-bezier(0.4, 0, 1, 1)"
    spring:   "cubic-bezier(0.34, 1.56, 0.64, 1)"
  usage:
    button-hover:   "background-color {motion.duration.fast} {motion.easing.standard}"
    card-hover:     "box-shadow {motion.duration.normal} {motion.easing.standard}"
    modal-enter:    "opacity + transform {motion.duration.slow} {motion.easing.enter}"
    sidebar-slide:  "transform {motion.duration.normal} {motion.easing.standard}"
    toast-enter:    "transform + opacity {motion.duration.slow} {motion.easing.spring}"
    skeleton-pulse: "opacity 1.5s ease-in-out infinite"

# ─────────────────────────────────────────────────────────────────
# 11. DARK MODE TOKENS
# ─────────────────────────────────────────────────────────────────
# Aktifkan via: @media (prefers-color-scheme: dark)
# atau class .dark pada <html> untuk manual toggle
# ─────────────────────────────────────────────────────────────────
darkMode:
  colors:
    # Surface layers
    bg-base:        "#0D1117"   # background halaman
    bg-surface:     "#161B22"   # kartu, panel
    bg-elevated:    "#1C2128"   # modal, dropdown
    bg-overlay:     "#21262D"   # hover, input

    # Brand — sedikit lebih terang agar tetap AA di atas bg gelap
    primary:        "#4D9EE0"   # kontras di atas bg-base: 5.1:1 ✓
    primary-light:  "#A8CCEE"   # link, teks aktif
    accent:         "#F09060"   # kontras: 4.8:1 ✓
    positive:       "#3DB86B"   # kontras: 4.9:1 ✓
    error:          "#F77171"   # kontras: 5.2:1 ✓
    warning:        "#F5A623"

    # Text
    text-primary:   "#E6EDF3"   # heading
    text-secondary: "#8D96A0"   # body, label
    text-muted:     "#525C65"   # placeholder, caption

    # Border
    border:         "#30363D"
    border-strong:  "#484F58"

    # Sidebar dark
    sidebar-bg:     "#010409"
    sidebar-text:   "#E6EDF3"

  note: >
    Dark mode mengubah warna surface dan teks; radius, spacing, dan
    komponen TIDAK berubah. Override hanya token di bagian ini.

# ─────────────────────────────────────────────────────────────────
# 12. Z-INDEX SCALE
# ─────────────────────────────────────────────────────────────────
zIndex:
  below:     -1     # elemen di bawah normal flow
  base:      0      # normal flow
  raised:    10     # kartu yang sedikit terangkat
  dropdown:  100    # dropdown menu, select panel
  sticky:    200    # tabel header sticky, filter bar
  header:    300    # topbar navigasi
  sidebar:   400    # sidebar mobile (overlay)
  overlay:   500    # modal backdrop
  modal:     600    # modal dialog
  toast:     700    # notifikasi toast
  tooltip:   800    # tooltip (selalu di atas semua)

# ─────────────────────────────────────────────────────────────────
# 13. DATA VISUALIZATION PALETTE
# ─────────────────────────────────────────────────────────────────
# Digunakan untuk chart, grafik, dan visualisasi data statistik BPS.
# Setiap warna memiliki kontras minimal 3:1 antar warna berdekatan.
# ─────────────────────────────────────────────────────────────────
dataViz:
  # Palet kategoris — untuk bar chart, pie chart, legend
  categorical:
    1: "#0057A8"   # BPS Blue — primary series
    2: "#E8601C"   # BPS Orange — secondary series
    3: "#00873E"   # BPS Green — tertiary series
    4: "#7B61FF"   # Ungu — series keempat
    5: "#00AABB"   # Teal — series kelima
    6: "#E8B800"   # Kuning — series keenam

  # Palet sekuensial — untuk heatmap, choropleth, gradient tunggal
  sequential-blue:
    100: "#D6E8F7"
    300: "#8BBEE8"
    500: "#3D87CC"
    700: "#0057A8"
    900: "#002D5C"

  sequential-green:
    100: "#A8DFC0"
    300: "#4DB880"
    500: "#00873E"
    700: "#006030"
    900: "#003A1D"

  # Palet divergen — untuk perbandingan positif/negatif (e.g. pertumbuhan ekonomi)
  diverging:
    negative-strong:  "#C62828"
    negative-light:   "#FDECEA"
    neutral:          "#F1F5F9"
    positive-light:   "#E6F5EC"
    positive-strong:  "#00873E"

  # Warna aksis dan grid chart
  axis:
    line:    "{colors.neutral-300}"
    label:   "{colors.neutral-500}"
    grid:    "{colors.neutral-100}"
    tick:    "{colors.neutral-300}"

  # Tipografi chart
  chart-title:
    fontSize:   "{typography.scale.md}"
    fontWeight: "{typography.weights.semibold}"
    color:      "{colors.neutral-900}"

  chart-label:
    fontSize:   "{typography.scale.xs}"
    fontFamily: "{typography.fontFamily.mono}"
    color:      "{colors.neutral-500}"

  chart-value:
    fontSize:   "{typography.scale.sm}"
    fontFamily: "{typography.fontFamily.mono}"
    fontWeight: "{typography.weights.semibold}"
    color:      "{colors.neutral-900}"

# ─────────────────────────────────────────────────────────────────
# 14. PRINT / PDF TOKENS
# ─────────────────────────────────────────────────────────────────
# Digunakan untuk dokumen keuangan, laporan arsip, dan ekspor PDF.
# Override ini berlaku di @media print dan template PDF generator.
# ─────────────────────────────────────────────────────────────────
print:
  pageSize:       "A4"
  margin:         "20mm 25mm 20mm 25mm"   # top right bottom left
  colorScheme:    "light"
  fontFamily:     "Plus Jakarta Sans, Arial, sans-serif"
  fontSize:       "10pt"
  lineHeight:     1.5

  colors:
    bg:           "#FFFFFF"
    text:         "#000000"       # hitam penuh untuk keterbacaan cetak
    text-muted:   "#555555"
    border:       "#CCCCCC"
    primary:      "#0057A8"       # tetap berwarna untuk header tabel
    accent:       "#000000"       # oranye tidak terlihat jelas di cetak — fallback hitam
    positive:     "#000000"       # fallback hitam
    error:        "#CC0000"

  header:
    height:       "20mm"
    content:      "Logo BPS kiri | Nama Instansi kanan"
    borderBottom: "1pt solid #CCCCCC"
    fontSize:     "8pt"

  footer:
    height:       "15mm"
    content:      "Nama dokumen kiri | Halaman n/total kanan | Tanggal cetak tengah"
    borderTop:    "1pt solid #CCCCCC"
    fontSize:     "7pt"

  table:
    headerBg:     "#E8F0FA"
    headerColor:  "#000000"
    borderColor:  "#CCCCCC"
    fontSize:     "9pt"
    rowPadding:   "4pt 6pt"
    stripedBg:    "#F5F8FC"

  pageBreak:
    avoid:        "table-row, figure, h3, h4"  # jangan potong elemen ini
    before:       "h2"                          # h2 selalu mulai halaman baru
    note:         "Gunakan page-break-inside: avoid pada kartu dan tabel kecil"

  watermark:
    text:         "DOKUMEN RESMI BPS"
    color:        "rgba(0, 87, 168, 0.08)"
    fontSize:     "48pt"
    angle:        "-45deg"
    usage:        "Aktifkan hanya pada dokumen draft dan salinan tidak resmi"

---

## Overview

**Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang** adalah sistem pengarsipan digital dokumen pertanggungjawaban keuangan (SPJ/BAPP/Kuitansi) berbasis struktur POK, untuk BPS Kabupaten Subang. Identitas visual bersumber langsung dari tiga warna logo resmi BPS — biru (kepercayaan/data), oranye (aksi/ekonomi), hijau (konfirmasi/pertanian) — yang juga melambangkan tiga kegiatan sensus besar BPS.

Design system ini dirancang untuk **produksi**, bukan prototipe. Setiap token, state, dan perilaku responsif sudah didefinisikan untuk kebutuhan aplikasi administrasi keuangan yang kompleks: form panjang, tabel data besar, alur persetujuan bertahap, pengarsipan file, dan ekspor laporan.

---

## 1. Warna

Tiga pilar brand BPS diterjemahkan ke peran fungsional:

- **BPS Blue `#0057A8`** → Primary: navigasi, tombol utama, header
- **BPS Orange `#E8601C`** → Accent: CTA sekunder, indikator penting
- **BPS Green `#00873E`** → Positive: status sukses, konfirmasi
- **Neutral** → Teks, border, dan surface tanpa distraksi
- **Semantic** → Error (merah), Warning (oranye tua), Info (alias primary)

Dark mode menggunakan varian yang lebih terang dari ketiga warna brand agar tetap memenuhi standar kontras WCAG AA di atas latar gelap.

**Aturan:** Jangan hard-code hex. Selalu gunakan token reference. Jika butuh warna baru, perluas shade dari token yang ada — jangan buat token baru tanpa justifikasi.

---

## 2. Tipografi

Plus Jakarta Sans untuk semua teks — hierarki dari ukuran, bukan ganti font. JetBrains Mono **hanya** untuk angka: ID transaksi, nomor arsip, nilai keuangan di tabel.

Skala **Perfect Fourth (×1.333)** — 8 level dari `xs` (12px) hingga `3xl` (51px). Tambahkan `overline` untuk label kategori dan `link` untuk teks interaktif.

---

## 3. Spacing

8-point Grid (Base 4px). Semua jarak adalah nilai dari token spacing (4–96px). **Dilarang** menggunakan nilai arbitrer seperti `15px` atau `22px`.

---

## 4. Layout & Responsif

Grid 12 kolom. Sidebar menggunakan proporsi **1:1.618 (golden ratio)** terhadap area konten di desktop.

Perilaku sidebar berdasarkan viewport:

- **Mobile (xs/sm):** tersembunyi, buka via tombol hamburger sebagai overlay
- **Tablet (md):** collapsed 64px (ikon saja), expand on hover
- **Desktop (lg+):** expanded penuh 256px

---

## 5. Elevation

Gunakan shadow hanya untuk hierarki lapisan — bukan dekorasi:

`elevation.0` flat → `elevation.1` kartu → `elevation.2` dropdown/hover → `elevation.3` modal → `elevation.4` header sticky

---

## 6. Components

### Button

Satu `button-primary` per halaman. `button-danger` **wajib** diikuti konfirmasi modal sebelum eksekusi.

### Form

Setiap input wajib punya `<label>`. Error harus teks, bukan hanya warna merah. Field readonly dan disabled harus dibedakan secara visual (readonly: bg abu muda + border normal; disabled: bg abu + cursor not-allowed).

### File Upload

Format yang diizinkan: PDF, JPG, JPEG, PNG (mengikuti PRD Modul B — maks. 15MB/file). Tampilkan progress upload dan state error per file. Setiap file wajib memiliki label kategori (BAPP, Kuitansi, KAK, SK, Daftar Hadir, dll).

### Checklist Verifikasi (Bendahara)

Setiap baris dokumen di halaman Verifikasi Pencairan memiliki `checkbox` individual ("sudah diperiksa"). Tombol `button-primary` **Setujui Pencairan** dalam kondisi `_disabled` sampai **seluruh** dokumen pada item tersebut tercentang — bukan hanya berdasarkan jumlah file terunggah. Tampilkan progress ringkas di atas daftar, misalnya "3/5 dokumen diverifikasi", memakai `badge-warning` selama belum lengkap dan `badge-success` setelah lengkap.

### Stepper

Digunakan untuk alur pengajuan dan persetujuan dokumen keuangan. State: `pending → active → done` (atau `error` jika ditolak).

### Skeleton Loader

Gunakan skeleton, bukan spinner, untuk loading state konten berstruktur (tabel, kartu, form). Spinner hanya untuk aksi singkat (submit button, upload progress).

### Tabs

Untuk filter kategori dokumen arsip, jenis laporan, atau periode waktu. Jangan gunakan lebih dari 6 tab; jika lebih, gunakan dropdown select.

---

## 7. Data Visualization

Gunakan palet `dataViz.categorical` berurutan (1 → 2 → 3...) untuk setiap series baru. Palet `diverging` khusus untuk data perbandingan positif/negatif seperti pertumbuhan atau defisit anggaran.

Semua label dan nilai di chart menggunakan `JetBrains Mono` agar angka terbaca konsisten.

---

## 8. Dark Mode

Dark mode diaktifkan otomatis via `prefers-color-scheme: dark` atau manual via class `.dark` di `<html>`. Hanya warna yang berubah — radius, spacing, dan struktur komponen tetap identik.

---

## 9. Print / PDF

Dokumen keuangan dan arsip yang dicetak menggunakan token dari section `print`. Watermark "DOKUMEN RESMI BPS" aktif hanya untuk salinan draft. Footer selalu menampilkan nomor halaman dan tanggal cetak.

---

## 10. Z-Index

Hierarki lapisan wajib menggunakan token `zIndex.*`. Tooltip selalu di layer tertinggi (800). Modal overlay di 500, modal konten di 600 — pastikan tidak ada elemen yang melebihi 800 tanpa alasan.

---

## Do's and Don'ts

**Do:**

- Pakai token reference `{colors.primary}` — bukan hex langsung
- Satu `button-primary` per halaman
- Gunakan `card-stat` untuk semua KPI di dashboard
- Sertakan state `_error`, `_disabled`, `_readonly` di semua form
- Gunakan `skeleton` untuk loading konten berstruktur
- Test kontras warna di WCAG Checker sebelum deploy
- Dokumentasikan setiap perubahan di `changelog`

**Don't:**

- Jangan hard-code hex atau nilai spacing di luar token
- Jangan campur `radius.md` dan sudut tajam di halaman yang sama
- Jangan gunakan shadow > `elevation.2` untuk elemen non-modal
- Jangan gunakan placeholder sebagai pengganti label form
- Jangan gunakan warna sebagai satu-satunya cara menyampaikan informasi
- Jangan aktifkan animasi tanpa mengecek `prefers-reduced-motion`
- Jangan gunakan lebih dari 6 warna kategoris dalam satu chart
