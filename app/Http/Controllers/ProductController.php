<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk dengan fitur pencarian, filter kategori, status stok, sorting, dan pagination.
     */
    public function index(Request $request): View
    {
        $query = Product::with('category');

        // Fitur Pencarian (Search by SKU atau Nama Produk)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Fitur Filter Kategori
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        // Fitur Filter Status Stok (Stok Menipis / Rendah)
        if ($request->input('status') === 'low') {
            $query->whereColumn('stock', '<=', 'minimum_stock');
        }

        // Fitur Pengurutan (Sorting)
        $allowedSorts = ['name', 'price', 'stock', 'created_at', 'sku'];
        $sortBy = in_array($request->input('sort_by'), $allowedSorts, true) ? $request->input('sort_by') : 'created_at';
        $sortDirection = strtolower($request->input('order')) === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortBy, $sortDirection);

        // Pagination dengan mempertahankan parameter query string
        $products = $query->paginate(8)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'sortBy', 'sortDirection'));
    }

    /**
     * Menampilkan formulir penambahan produk baru.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Menyimpan produk baru ke database dengan validasi request dan DB Transaction.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $product = Product::create($validated);

            // Jika ada stok awal yang diinputkan saat create, catat di riwayat StockMovement
            if ($product->stock > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'supplier_id' => null,
                    'type' => 'in',
                    'quantity' => $product->stock,
                    'note' => 'Pencatatan saldo awal produk baru',
                ]);
            }
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan ke inventaris!');
    }

    /**
     * Menampilkan rincian detail produk dan riwayat mutasi stoknya.
     */
    public function show(Product $product): View
    {
        $product->load(['category', 'stockMovements.supplier']);
        return view('products.show', compact('product'));
    }

    /**
     * Menampilkan formulir ubah/edit data produk.
     */
    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Memperbarui data produk dengan validasi form request.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()
            ->route('products.show', $product)
            ->with('success', "Data produk '{$product->name}' berhasil diperbarui!");
    }

    /**
     * Menghapus produk dari sistem beserta riwayat mutasi terkait.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $productName = $product->name;
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', "Produk '{$productName}' berhasil dihapus dari inventaris.");
    }
}
