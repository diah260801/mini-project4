# Inventory Management System (Laravel)

> **Tugas Praktikum Pertemuan 5 — Pemrograman Web**  
> **Topik:** Laravel Fundamental & Migrasi Inventory Management System P4 (PHP Native) ke Laravel MVC.  
> **Pemilik:** Diah (Mahasiswa) · Program Studi Teknik Informatika / Sistem Informasi

---

## 📌 Ringkasan Proyek

Proyek ini merupakan hasil migrasi aplikasi **Inventory Management System** dari arsitektur PHP Native (Pertemuan 4) ke framework modern **Laravel 12 (MVC)**. Meskipun framework dan arsitektur berubah total menjadi Object-Oriented dan berbasis pola MVC (Model-View-Controller), **seluruh Business Rules dan integritas data dari P4 tetap dipertahankan 100%**.

### 🌟 Fitur Utama
1. **Dashboard Interaktif**: Statistik total produk, total kategori, total supplier, total pergerakan stok, kalkulasi estimasi nilai aset inventaris, serta daftar produk dengan stok menipis dan transaksi mutasi terbaru.
2. **Katalog Produk (CRUD Lengkap)**:
   - Pencarian real-time (berdasarkan Nama Produk atau SKU).
   - Filter Kategori dan Filter Status Stok (Stok Rendah / Menipis).
   - Pengurutan dinamis (Sorting by Nama, Harga, Stok, SKU, Waktu Pembuatan).
   - Pagination bersih dengan mempertahankan query string (`withQueryString()`).
   - Indikator visual stok menipis (`x-badge-stock`) saat `stock <= minimum_stock`.
3. **Pencatatan Mutasi Stok (StockMovement Transaction)**:
   - Transaksi stok masuk (*In*) dan stok keluar (*Out*).
   - Transaksi database atomik (`DB::transaction` dan `lockForUpdate()`).
4. **Manajemen Kategori & Supplier (CRUD Lengkap)**:
   - Manajemen relasi kategori produk dan supplier pemasok barang.
5. **Keamanan & Standar Laravel**:
   - Proteksi CSRF (`@csrf`) pada seluruh form.
   - Server-side validation via Form Requests (`StoreProductRequest`, `UpdateProductRequest`, dll).
   - Retensi input lama (`old()`) dan penanganan pesan error formulir.
   - Eager Loading (`with()`) untuk mencegah masalah performa *N+1 Query*.
   - Auto-escaping output Blade `{{ }}` mencegah XSS.

---

## 🛡️ Business Rules P4 yang Dipertahankan

Sesuai spesifikasi tugas, seluruh aturan bisnis wajib berjalan konsisten:

| # | Business Rule | Lokasi Implementasi & Mekanisme |
|---|---------------|----------------------------------|
| 1 | **Stok tidak boleh bernilai negatif** | `StoreProductRequest` & `UpdateProductRequest` memvalidasi `integer|min:0`. Pada level mutasi stok, divalidasi ketat sebelum pengurangan. |
| 2 | **Stok keluar tidak boleh melebihi stok tersedia** | Di `StockMovementController@store`, sistem mengecek `if ($product->stock < $qty)` dan melempar `ValidationException` jika kuantitas keluar melebihi stok yang ada. |
| 3 | **Pencatatan stok masuk/keluar atomik (DB Transaction)** | Di `StockMovementController@store`, mutasi stok dan pembaruan jumlah stok produk dibungkus dalam `DB::transaction()` dengan `lockForUpdate()` untuk mencegah *race condition*. |
| 4 | **Peringatan stok menipis** | Jika `stock <= minimum_stock`, komponen Blade `<x-badge-stock>` menampilkan badge status `Stok Menipis` berwarna oranye/merah. Tersedia juga filter cepat `status=low`. |
| 5 | **SKU produk harus unik** | Aturan validasi `unique:products,sku` di controller dan index unik di migrasi database (`$table->string('sku')->unique()`). |
| 6 | **Kategori yang masih memiliki produk tidak boleh dihapus** | Di database menggunakan `restrictOnDelete()`. Di `CategoryController@destroy`, dilakukan pengecekan `$category->products()->count() > 0` dan mengembalikan pesan error flash jika masih ada produk terkait. |

