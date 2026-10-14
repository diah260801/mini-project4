<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Inventory Management System (Laravel)
|--------------------------------------------------------------------------
| Arsitektur: Request -> Route -> Controller -> Model -> Database -> Response
| Menggunakan Named Routes, Resource Controllers, dan Implicit Route Model Binding.
*/

// Halaman Dashboard Utama
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Route Grouping Inventaris
Route::prefix('inventory')->group(function () {
    // 1. Resource Controller Produk (Named routes: products.index, products.create, products.store, products.show, products.edit, products.update, products.destroy)
    Route::resource('products', ProductController::class);

    // 2. Resource Controller Kategori (Named routes: categories.index, categories.create, categories.store, categories.show, categories.edit, categories.update, categories.destroy)
    Route::resource('categories', CategoryController::class);

    // 3. Resource Controller Supplier (Named routes: suppliers.index, suppliers.create, suppliers.store, suppliers.show, suppliers.edit, suppliers.update, suppliers.destroy)
    Route::resource('suppliers', SupplierController::class);

    // 4. Transaksi Pergerakan Stok (Named routes: stock-movements.index, stock-movements.create, stock-movements.store)
    Route::resource('stock-movements', StockMovementController::class)->only(['index', 'create', 'store']);
});
