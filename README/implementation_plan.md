# Sistem Data Digital Arsip Keuangan BPS — Implementation Plan

Membangun MVP web-app arsip keuangan BPS Kabupaten Subang: pengelolaan hirarki POK 7-level, multi-file upload SPJ/BAPP/Kuitansi, pratinjau inline PDF, dan verifikasi pencairan oleh Bendahara.

---

## User Review Required

> [!IMPORTANT]
> **Tech Stack Decision**: Berdasarkan PRD, disarankan menggunakan **Next.js (App Router) + Prisma + SQLite** sebagai stack utama MVP karena:
> - Next.js App Router mendukung API Routes bawaan (tidak perlu backend terpisah)
> - Prisma ORM mempercepat migrasi dan seeding database
> - SQLite cukup untuk MVP lokal BPS Subang (mudah migrate ke PostgreSQL/MySQL nanti)
>
> Apakah stack ini sudah sesuai, atau Anda menginginkan **Laravel + Inertia.js + MySQL/PostgreSQL**?

> [!IMPORTANT]
> **File Storage**: File upload akan disimpan di `/public/uploads/{year}/{sub_output_code}/{item_code}/` (lokal server). Untuk production, bisa diganti ke S3/MinIO. Apakah ini sudah sesuai?

> [!WARNING]
> **Folder Target**: Semua file proyek akan dibuat di `d:\Project BPS\` (workspace utama). Direktori `README\` sudah ada. Proyek Next.js akan diinisialisasi langsung di root `d:\Project BPS\`.

---

## Open Questions

1. **Database Engine**: SQLite (simpel, lokal) vs PostgreSQL/MySQL (production-grade)?  
   → *Default rencana: SQLite untuk MVP, mudah migrate nanti.*
2. **Port development server**: Default Next.js `localhost:3000` — apakah ada konflik port?
3. **Node.js version**: Apakah sudah terinstall Node.js ≥ 18?

---

## Proposed Changes

### Phase 1: Project Bootstrap & Database

#### [NEW] `d:\Project BPS\` — Next.js App Router Project
Init dengan `npx create-next-app@latest` di `d:\Project BPS\`.

#### [NEW] `prisma/schema.prisma`
Schema lengkap:
- `fiscal_years`, `users`, `programs`, `outputs`, `sub_outputs`
- `components`, `sub_components`, `accounts`, `items`, `documents`

#### [NEW] `prisma/seed.ts`
Full seeder dengan:
- 4 default users (Admin, Supervisor, Operator, Bendahara)
- Hirarki POK lengkap: GG.2902 → BMA.004, BMA.006 (focus MVP), FAN.ZZ1
- Semua item dari 000698 s/d 001512

---

### Phase 2: Authentication & Layout

#### [NEW] `app/api/auth/[...nextauth]/route.ts`
NextAuth.js dengan CredentialsProvider — login via NIP/password (bcrypt).

#### [NEW] `app/(auth)/login/page.tsx`
Halaman login dengan form NIP + Password, branding BPS.

#### [NEW] `components/layout/Sidebar.tsx`
POK Treeview Sidebar dinamis — fetch dari DB, render hirarki collapsible.

#### [NEW] `components/layout/AppLayout.tsx`
Layout utama dengan sidebar + header + konten area, responsive.

---

### Phase 3: Multi-File Upload

#### [NEW] `app/api/items/[itemId]/documents/route.ts`
API endpoint POST upload (multipart/form-data), GET list dokumen per item.

#### [NEW] `app/api/documents/[docId]/route.ts`
DELETE endpoint, dan GET (stream file dengan auth check).

#### [NEW] `app/(dashboard)/items/[itemId]/page.tsx`
Item Detail page:
- Info breadcrumb: Program → Output → Sub-Output → Komponen → Sub-Komponen → Akun → Item
- Status badge (PENDING/APPROVED/REJECTED) dengan warna
- Drag-and-drop zone multi-file (react-dropzone)
- Tabel daftar dokumen dengan preview, download, delete

---

### Phase 4: PDF Previewer & Bendahara Approval

#### [NEW] `components/DocumentViewer.tsx`
Modal inline PDF/Image viewer menggunakan native `<iframe>` embed + PDF.js fallback.

#### [NEW] `app/api/items/[itemId]/verify/route.ts`
PATCH endpoint: APPROVED / REJECTED (dengan rejection_note).

#### Modifikasi `app/(dashboard)/items/[itemId]/page.tsx`
Tambah panel Bendahara:
- Tombol "✅ Setujui Pencairan" → status APPROVED (hijau)
- Tombol "❌ Tolak / Butuh Revisi" → modal input catatan → status REJECTED (merah)

---

### Phase 5: Dynamic Master Data CRUD

#### [NEW] `app/(dashboard)/master/page.tsx`
Halaman manajemen POK untuk Supervisor/Admin:
- Tabel navigasi per-level
- Form tambah/edit Program, Output, Sub-Output, Komponen, Sub-Komponen, Akun, Item

#### [NEW] `app/(dashboard)/users/page.tsx`
Halaman manajemen user untuk Admin: tambah, edit role, reset password.

---

## Database Schema (Prisma)

```prisma
model FiscalYear {
  id        Int       @id @default(autoincrement())
  year      Int       @unique
  isActive  Boolean   @default(true)
  programs  Program[]
}

