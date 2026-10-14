# Inventory Management System (Laravel)

Pemilik: Nama Mahasiswa (0000000000) · Pemrograman Web
Tugas: Pertemuan 5, Laravel Fundamental. Migrasi Inventory Management System P4 (PHP native) ke Laravel. Framework berubah, business rule P4 tidak boleh hilang.

## Stack
- Laravel (versi 12.x / stabil terbaru), Composer, Artisan
- Database: SQLite (default out-of-the-box: `database/database.sqlite`) & kompatibel MySQL (`inventory_db`)
- Autentikasi: Laravel Breeze (Blade) / Auth bawaan siap pakai
- Tampilan: Blade + Bootstrap 5.3 via CDN & Bootstrap Icons
- Folder project: `inventory-laravel`

## Aturan arsitektur
- Alur: Request → Route → Controller → Model → Database → Response.
- Jangan menaruh query atau logika di route closure. Jangan menaruh business logic kompleks di Blade.
- Pakai resource controller, named route, implicit route model binding.
- Validasi di controller (atau Form Request), tampilkan pesan error di Blade, setiap form memakai `@csrf`.
- Semua model mengisi `$fillable`. Pakai eager loading `with()` untuk relasi.
- `{{ }}` untuk output (escaping). Jangan `{!! !!}` untuk input pengguna.
- Credential hanya di `.env`. Jangan pernah commit `.env`; sediakan `.env.example`.

## Data
### Category (tabel `categories`)
- name (string, unique)
- description (text, nullable)
- timestamps

### Product (tabel `products`)
- category_id (foreignId -> categories, restrictOnDelete)
- sku (string, unique)
- name (string, max 150)
- price (decimal 12,2)
- stock (integer, default 0)
- minimum_stock (integer, default 0)
- timestamps

### Supplier (tabel `suppliers`)
- name (string)
- phone (string, nullable)
- email (string, nullable)
- address (text, nullable)
- timestamps

### StockMovement (tabel `stock_movements`)
- product_id (foreignId -> products)
- supplier_id (foreignId -> suppliers, nullable)
- type (enum: in, out)
- quantity (integer)
- note (string, nullable)
- timestamps

Relasi: Category hasMany Product; Product belongsTo Category; Product hasMany StockMovement; StockMovement belongsTo Product; Supplier hasMany StockMovement; StockMovement belongsTo Supplier (nullable).

## Business rule (wajib dipertahankan)
- Stok tidak boleh bernilai negatif.
- Stok keluar tidak boleh melebihi stok yang tersedia.
- Setiap transaksi stok (masuk/keluar) tercatat di StockMovement dan mengubah stok produk dalam satu DB transaction.
- Produk dengan stok <= minimum_stock ditandai "stok menipis" di daftar produk.
- SKU produk harus unik.
- Kategori yang masih memiliki produk tidak boleh dihapus.

## Kriteria penerimaan
- [x] Aplikasi berjalan dari project Laravel baru
- [x] Database dibuat dengan migrate:fresh --seed
- [x] Relationship Eloquent Category hasMany Product dan Product belongsTo Category
- [x] CRUD produk memakai resource controller dan Route::resource
- [x] Form punya validasi, @csrf, old() dan pesan error
- [x] Listing produk: search, filter kategori, sorting, pagination (withQueryString)
- [x] Tampilan memakai Blade layout dan minimal satu Blade component
- [x] Business rule stok dari P4 tetap berjalan
- [x] CRUD kategori dan supplier
- [x] Transaksi stok masuk/keluar lewat StockMovement
- [x] Named route dan route grouping
- [x] README instalasi dan penjelasan arsitektur MVC
- [x] Mahasiswa dapat menjelaskan alur request sampai response
