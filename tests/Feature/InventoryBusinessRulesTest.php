<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryBusinessRulesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test 1: Aplikasi berjalan dan halaman dashboard utama menampilkan status kode 200.
     */
    public function test_dashboard_page_loads_successfully(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Dashboard Inventaris');
    }

    /**
     * Test 2: Listing produk berfungsi beserta search, filter, dan pagination.
     */
    public function test_product_listing_and_search(): void
    {
        $category = Category::create(['name' => 'Elektronik Test']);
        Product::create([
            'category_id' => $category->id,
            'sku' => 'TEST-001',
            'name' => 'Laptop Asus ROG',
            'price' => 20000000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);
        Product::create([
            'category_id' => $category->id,
            'sku' => 'TEST-002',
            'name' => 'Mouse Logitech Silent',
            'price' => 300000,
            'stock' => 1, // Stok menipis
            'minimum_stock' => 3,
        ]);

        // Search test
        $response = $this->get(route('products.index', ['search' => 'Asus']));
        $response->assertStatus(200);
        $response->assertSee('Laptop Asus ROG');
        $response->assertDontSee('Mouse Logitech Silent');

        // Low stock filter test
        $responseLow = $this->get(route('products.index', ['status' => 'low']));
        $responseLow->assertStatus(200);
        $responseLow->assertSee('Mouse Logitech Silent');
        $responseLow->assertDontSee('Laptop Asus ROG');
    }

    /**
     * Test 3: Validasi form produk (SKU unik, tidak boleh harga/stok negatif).
     */
    public function test_product_validation_rules(): void
    {
        $category = Category::create(['name' => 'Kategori Validasi']);
        Product::create([
            'category_id' => $category->id,
            'sku' => 'UNIQUE-SKU',
            'name' => 'Produk Asli',
            'price' => 10000,
            'stock' => 5,
            'minimum_stock' => 2,
        ]);

        // Coba masukkan SKU duplikat dan stok negatif
        $response = $this->post(route('products.store'), [
            'category_id' => $category->id,
            'sku' => 'UNIQUE-SKU', // Duplikat
            'name' => 'Produk Duplikat',
            'price' => -5000,      // Negatif
            'stock' => -10,        // Negatif
            'minimum_stock' => -2, // Negatif
        ]);

        $response->assertSessionHasErrors(['sku', 'price', 'stock', 'minimum_stock']);
    }

    /**
     * Test 4: Business Rule P4: Stok keluar tidak boleh melebihi stok yang tersedia.
     */
    public function test_cannot_record_stock_out_exceeding_available_stock(): void
    {
        $category = Category::create(['name' => 'ATK']);
        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'KRT-001',
            'name' => 'Kertas Sinar Dunia',
            'price' => 45000,
            'stock' => 5, // Hanya ada 5 unit
            'minimum_stock' => 2,
        ]);

        // Coba keluarkan 10 unit (melebihi 5 unit yang ada)
        $response = $this->post(route('stock-movements.store'), [
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 10,
            'note' => 'Coba keluarkan melebihi stok',
        ]);

        $response->assertSessionHasErrors(['quantity']);

        // Pastikan stok tidak berubah di database
        $this->assertEquals(5, $product->fresh()->stock);
        $this->assertEquals(0, StockMovement::count());
    }

    /**
     * Test 5: Transaksi stok masuk dan keluar mengubah stok produk secara atomik dalam DB Transaction.
     */
    public function test_stock_movement_updates_product_stock_in_db_transaction(): void
    {
        $category = Category::create(['name' => 'Hardware']);
        $supplier = Supplier::create(['name' => 'PT Suplai Utama']);
        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'HDW-001',
            'name' => 'SSD Samsung 1TB',
            'price' => 1500000,
            'stock' => 10,
            'minimum_stock' => 3,
        ]);

        // Transaksi 1: Stok Masuk (+15 unit)
        $this->post(route('stock-movements.store'), [
            'product_id' => $product->id,
            'supplier_id' => $supplier->id,
            'type' => 'in',
            'quantity' => 15,
            'note' => 'Pengadaan stok baru',
        ])->assertRedirect(route('stock-movements.index'));

        $this->assertEquals(25, $product->fresh()->stock);

        // Transaksi 2: Stok Keluar (-8 unit)
        $this->post(route('stock-movements.store'), [
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 8,
            'note' => 'Penjualan ke user',
        ])->assertRedirect(route('stock-movements.index'));

        $this->assertEquals(17, $product->fresh()->stock);
        $this->assertEquals(2, StockMovement::count());
    }

    /**
     * Test 6: Business Rule P4: Kategori yang masih memiliki produk tidak boleh dihapus.
     */
    public function test_category_with_products_cannot_be_deleted(): void
    {
        $category = Category::create(['name' => 'Kategori Berisi']);
        Product::create([
            'category_id' => $category->id,
            'sku' => 'PRD-TEST-DEL',
            'name' => 'Barang Terkait',
            'price' => 50000,
            'stock' => 5,
            'minimum_stock' => 1,
        ]);

        // Coba hapus kategori
        $response = $this->delete(route('categories.destroy', $category));

        // Harus dicegah dan dikembalikan dengan pesan error
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);

        // Kategori kosong harus bisa dihapus
        $emptyCategory = Category::create(['name' => 'Kategori Kosong']);
        $responseEmpty = $this->delete(route('categories.destroy', $emptyCategory));
        $responseEmpty->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $emptyCategory->id]);
    }
}
