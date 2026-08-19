# Implementation Plan — Sistem Data Digital Arsip Keuangan BPS Subang (v2.1 Execution)

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Execute backend logic guards, refactor UI to a Search-First Directory, and implement the Interactive Bendahara Checklist according to PRD v2.1 for BPS Subang's financial archive system.

**Architecture:** Laravel 11 backend with Eloquent models, Blade templates styled with corporate Tailwind CSS aesthetic, and Alpine.js for dynamic frontend interactions (Search-First Explorer, Interactive Verification Checklist, PDF Modal Stream Viewer).

**Tech Stack:** Laravel 11 (PHP 8.2), MySQL / SQLite, Tailwind CSS, Alpine.js, PHPUnit.

---

## User Review Required

> [!IMPORTANT]
> - **Strict Interactive Bendahara Checklist:** Bendahara MUST physically check off EVERY single uploaded document checklist item before the **Setujui Pencairan (APPROVED)** button activates. Condition: `checkedCount === totalDocuments && totalDocuments > 0`.
> - **Auto Re-review Reset:** When an item has status `REJECTED` and an Operator uploads a new document, the system automatically resets status to `PENDING` and clears the rejection note so it reappears in Bendahara's verification queue.
> - **URL State Preservation:** Cascading dropdown filter changes preserve active search queries (`search` parameter) seamlessly.

---

## Proposed Changes

### Component 1: Backend Logic Guards & Models

#### [MODIFY] [DocumentController.php](file:///d:/Project%20BPS/app/app/Http/Controllers/DocumentController.php)
- Verify and refine Guard 1 (lock uploads & deletes for `APPROVED` items).
- Verify Re-review Auto Reset logic (resets `verification_status` to `PENDING` and `rejection_note` to `null` when a document is uploaded to a `REJECTED` item).

#### [MODIFY] [ItemController.php](file:///d:/Project%20BPS/app/app/Http/Controllers/ItemController.php)
- Verify and refine Guard 2 (check `$item->documents()->count() > 0` before allowing `APPROVED` status).
- Ensure approving an item resets `rejection_note` to `null`.

#### [MODIFY] [Document.php](file:///d:/Project%20BPS/app/app/Models/Document.php)
- Verify Guard 3 Eloquent `booted()` deleting listener for storage garbage collection explicitly calling `Storage::disk('private')->delete($document->file_path)`.

---

### Component 2: Directory Browser View (`/items` / `/arsip`)

#### [MODIFY] [arsip/index.blade.php](file:///d:/Project%20BPS/app/resources/views/arsip/index.blade.php)
- Enhance real-time Search-First Explorer header and cascading filter dropdowns (Program ➔ Output ➔ SubOutput).
- Add JavaScript/Form logic to preserve the `search` query string parameter when resetting lower-level cascading dropdowns upon parent dropdown changes.
- Ensure item table displays: Item Code (`font-mono`), Item Name, Akun/Sub-Output info, Pagu Anggaran (`font-mono text-emerald-800`), Document Count Badge, Verification Status Badge, and "Workspace →" button.

---

### Component 3: Item Workspace & Bendahara Verification View (`/items/{id}`)

#### [MODIFY] [items/show.blade.php](file:///d:/Project%20BPS/app/resources/views/items/show.blade.php)
- Implement Alpine.js state for the **Interactive Bendahara Verification Checklist Box**:
  - List all uploaded documents with checkboxes.
  - Dynamically count checked items vs total documents.
  - Strictly bind `disabled` attribute of **"Setujui Pencairan (APPROVED)"** button to `checkedCount === totalDocuments && totalDocuments > 0` (strictly requires 100% of uploaded documents to be checked).
- Implement Rejection Modal for **"Tolak / Minta Revisi (REJECTED)"** button:
  - Requires `rejection_note` text input before submitting.
- Maintain and enhance:
  - Full 7-level POK Breadcrumb Trail (`GG.2902 > BMA > BMA.006 > 005 > 521213 > 001366`).
  - Multi-file Drag & Drop Upload Zone with Label selector.
  - Uploaded Documents Table with Stream Inline PDF Previewer and Delete actions.
  - Rejection Alert Banner at top when item status is `REJECTED`.

---

### Component 4: Database & Seeder Audit

#### [MODIFY] [DatabaseSeeder.php](file:///d:/Project%20BPS/app/database/seeders/DatabaseSeeder.php)
- Audit default users (`admin`, `supervisor`, `operator`, `bendahara`).
- Confirm seeding of MVP focus tree: Program `GG.2902`, Output `BMA`, SubOutput `BMA.006` (items `001366`, `001211`, `001510`), and Bounding Account `524114` (items `001351`, `001352`).

---

## Detailed Bite-Sized Implementation Plan

### Task 1: Audit & Refine Backend Logic Guards (Phase 1)

