<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama sistem inventaris.
     */
    public function index(): View
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $totalStockUnits = (int) Product::sum('stock');
        $totalInventoryValue = (float) Product::selectRaw('SUM(stock * price) as val')->value('val');

        // Produk dengan stok menipis (Business Rule P4: stock <= minimum_stock)
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->take(6)
            ->get();

        $totalLowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();

        // Mutasi stok terbaru
        $recentMovements = StockMovement::with(['product', 'supplier'])
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'totalStockUnits',
            'totalInventoryValue',
            'lowStockProducts',
            'totalLowStockCount',
            'recentMovements'
        ));
    }
}
