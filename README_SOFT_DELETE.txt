╔════════════════════════════════════════════════════════════════════════════╗
║                                                                            ║
║                  SOFT DELETE IMPLEMENTATION - COMPLETE GUIDE                ║
║                                                                            ║
╚════════════════════════════════════════════════════════════════════════════╝

📌 RINGKASAN SINGKAT
====================

Soft Delete adalah fitur untuk "menandai" data sebagai deleted tanpa 
benar-benar menghapusnya dari database. Data tetap ada di DB dengan kolom 
'deleted_at' yang berisi timestamp penghapusan.

KEUNTUNGAN:
- ✅ Data bisa dipulihkan
- ✅ Audit trail tersimpan
- ✅ Relasi foreign key tetap konsisten
- ✅ Tidak perlu khawatir data loss


════════════════════════════════════════════════════════════════════════════
✅ APA YANG SUDAH DIKERJAKAN
════════════════════════════════════════════════════════════════════════════

1. MIGRATIONS (Database Schema)
   ✓ 2026_01_26_000001_add_soft_deletes_to_events_table.php
   ✓ 2026_01_26_000002_add_soft_deletes_to_tikets_table.php
   ✓ 2026_01_26_000003_add_soft_deletes_to_kategoris_table.php
   ✓ 2026_01_26_000004_add_soft_deletes_to_orders_table.php
   
   Fungsi: Menambahkan kolom 'deleted_at' ke 4 tabel


2. MODELS (dengan SoftDeletes Trait)
   ✓ app/Models/Event.php
     use HasFactory, SoftDeletes;
   
   ✓ app/Models/Tiket.php
     use SoftDeletes;
   
   ✓ app/Models/Kategori.php
     use SoftDeletes;
   
   ✓ app/Models/Order.php
     use SoftDeletes;
   
   Fungsi: Enable soft delete functionality di models


3. CONTROLLERS (dengan Soft Delete Logic)
   ✓ KategoriController
     - index(): Query exclude soft-deleted
     - destroy(): Soft delete (set deleted_at)
     - restore(): Restore deleted items
   
   ✓ EventsController
     - index(): Query exclude soft-deleted
     - destroy(): Soft delete
     - restore(): Restore
   
   ✓ TiketController
     - index(): Query exclude soft-deleted
     - destroy(): Soft delete
     - restore(): Restore
   
   ✓ HistoryController
     - index(): Query exclude soft-deleted
     - destroy(): Soft delete (+ restore stok)
     - restore(): Restore


4. ROUTES (untuk Delete & Restore)
   ✓ POST /admin/categories/{id}/restore
   ✓ POST /admin/events/{id}/restore
   ✓ POST /admin/tickets/{id}/restore
   ✓ POST /admin/histories/{id}/restore


5. DOKUMENTASI
   ✓ SOFT_DELETE_EXPLANATION.txt
   ✓ SOFT_DELETE_VIEW_GUIDE.php
   ✓ SOFT_DELETE_IMPLEMENTATION_GUIDE.md
   ✓ SOFT_DELETE_COMMANDS.sh
   ✓ README_SOFT_DELETE.txt (file ini)


════════════════════════════════════════════════════════════════════════════
📋 LANGKAH IMPLEMENTASI (YANG HARUS DILAKUKAN)
════════════════════════════════════════════════════════════════════════════

STEP 1: Jalankan Migrations
───────────────────────────

Command:
  php artisan migrate

Ini akan:
  ✓ Membuat kolom 'deleted_at' di tabel events, tikets, kategoris, orders
  ✓ Default value NULL untuk semua existing records
  ✓ Soft delete siap digunakan

Verifikasi:
  php artisan migrate:status


STEP 2: Update Blade Templates
───────────────────────────────

Edit 4 file view untuk menampilkan deleted data & restore buttons:

A. resources/views/pages-admin/kategori.blade.php
B. resources/views/events/events.blade.php
C. resources/views/eventshow.blade.php (untuk tikets)
D. resources/views/histories/histories.blade.php

PATTERN untuk setiap view:

@extends('layouts.app')

@section('content')
<!-- TAB NAVIGATION -->
<div class="flex border-b">
    <button onclick="showTab('active')">Aktif ({{ count($categories) }})</button>
    <button onclick="showTab('deleted')">Dihapus ({{ count($deletedCategories) }})</button>
</div>

<!-- TAB: AKTIF -->
<div id="active">
    @foreach($categories as $item)
        <div>
            {{ $item->nama }}
            
            <!-- DELETE: Soft Delete -->
            <form method="POST" action="{{ route('admin.categories.destroy', $item->id) }}">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Yakin?')">Hapus</button>
            </form>
        </div>
    @endforeach
</div>

