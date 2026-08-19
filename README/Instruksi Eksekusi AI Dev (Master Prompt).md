# **🚀 MASTER EXECUTION PROMPT FOR AI CODING AGENT**

**Project Name:** Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang

**Stack:** Laravel 11 (PHP 8.2) · MySQL · Tailwind CSS · Alpine.js / Blade

**Target Goal:** Execute backend logic guards, refactor UI to Search-First Directory, and implement the Interactive Bendahara Checklist according to PRD v2.1.

## **🎯 INSTRUCTIONS FOR AI AGENT**

Read the repository source code directly and execute the following 4 implementation phases sequentially:

### **📍 PHASE 1: BACKEND LOGIC GUARDS & STORAGE GARBAGE COLLECTION**

Implement 3 business logic guards across Controllers and Models:

1. **Guard 1: Lock Documents for APPROVED Items**  
   * **Target:** app/Http/Controllers/DocumentController.php  
   * **Logic:** In store() and destroy(), fetch the parent Item. If $item-\>verification\_status \=== 'APPROVED', abort/redirect back with error toast:"Item ini sudah disetujui oleh Bendahara. Dokumen tidak dapat diubah lagi."  
2. **Guard 2: Minimum 1 Document & Interactive Checklist for Bendahara Approval**  
   * **Target:** app/Http/Controllers/ItemController.php (or VerificationController.php)  
   * **Logic:** When verifying with action \= APPROVED:  
     * Check $item-\>documents()-\>count(). If 0, reject approval with error toast:"Gagal menyetujui: Minimal harus ada 1 dokumen SPJ/BAPP terunggah."  
     * Ensure status update resets rejection\_note \= null.  
   * **Re-review Auto Reset:** When an Operator uploads a new file to an item with status REJECTED, automatically reset $item-\>verification\_status \= 'PENDING' so it reappears in Bendahara's inbox.  
3. **Guard 3: Physical Storage Garbage Collection**  
   * **Target:** app/Models/Document.php  
   * **Logic:** Add Eloquent booted() method with a deleting listener:  
     use Illuminate\\Support\\Facades\\Storage;

     protected static function booted(): void  
     {  
         static::deleting(function (Document $document) {  
             if ($document-\>file\_path && Storage::disk('private')-\>exists($document-\>file\_path)) {  
                 Storage::disk('private')-\>delete($document-\>file\_path);  
             }  
         });  
     }

### **📍 PHASE 2: UI REFACTORING — SEARCH-FIRST DIRECTORY (/items)**

Refactor resources/views/arsip/index.blade.php and items/index.blade.php:

1. **Remove Nested 8-Level Accordions:** Replace the stacked accordion layout with a clean, high-performance **Search-First Explorer**.  
2. **Top Bar Search:** Add a real-time Search Box. Searching by item code (e.g., 001366\) or keyword instantly filters and displays items in a flat data table.  
3. **Cascading Dropdown Filter:** Add sequential dropdowns (Program ➔ Output ➔ SubOutput). Selecting BMA.006 populates the table with items under that SubOutput.  
4. **Item Table Columns:**  
   * Item Code (font-mono)  
   * Item Title  
   * Pagu Anggaran (font-mono text-emerald-700 font-bold)  
   * Uploaded Docs Count (X File)  
   * Verification Status Badge (APPROVED 🟢 / PENDING 🟡 / REJECTED 🔴)  
   * Action: Button "Workspace →" pointing to /items/{id}.

### **📍 PHASE 3: BENDAHARA CHECKLIST & WORKSPACE DETAIL (/items/{id})**

Refactor resources/views/items/show.blade.php:

1. **POK Path Breadcrumb:** Display full breadcrumb (GG.2902 \> BMA \> BMA.006 \> 005 \> 521213 \> 001366).  
2. **Item Header:** Title, Pagu Badge in Rupiah format, and Status Badge.  
3. **Multi-File Upload Dropzone:** Drag-and-drop dropzone supporting .pdf, .jpg, .png up to 15MB with a Label selector (BAPP Honor, Kuitansi, KAK, SK, Daftar Hadir).  
4. **Uploaded Documents Table:** Display uploaded files with Eye Icon (Inline PDF Stream Modal), Download, and Delete buttons.  
5. **Interactive Bendahara Verification Panel:**  
   * Visible for BENDAHARA & ADMIN roles.  
   * Render a **Checklist Box** listing all uploaded documents with checkboxes.  
   * Enable the **"Setujui Pencairan (APPROVED)"** button only when required checklist items are checked.  
   * Include **"Tolak / Minta Revisi (REJECTED)"** button which triggers a modal requiring a rejection\_note text input.  
6. **Rejection Alert Banner:** If item status is REJECTED, display an alert card at the top showing the rejection note left by Bendahara.

### **📍 PHASE 4: DATABASE & SEEDER AUDIT**

1. Ensure DatabaseSeeder.php includes default users:  
   * admin (Role: ADMIN)  
   * supervisor (Role: SUPERVISOR)  
   * operator (Role: OPERATOR)  
   * bendahara (Role: BENDAHARA)  
2. Seed MVP priority tree focus:  
   * Program GG.2902 ➔ Output BMA ➔ SubOutput BMA.006  
   * Items 001366 (Pagu Rp 925.600.000), 001211, 001510  
   * Bounding Account 524114 (Item 001351, 001352\)

## **🧪 VERIFICATION CHECKLIST FOR AI AGENT**

Run and verify the following tests after implementation:

* ![][image1]php artisan test or manual browser test.  
* ![][image1]Try uploading a file to an item with status APPROVED ➔ Verify it is blocked with an error toast.  
* ![][image1]Try approving an item with 0 uploaded documents as Bendahara ➔ Verify approval is blocked.  
* ![][image1]Delete a document ➔ Verify row is removed from MySQL AND physical file is deleted from storage/app/private/.  
* ![][image1]Check /items page ➔ Verify the Search Box works instantly without nested accordions.

[image1]: <data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAmwAAAAVCAYAAAD7J7IFAAABkUlEQVR4Xu3W0ZKCMAwFUPf/f3pf3NnRASwV2qQ55xEBb9Ok+njAjp/3C7BjaK8M/TLgo7IzOWPhN33nTa9dUk+tep5pc9+bAViB3wmAIbaP2+2r66myzhXM36v5CYDanEIApOaHjFWF7e2wwYChnAVldW5952MAQF3+PuzKWJqMmaGLZqckjZ+BXbpB5qJmzg53Mx+00is7ohQmSg6ekm9I8vjMUr1xvlz/l4+/6HlXzzPkZb8vsEIRA68hcLTQ2e7QvN7rbywkbU3SBieImB0UM1Wz5PGHiFKjKDmaJQucLC7UFGVQo+Q4KV3sdIFhBQZvbfYXQjOixFGlG6uskwh020gb1d64dJH73gy0qTaF1dbLBxoCIBsnN7wyE+epWUi2BebIPHuZs79aZyWMVrh3Ci8dnkwBwDIOjvSDjxggRv1jpOCUgptWcMmA0QcycFJBWsYXzgg0Mc1Rmm8E/qQbm3SBoY9WBxJxZDXZK9Pe9cf7Rwc3sgD7W9ZFW3/Ra+DflKaa8qVAGwMK0G3xI/QXsksAcUBhO/oAAAAASUVORK5CYII=>