---

## 🛠️ Persyaratan Sistem & Tech Stack

- **PHP**: Versi 8.2 atau lebih baru
- **Composer**: Dependency Manager for PHP
- **Framework**: Laravel 12.x
- **Database**: SQLite (default zero-configuration) atau MySQL / MariaDB
- **Frontend Engine**: Laravel Blade Components + Bootstrap 5.3 CDN & Bootstrap Icons
- **Ekstensi PHP**: `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

Ikuti langkah-langkah mudah berikut untuk menjalankan proyek di komputer lokal:

### 1. Clone Repositori & Masuk Direktori
```bash
git clone <url-repo-anda>
cd mini_project4
```

### 2. Pasang Dependensi Composer
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
# Windows PowerShell
copy .env.example .env

# Linux / macOS
cp .env.example .env
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Konfigurasi Database & Jalankan Migrasi + Seeder
Proyek ini secara default menggunakan **SQLite** yang siap pakai tanpa instalasi database server terpisah:
```bash
# Windows PowerShell (membuat database jika belum ada)
if (!(Test-Path database/database.sqlite)) { New-Item -ItemType File database/database.sqlite }

# Jalankan migrasi dan isi dummy data awal
php artisan migrate:fresh --seed
```

> **Data bawaan Seeder mencakup:**
> - 6 Kategori umum (Elektronik, Pakaian, Makanan & Minuman, Buku & Alat Tulis, dll).
> - 5 Supplier resmi terpercaya.
> - 15+ Produk awal dengan berbagai variasi stok (termasuk kondisi stok normal dan stok menipis).
> - Riwayat mutasi stok awal.

### 6. Jalankan Server Pengembangan
```bash
php artisan serve
```
Akses aplikasi melalui browser di: **[http://localhost:8000](http://localhost:8000)** atau **[http://127.0.0.1:8000](http://127.0.0.1:8000)**.

---

## 🧪 Menjalankan Automated Testing

Proyek ini telah dilengkapi dengan Unit & Feature Test yang menguji seluruh endpoint, validasi, dan business rules secara komprehensif:

```bash
php artisan test
```

### Hasil Pengujian (`Tests: 8 passed (30 assertions)`):
- `✓ dashboard page loads successfully`: Dashboard terbuka dengan status HTTP 200 dan elemen statistik.
- `✓ product listing and search`: Fitur pencarian produk dan filter stok menipis berjalan akurat.
- `✓ product validation rules`: Form menolak SKU duplikat, harga negatif, dan stok negatif.
- `✓ cannot record stock out exceeding available stock`: Sistem menolak mutasi keluar yang melebihi saldo stok barang.
- `✓ stock movement updates product stock in db transaction`: Transaksi stok masuk dan keluar mengubah saldo barang secara atomik.
- `✓ category with products cannot be deleted`: Proteksi penghapusan kategori yang masih memiliki produk terkait.

---

## 🗺️ Dokumentasi Rute (`php artisan route:list`)

Seluruh endpoint disusun rapi menggunakan **Resource Controller** dan **Named Routes**:

| HTTP Method | URL / Path | Route Name | Controller Action | Keterangan |
|-------------|------------|------------|-------------------|------------|
| `GET` | `/` | `dashboard` | `DashboardController@index` | Dashboard ringkasan inventaris |
| `GET` | `/inventory/products` | `products.index` | `ProductController@index` | Daftar produk (search/filter/sort) |
| `GET` | `/inventory/products/create` | `products.create` | `ProductController@create` | Form tambah produk |
| `POST` | `/inventory/products` | `products.store` | `ProductController@store` | Simpan produk baru |
| `GET` | `/inventory/products/{product}` | `products.show` | `ProductController@show` | Rincian detail produk & riwayat stok |
| `GET` | `/inventory/products/{product}/edit` | `products.edit` | `ProductController@edit` | Form edit produk |
| `PUT/PATCH` | `/inventory/products/{product}` | `products.update` | `ProductController@update` | Update data produk |
| `DELETE` | `/inventory/products/{product}` | `products.destroy` | `ProductController@destroy` | Hapus produk |
| `GET` | `/inventory/categories` | `categories.index` | `CategoryController@index` | Daftar kategori |
| `POST` | `/inventory/categories` | `categories.store` | `CategoryController@store` | Simpan kategori |
| `GET` | `/inventory/categories/create` | `categories.create` | `CategoryController@create` | Form tambah kategori |
| `GET` | `/inventory/categories/{category}` | `categories.show` | `CategoryController@show` | Detail kategori & produk terkait |
| `PUT/PATCH` | `/inventory/categories/{category}` | `categories.update` | `CategoryController@update` | Update kategori |
| `DELETE` | `/inventory/categories/{category}` | `categories.destroy` | `CategoryController@destroy` | Hapus kategori (terproteksi) |
| `GET` | `/inventory/suppliers` | `suppliers.index` | `SupplierController@index` | Daftar supplier |
| `POST` | `/inventory/suppliers` | `suppliers.store` | `SupplierController@store` | Simpan supplier |
| `GET` | `/inventory/suppliers/create` | `suppliers.create` | `SupplierController@create` | Form tambah supplier |
| `GET` | `/inventory/suppliers/{supplier}` | `suppliers.show` | `SupplierController@show` | Detail supplier |
| `PUT/PATCH` | `/inventory/suppliers/{supplier}` | `suppliers.update` | `SupplierController@update` | Update supplier |
| `DELETE` | `/inventory/suppliers/{supplier}` | `suppliers.destroy` | `SupplierController@destroy` | Hapus supplier |
| `GET` | `/inventory/stock-movements` | `stock-movements.index` | `StockMovementController@index` | Riwayat transaksi stok |
| `GET` | `/inventory/stock-movements/create` | `stock-movements.create` | `StockMovementController@create` | Form transaksi stok masuk/keluar |
| `POST` | `/inventory/stock-movements` | `stock-movements.store` | `StockMovementController@store` | Simpan transaksi mutasi stok |

---

## 🏛️ Penjelasan Arsitektur MVC & Siklus Request-Response

Aplikasi ini mengadopsi standar arsitektur **Model-View-Controller (MVC)** dengan alur request yang bersih dan terstruktur:

```
[ Browser / Client ]
        │  1. HTTP Request (GET / POST / PUT / DELETE)
        ▼