<!-- TAB: DIHAPUS/TRASH -->
<div id="deleted" style="display:none;">
    @foreach($deletedCategories as $item)
        <div style="opacity: 0.5; background-color: #f0f0f0;">
            <s>{{ $item->nama }}</s> (Dihapus: {{ $item->deleted_at }})
            
            <!-- RESTORE -->
            <form method="POST" action="{{ route('admin.categories.restore', $item->id) }}">
                @csrf
                <button type="submit">Pulihkan</button>
            </form>
        </div>
    @endforeach
</div>

<script>
function showTab(tab) {
    document.getElementById('active').style.display = tab === 'active' ? 'block' : 'none';
    document.getElementById('deleted').style.display = tab === 'deleted' ? 'block' : 'none';
}
</script>
@endsection

REFERENSI LENGKAP: Lihat SOFT_DELETE_VIEW_GUIDE.php


STEP 3: Test Functionality
──────────────────────────

A. Via Web UI:
   1. Login ke admin panel
   2. Buka /admin/categories
   3. Klik "Hapus" pada sebuah kategori
   4. Verifikasi:
      - Kategori hilang dari tab "Aktif"
      - Kategori muncul di tab "Dihapus"
      - Timestamp deleted_at terlihat
   5. Klik "Pulihkan"
   6. Verifikasi kategori kembali ke tab "Aktif"

B. Via Tinker (Interactive Shell):
   php artisan tinker
   
   $event = Event::find(1);
   $event->delete();  // Soft delete
   $event;  // Lihat deleted_at
   
   Event::whereNull('deleted_at')->count();  // Hitung aktif
   Event::onlyTrashed()->get();  // Lihat deleted
   
   $event->restore();  // Restore
   $event;  // Lihat deleted_at = NULL

C. Via Database:
   SELECT * FROM events WHERE deleted_at IS NULL;  // Aktif
   SELECT * FROM events WHERE deleted_at IS NOT NULL;  // Deleted


════════════════════════════════════════════════════════════════════════════
🔍 CARA MEMBACA IMPLEMENTASI
════════════════════════════════════════════════════════════════════════════

1. MIGRATION FILES
   
   File: database/migrations/2026_01_26_000001_add_soft_deletes_to_events_table.php
   
   ```php
   Schema::table('events', function (Blueprint $table) {
       $table->softDeletes();  // Tambah kolom deleted_at
   });
   ```
   
   Artian:
   - Menambah kolom 'deleted_at' tipe TIMESTAMP NULLABLE
   - Default value NULL
   - Saat data dihapus: set ke timestamp saat itu


2. MODEL FILES
   
   File: app/Models/Event.php
   
   ```php
   use Illuminate\Database\Eloquent\SoftDeletes;
   
   class Event extends Model {
       use HasFactory, SoftDeletes;
   }
   ```
   
   Artian:
   - Model sekarang supports soft delete
   - Bisa gunakan: $model->delete(), restore(), onlyTrashed(), etc


3. CONTROLLER METHODS
   
   File: app/Http/Controllers/EventsController.php
   
   ```php
   public function index() {
       // Ambil data AKTIF (deleted_at IS NULL)
       $events = Event::whereNull('deleted_at')->with('kategori')->get();
       
       // Ambil data DIHAPUS (deleted_at IS NOT NULL)
       $deletedEvents = Event::onlyTrashed()->with('kategori')->get();
       
       return view('events.events', compact('events', 'deletedEvents'));
   }
   
   public function destroy(string $id) {
       $event = Event::findOrFail($id);
       $event->delete();  // SOFT DELETE: UPDATE events SET deleted_at = NOW()
       return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
   }
   
   public function restore(string $id) {
       $event = Event::onlyTrashed()->findOrFail($id);  // Cari di data dihapus
       $event->restore();  // RESTORE: UPDATE events SET deleted_at = NULL
       return redirect()->route('admin.events.index')->with('success', 'Event berhasil dipulihkan.');
   }
   ```
   
   Penjelasan:
   - index(): Query normal exclude soft-deleted, tambah query untuk deleted items
   - destroy(): Soft delete (bukan permanent delete)
   - restore(): Kembalikan deleted items


4. ROUTES
   
   File: routes/web.php
   
   ```php
   Route::resource('events', EventsController::class);  // Standard CRUD
   Route::post('/events/{id}/restore', [EventsController::class, 'restore'])
       ->name('events.restore');  // Tambah restore route
   ```
   
   Artian:
   - Standard resource routes: GET create, POST store, GET edit, PUT update, DELETE destroy
   - Tambah custom route untuk restore (POST)


