<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori produk beserta jumlah produk terkait.
     */
    public function index(Request $request): View
    {
        $query = Category::withCount('products');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $categories = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('categories.index', compact('categories'));
    }

    /**
     * Menampilkan formulir pembuatan kategori baru.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail kategori dan produk yang ada di dalamnya.
     */
    public function show(Category $category): View
    {
        $category->load('products');
        return view('categories.show', compact('category'));
    }

    /**
     * Menampilkan formulir edit kategori.
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Memperbarui informasi kategori.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', "Kategori '{$category->name}' berhasil diperbarui!");
    }

    /**
     * Menghapus kategori.
     * Business Rule P4: Kategori yang masih memiliki produk tidak boleh dihapus!
     */
    public function destroy(Category $category): RedirectResponse
    {
        $count = $category->products()->count();

        if ($count > 0) {
            return redirect()
                ->route('categories.index')
                ->with('error', "Gagal menghapus: Kategori '{$category->name}' masih memiliki {$count} produk terkait. Business rule P4 melarang penghapusan kategori yang masih memiliki produk.");
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', "Kategori '{$categoryName}' berhasil dihapus.");
    }
}