[ routes/web.php ]  ──▶ Mencocokkan URL & Method ke Named Route
        │  2. Meneruskan ke Controller Method
        ▼
[ Controller ]      ──▶ Mengambil input, validasi via Form Request
        │  3. Memanggil Eloquent ORM & Business Logic
        ▼
[ Model (Eloquent) ] ──▶ Representasi entitas data ($fillable, Relasi)
        │  4. Query SQL melalui PDO Driver
        ▼
[ Database ]        ──▶ Menyimpan / Mengambil data (SQLite / MySQL)
        │  5. Mengembalikan Record / Collection
        ▼
[ Controller ]      ──▶ Membungkus data ke dalam `view('name', $data)`
        │  6. Render template
        ▼
[ Blade View ]      ──▶ Menyusun layout, component, escaping `{{ }}`
        │  7. HTTP Response (HTML)
        ▼
[ Browser / Client ]  ◀ Tampilan selesai dimuat
```

### 1. Model (Data & Relasi)
- Terletak di `app/Models/` (`Product`, `Category`, `Supplier`, `StockMovement`).
- Mengatur relasi database:
  - `Category` `hasMany` `Product`
  - `Product` `belongsTo` `Category`
  - `Product` `hasMany` `StockMovement`
  - `Supplier` `hasMany` `StockMovement`
  - `StockMovement` `belongsTo` `Product` & `belongsTo` `Supplier` (nullable)

### 2. View (Antarmuka Pengguna)
- Terletak di `resources/views/`.
- Memanfaatkan **Master Layout** `resources/views/layouts/app.blade.php`.
- Memanfaatkan **Blade Components** reusable:
  - `<x-stat-card>`: Kartu ringkasan metrik statistik di dashboard.
  - `<x-badge-stock>`: Indikator status ketersediaan stok produk.
  - `<x-alert>`: Notifikasi flash message (sukses, error, info).
  - `<x-page-header>`: Judul halaman seragam dengan breadcrumbs dan tombol aksi.

### 3. Controller (Logika Aplikasi)
- Terletak di `app/Http/Controllers/`.
- Berperan sebagai jembatan yang menerima HTTP Request, memvalidasi data menggunakan Form Request, memproses transaksi database, dan mengembalikan response Blade View atau Redirect.

---

## 🎬 Panduan Demo Alur Aplikasi (Checklist Pengujian)

Untuk keperluan demonstrasi kepada dosen/evaluator, lakukan alur berikut:

### Skenario 1: Penambahan Produk & Validasi
1. Buka menu **Produk** (`/inventory/products`), klik **Tambah Produk Baru**.
2. **Uji Validasi Form**: Kosongkan form atau masukkan harga negatif `-5000` dan klik simpan. Sistem akan membatalkan submit dan menampilkan pesan error merah di bawah input terkait serta mempertahankan nilai input lama (`old()`).
3. **Simpan Valid**: Masukkan Nama Produk: `"SSD NVMe 1TB"`, SKU: `"SSD-001"`, Kategori: `"Elektronik"`, Harga: `1250000`, Stok: `10`, Min Stok: `3`.
4. Klik **Simpan Produk**. Sistem melakukan redirect kembali ke daftar produk dengan notifikasi hijau: *"Produk berhasil ditambahkan ke inventaris!"*.
5. Buka detail produk, perhatikan bahwa mutasi stok awal (+10 unit) tercatat otomatis di tabel riwayat stok.

### Skenario 2: Transaksi Mutasi Stok & Penegakan Business Rule
1. Buka menu **Transaksi Stok** (`/inventory/stock-movements`), klik **Catat Transaksi Stok**.
2. Pilih produk `"SSD NVMe 1TB"`, pilih Jenis Transaksi: **Barang Keluar (Out)**.
3. Masukkan jumlah `15` (melebihi stok yang ada yaitu 10 unit).
4. Klik **Simpan Transaksi**.
5. Sistem menolak dengan pesan error: *"Stok tidak mencukupi! Stok keluar (15) melebihi stok yang tersedia (10). Business rule P4: stok tidak boleh bernilai negatif."*.
6. Ubah jumlah menjadi `4` dan submit kembali. Sistem sukses memproses pengurangan stok menjadi `6` unit dalam transaksi atomik.

### Skenario 3: Filter Stok Menipis & Proteksi Hapus Kategori
1. Lakukan transaksi keluar kembali sebanyak `4` unit sehingga stok tersisa `2` (di bawah minimum stok 3).
2. Kembali ke halaman `/inventory/products`. Produk `"SSD NVMe 1TB"` otomatis berlabel badge kuning/oranye **"Stok Menipis"**.
3. Centang filter **Stok Menipis** untuk melihat produk-produk yang butuh *restock*.
4. Buka menu **Kategori** (`/inventory/categories`), coba hapus kategori `"Elektronik"`.
5. Sistem menolak penghapusan dengan pesan: *"Gagal menghapus: Kategori Elektronik masih memiliki produk terkait. Business rule P4 melarang penghapusan kategori yang masih memiliki produk."*.

---

## 📂 Struktur Direktori Proyek

```
mini_project4/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Resource Controllers
│   │   └── Requests/             # Form Requests & Validasi
│   └── Models/                   # Eloquent Models & Relationships
├── database/
│   ├── factories/                # Model Factories untuk dummy data
│   ├── migrations/               # Skema tabel database
│   └── seeders/                  # Seeder data uji awal
├── resources/
│   └── views/
│       ├── components/           # Blade Components (badge, alert, stat-card)
│       ├── layouts/              # Master Layout (app.blade.php)
│       ├── products/             # CRUD View Produk
│       ├── categories/           # CRUD View Kategori
│       ├── suppliers/            # CRUD View Supplier
│       ├── stock_movements/      # Transaksi Stok View
│       └── dashboard/            # Tampilan Dashboard
├── routes/
│   └── web.php                   # Definisi Named Routes & Resource
├── tests/
│   └── Feature/                  # Automated Test Business Rules
└── README.md                     # Dokumentasi Resmi Proyek
```

---

## 📄 Lisensi & Hak Cipta
Dibuat untuk memenuhi tugas praktikum mata kuliah **Pemrograman Web**. Di bawah lisensi MIT.