model User {
  id           Int        @id @default(autoincrement())
  nipUsername  String     @unique
  passwordHash String
  name         String
  role         Role       @default(OPERATOR)
  createdAt    DateTime   @default(now())
  documents    Document[]
}

enum Role { ADMIN SUPERVISOR OPERATOR BENDAHARA }

model Program {
  id           Int        @id @default(autoincrement())
  fiscalYearId Int
  fiscalYear   FiscalYear @relation(fields: [fiscalYearId], references: [id])
  code         String     // e.g. GG.2902
  name         String
  outputs      Output[]
}

model Output {
  id         Int        @id @default(autoincrement())
  programId  Int
  program    Program    @relation(fields: [programId], references: [id])
  code       String     // e.g. BMA, FAN
  name       String
  subOutputs SubOutput[]
}

model SubOutput {
  id         Int         @id @default(autoincrement())
  outputId   Int
  output     Output      @relation(fields: [outputId], references: [id])
  code       String      // e.g. BMA.004, BMA.006, FAN.ZZ1
  name       String
  components Component[]
}

model Component {
  id           Int           @id @default(autoincrement())
  subOutputId  Int
  subOutput    SubOutput     @relation(fields: [subOutputId], references: [id])
  code         String        // e.g. 005, 051, 530
  name         String
  subComponents SubComponent[]
}

model SubComponent {
  id          Int       @id @default(autoincrement())
  componentId Int
  component   Component @relation(fields: [componentId], references: [id])
  code        String    // e.g. 005.0A, 005.0B, 530.0B
  name        String
  accounts    Account[]
}

model Account {
  id             Int          @id @default(autoincrement())
  subComponentId Int
  subComponent   SubComponent @relation(fields: [subComponentId], references: [id])
  code           String       // e.g. 521213, 524113, 524114
  name           String
  items          Item[]
}

model Item {
  id                 Int              @id @default(autoincrement())
  accountId          Int
  account            Account          @relation(fields: [accountId], references: [id])
  code               String           // e.g. 001366, 001211
  name               String
  verificationStatus VerificationStatus @default(PENDING)
  rejectionNote      String?
  documents          Document[]
}

enum VerificationStatus { PENDING APPROVED REJECTED }

model Document {
  id               Int      @id @default(autoincrement())
  itemId           Int
  item             Item     @relation(fields: [itemId], references: [id])
  fileName         String
  filePath         String
  fileSize         Int
  fileType         String
  uploadedByUserId Int
  uploadedBy       User     @relation(fields: [uploadedByUserId], references: [id])
  label            String?  // e.g. "BAPP Honor", "Daftar Penerima"
  createdAt        DateTime @default(now())
}
```

---

## UI/UX Design Decisions

- **Design System**: Tailwind CSS + shadcn/ui components
- **Color Palette**: Biru BPS (`#003087`) sebagai primary, aksen emas/amber untuk highlight
- **Dark Mode**: Opsional, default light mode
- **Treeview**: Collapsible sidebar dengan ikon per level, badge status di level item
- **Responsive**: Mobile-friendly (sidbar collapse ke hamburger menu)
- **Bahasa**: Full Bahasa Indonesia sesuai terminologi BPS

---

## Verification Plan

### Automated
```bash
npx prisma db push       # validasi schema
npx prisma db seed       # validasi seeder
npm run build            # TypeScript compile check
```

### Manual
1. Login sebagai setiap role → verifikasi akses menu
2. Upload 3+ file PDF pada item 001366 → verifikasi tersimpan di `/uploads/`
3. Preview PDF inline tanpa download
4. Bendahara setujui → badge berubah hijau (APPROVED)
5. Bendahara tolak + isi catatan → badge merah (REJECTED), catatan tampil
6. Supervisor tambah item baru → langsung muncul di treeview

---

## Execution Order

| Step | Task | Estimasi |
|------|------|----------|
| 1 | `npx create-next-app` + konfigurasi | 5 mnt |
| 2 | Prisma schema + `db push` | 10 mnt |
| 3 | Full seeder POK + users | 20 mnt |
| 4 | NextAuth login + RBAC middleware | 15 mnt |
| 5 | Sidebar treeview dinamis | 20 mnt |
| 6 | Item Detail + multi-file upload API | 25 mnt |
| 7 | PDF previewer + Bendahara approval | 20 mnt |
| 8 | Master data CRUD pages | 20 mnt |
| 9 | User management (Admin) | 10 mnt |
| 10 | Polish UI + testing | 15 mnt |