5. BLADE TEMPLATES
   
   ```blade
   <!-- DELETE FORM: POST ke destroy() -->
   <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}">
       @csrf
       @method('DELETE')
       <button type="submit" onclick="return confirm('Yakin?')">Hapus</button>
   </form>
   
   <!-- RESTORE FORM: POST ke restore() -->
   <form method="POST" action="{{ route('admin.events.restore', $event->id) }}">
       @csrf
       <button type="submit">Pulihkan</button>
   </form>
   ```


════════════════════════════════════════════════════════════════════════════
📊 VISUAL EXPLANATION: Database State
════════════════════════════════════════════════════════════════════════════

SEBELUM SOFT DELETE:
┌────┬────────────┬────────────────────┐
│ id │ nama       │ deleted_at         │
├────┼────────────┼────────────────────┤
│ 1  │ Kategori A │ NULL               │ ← Aktif
│ 2  │ Kategori B │ NULL               │ ← Aktif
│ 3  │ Kategori C │ NULL               │ ← Aktif
└────┴────────────┴────────────────────┘

Query normal: SELECT * FROM kategoris WHERE deleted_at IS NULL
Result: 3 rows (Kategori A, B, C)


SETELAH DELETE KATEGORI B:
┌────┬────────────┬──────────────────────────────┐
│ id │ nama       │ deleted_at                   │
├────┼────────────┼──────────────────────────────┤
│ 1  │ Kategori A │ NULL                         │ ← Aktif
│ 2  │ Kategori B │ 2026-01-26 14:30:45          │ ← Dihapus (masih ada!)
│ 3  │ Kategori C │ NULL                         │ ← Aktif
└────┴────────────┴──────────────────────────────┘

Query aktif: SELECT * FROM kategoris WHERE deleted_at IS NULL
Result: 2 rows (Kategori A, C)

Query dihapus: SELECT * FROM kategoris WHERE deleted_at IS NOT NULL
Result: 1 row (Kategori B)


SETELAH RESTORE KATEGORI B:
┌────┬────────────┬────────────────────┐
│ id │ nama       │ deleted_at         │
├────┼────────────┼────────────────────┤
│ 1  │ Kategori A │ NULL               │ ← Aktif
│ 2  │ Kategori B │ NULL               │ ← Kembali aktif!
│ 3  │ Kategori C │ NULL               │ ← Aktif
└────┴────────────┴────────────────────┘

Query aktif: SELECT * FROM kategoris WHERE deleted_at IS NULL
Result: 3 rows (Kategori A, B, C)


════════════════════════════════════════════════════════════════════════════
🎯 FITUR YANG SUDAH BUILT-IN DI MODEL
════════════════════════════════════════════════════════════════════════════

Setelah menggunakan SoftDeletes trait, model supports:

┌─────────────────────────┬────────────────────────────────────────────┐
│ Method                  │ Fungsi                                     │
├─────────────────────────┼────────────────────────────────────────────┤
│ $model->delete()        │ Soft delete (set deleted_at = NOW())       │
│ $model->restore()       │ Restore (set deleted_at = NULL)            │
│ $model->forceDelete()   │ Permanent delete (benar-benar hapus)        │
│ Model::find(id)         │ Hanya aktif (exclude soft-deleted)         │
│ Model::findOrFail(id)   │ Hanya aktif (exclude soft-deleted)         │
│ Model::all()            │ Hanya aktif (exclude soft-deleted)         │
│ Model::get()            │ Hanya aktif (exclude soft-deleted)         │
│ Model::withTrashed()    │ Include soft-deleted items                 │
│ Model::onlyTrashed()    │ Hanya soft-deleted items                   │
└─────────────────────────┴────────────────────────────────────────────┘


════════════════════════════════════════════════════════════════════════════
⚙️ OPTIONAL: ADVANCED FEATURES
════════════════════════════════════════════════════════════════════════════

1. TRACK SIAPA YANG DELETE
   
   Tambah migration:
   ```php
   $table->foreignId('deleted_by')->nullable()->constrained('users');
   ```
   
   Update controller:
   ```php
   public function destroy($id) {
       $category = Kategori::findOrFail($id);
       $category->deleted_by = auth()->id();  // Catat siapa yang delete
       $category->delete();
   }
   ```

2. AUTO PERMANENT DELETE (Cron Job)
   
   Buat command:
   ```bash
   php artisan make:command PermanentlyDeleteOldRecords
   ```
   
   Di command:
   ```php
   Event::onlyTrashed()
       ->where('deleted_at', '<', now()->subMonths(3))
       ->forceDelete();
   ```
   
   Schedule di Kernel.php:
   ```php
   $schedule->command('command:permanently-delete-old-records')->monthly();
   ```

3. SOFT DELETE DENGAN CUSTOM TIMESTAMP
   
   Edit migration:
   ```php
   $table->timestamp('deleted_at')->nullable()->useCurrent();
   ```