**Files:**
- Modify: [app/Http/Controllers/DocumentController.php](file:///d:/Project%20BPS/app/app/Http/Controllers/DocumentController.php)
- Modify: [app/Http/Controllers/ItemController.php](file:///d:/Project%20BPS/app/app/Http/Controllers/ItemController.php)
- Modify: [app/Models/Document.php](file:///d:/Project%20BPS/app/app/Models/Document.php)
- Test: [tests/Feature/BpsSystemTest.php](file:///d:/Project%20BPS/app/tests/Feature/BpsSystemTest.php)

- [ ] **Step 1: Inspect and verify DocumentController.php logic guards**
  Check `store()` and `destroy()` methods in `DocumentController.php` to ensure Guard 1 (`APPROVED` item lock) and Re-review Auto Reset (`REJECTED` -> `PENDING`) return expected error toast and status resets.

- [ ] **Step 2: Inspect and verify ItemController.php logic guard**
  Check `verify()` in `ItemController.php` to ensure Guard 2 (`documents()->count() === 0` check) blocks approval and clears `rejection_note` when approved.

- [ ] **Step 3: Verify Document.php model garbage collection listener**
  Ensure `Document::booted()` listener explicitly calls `Storage::disk('private')->delete($document->file_path)` inside `deleting` event callback.

- [ ] **Step 4: Run automated test suite for backend guards**
  Run `php artisan test --filter BpsSystemTest` and verify all tests pass.

---

### Task 2: Refactor Search-First Directory View (`/items`) (Phase 2)

**Files:**
- Modify: [resources/views/arsip/index.blade.php](file:///d:/Project%20BPS/app/resources/views/arsip/index.blade.php)

- [ ] **Step 1: Update Cascading Filter JavaScript in index.blade.php to preserve search query**
  Add cascading filter change handler that updates `program_id`, resets child options, preserves hidden `<input type="hidden" name="search" value="...">`, and submits the form without dropping search keywords.

- [ ] **Step 2: Ensure table columns and status badges strictly follow PRD v2.1 spec**
  Verify Item Code font (`font-mono`), Pagu styling, File Count badge, Status Badges (`APPROVED`, `PENDING`, `REJECTED`), and Workspace link button.

- [ ] **Step 3: Run automated test suite**
  Run `php artisan test --filter BpsSystemTest` to verify system integrity.

---

### Task 3: Implement Interactive Bendahara Checklist & Verification Panel in Workspace (`/items/{id}`) (Phase 3)

**Files:**
- Modify: [resources/views/items/show.blade.php](file:///d:/Project%20BPS/app/resources/views/items/show.blade.php)

- [ ] **Step 1: Add Alpine.js checklist state to Bendahara Panel**
  In `show.blade.php`, initialize Alpine state:
  ```javascript
  x-data="{
      checkedDocs: {},
      totalDocs: {{ $item->documents->count() }},
      get checkedCount() {
          return Object.values(this.checkedDocs).filter(Boolean).length;
      },
      get canApprove() {
          return this.totalDocs > 0 && this.checkedCount === this.totalDocs;
      },
      showRejectModal: false
  }"
  ```

- [ ] **Step 2: Render document checklist box inside Panel Verifikasi**
  For each uploaded document in `$item->documents`, render a checkbox bound to `checkedDocs['{{ $doc->id }}']` with document name, label, and inline preview action button.
  If `$item->documents` is empty, render notice: "Belum ada dokumen terunggah yang dapat diverifikasi."

- [ ] **Step 3: Bind approval button state strictly to checklist completion**
  Bind `:disabled="!canApprove"` on **Setujui Pencairan (APPROVED)** button so it is ONLY active when `checkedCount === totalDocs && totalDocs > 0`.

- [ ] **Step 4: Refactor Rejection Form into an interactive modal/panel**
  Ensure **Tolak / Minta Revisi (REJECTED)** triggers a clean modal with required textarea input for `rejection_note`.

- [ ] **Step 5: Verify Rejection Alert Banner**
  Ensure banner displays prominently when `$item->verification_status === 'REJECTED'`.

---

### Task 4: Database Seeder Audit & Full Verification (Phase 4)

**Files:**
- Modify: [database/seeders/DatabaseSeeder.php](file:///d:/Project%20BPS/app/database/seeders/DatabaseSeeder.php)
- Test: [tests/Feature/BpsSystemTest.php](file:///d:/Project%20BPS/app/tests/Feature/BpsSystemTest.php)

- [ ] **Step 1: Audit DatabaseSeeder.php for default users and POK hierarchy**
  Verify NIP/Usernames: `admin`, `supervisor`, `operator`, `bendahara`, and items `001366`, `001211`, `001510`, `001351`, `001352`.

- [ ] **Step 2: Execute db:seed command**
  Run `php artisan db:seed` to verify clean execution.

- [ ] **Step 3: Run full automated feature test suite**
  Run `php artisan test --filter BpsSystemTest` to verify all end-to-end user workflows pass.