4. QUERY WITH DELETED DATA
   
   ```php
   // Include deleted items dalam query
   Event::withTrashed()->where('kategori_id', 1)->get();
   
   // Only get soft-deleted items
   Event::onlyTrashed()->where('kategori_id', 1)->get();
   ```


════════════════════════════════════════════════════════════════════════════
❌ TROUBLESHOOTING COMMON ISSUES
════════════════════════════════════════════════════════════════════════════

ERROR: SQLSTATE[42S22]: Column 'deleted_at' not found
SOLUSI: Jalankan migrations
  php artisan migrate

ERROR: Route not defined for restore
SOLUSI: Pastikan routes sudah ditambah di routes/web.php
  Route::post('/categories/{id}/restore', ...)

ERROR: Method restore() not found in Controller
SOLUSI: Pastikan method restore() sudah ditambah di controller

ERROR: Soft deleted data masih muncul di view
SOLUSI: Verifikasi controller query menggunakan whereNull('deleted_at')
  $categories = Kategori::whereNull('deleted_at')->get();

ERROR: Cannot restore data - Model not found
SOLUSI: Pastikan menggunakan onlyTrashed() saat mencari data deleted
  $category = Kategori::onlyTrashed()->findOrFail($id);

ERROR: SoftDeletes trait not recognized
SOLUSI: Verifikasi import di model file
  use Illuminate\Database\Eloquent\SoftDeletes;


════════════════════════════════════════════════════════════════════════════
📚 DOKUMENTASI FILES
════════════════════════════════════════════════════════════════════════════

1. SOFT_DELETE_EXPLANATION.txt
   - Penjelasan mendalam tentang soft delete
   - Diagram flow dan architecture
   - Code examples dengan penjelasan detail
   - Database queries examples
   
2. SOFT_DELETE_VIEW_GUIDE.php
   - Contoh Blade template lengkap
   - Copy-paste untuk setiap view
   - UI dengan tab Aktif & Dihapus
   - Delete & Restore button implementation
   
3. SOFT_DELETE_IMPLEMENTATION_GUIDE.md
   - Step-by-step lengkap
   - Checklist implementation
   - Query pattern reference
   - Troubleshooting guide
   
4. SOFT_DELETE_COMMANDS.sh
   - Quick reference commands
   - Tinker testing examples
   - Database queries
   - Artisan commands
   
5. README_SOFT_DELETE.txt (FILE INI)
   - Overview keseluruhan
   - Ringkasan apa yang sudah dikerjakan
   - Step implementasi yang perlu dilakukan
   - Visual explanations


════════════════════════════════════════════════════════════════════════════
✅ FINAL CHECKLIST
════════════════════════════════════════════════════════════════════════════

- ☐ Run: php artisan migrate
- ☐ Verify: php artisan migrate:status
- ☐ Update: resources/views/pages-admin/kategori.blade.php
- ☐ Update: resources/views/events/events.blade.php
- ☐ Update: resources/views/eventshow.blade.php
- ☐ Update: resources/views/histories/histories.blade.php
- ☐ Test delete kategori → verify di trash tab
- ☐ Test restore kategori → verify di aktif tab
- ☐ Test delete event
- ☐ Test restore event
- ☐ Test delete tiket
- ☐ Test restore tiket
- ☐ Test delete order → verify stok dipulihkan
- ☐ Test restore order
- ☐ Verify deleted_at timestamp di database
- ☐ Verify data masih ada di database (SELECT *)
- ☐ Check UI responsiveness
- ☐ Documentation complete


════════════════════════════════════════════════════════════════════════════
🚀 NEXT STEPS (AFTER BASIC SOFT DELETE WORKS)
════════════════════════════════════════════════════════════════════════════

1. Add force delete button (permanent delete)
2. Add deleted_by column to track who deleted
3. Add deleted_reason column
4. Create trash cleanup cron job (auto permanent delete after X days)
5. Add audit log for all deletions
6. Create restore history view
7. Add bulk restore functionality
8. Add restore with confirmation modal


════════════════════════════════════════════════════════════════════════════
💡 KEY TAKEAWAYS
════════════════════════════════════════════════════════════════════════════

✓ Soft delete TIDAK menghapus data dari database
✓ Data ditandai dengan deleted_at timestamp
✓ Normal queries secara otomatis exclude soft-deleted data
✓ Data bisa dipulihkan kapan saja via restore()
✓ Useful untuk audit trail & compliance
✓ Relasi foreign key tetap konsisten
✓ Data loss risk berkurang drastis

Semua foundational code sudah ready to use!
Tinggal update Blade templates dan run migrations.

════════════════════════════════════════════════════════════════════════════

Dokumentasi selesai! Silakan ikuti STEP 1-3 untuk finalisasi implementasi.
