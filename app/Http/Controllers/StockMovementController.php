<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockMovementRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    /**
     * Menampilkan riwayat transaksi pergerakan stok (masuk & keluar).
     */
    public function index(Request $request): View
    {
        $query = StockMovement::with(['product.category', 'supplier']);

        // Filter tipe transaksi (in / out)
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        // Filter berdasarkan produk tertentu
        if ($productId = $request->input('product_id')) {
            $query->where('product_id', $productId);
        }

        // Filter berdasarkan supplier
        if ($supplierId = $request->input('supplier_id')) {
            $query->where('supplier_id', $supplierId);
        }

        $movements = $query->latest()->paginate(10)->withQueryString();
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('stock_movements.index', compact('movements', 'products', 'suppliers'));
    }

    /**
     * Menampilkan formulir pencatatan transaksi stok baru.
     */
    public function create(Request $request): View
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $selectedProductId = $request->input('product_id');
        $defaultType = $request->input('type', 'in');

        return view('stock_movements.create', compact('products', 'suppliers', 'selectedProductId', 'defaultType'));
    }

    /**
     * Menyimpan transaksi stok dan memperbarui kuantitas produk dalam SATU DB Transaction.
     * Enforces Business Rules:
     * - Stok tidak boleh negatif
     * - Stok keluar tidak boleh melebihi stok tersedia
     * - Database Transaction atomic rollback jika terjadi error
     */
    public function store(StoreStockMovementRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $movement = DB::transaction(function () use ($validated) {
            // Lock baris produk untuk mencegah race condition (pembacaan stok bersamaan)
            $product = Product::where('id', $validated['product_id'])->lockForUpdate()->firstOrFail();

            $qty = (int) $validated['quantity'];
            $type = $validated['type'];

            if ($type === 'out') {
                if ($product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'quantity' => "Stok tidak mencukupi! Stok keluar ({$qty}) melebihi stok yang tersedia ({$product->stock}). Business rule P4: stok tidak boleh bernilai negatif.",
                    ]);
                }
                $product->decrement('stock', $qty);
            } else {
                $product->increment('stock', $qty);
            }

            return StockMovement::create([
                'product_id' => $product->id,
                'supplier_id' => $validated['supplier_id'] ?? null,
                'type' => $type,
                'quantity' => $qty,
                'note' => $validated['note'] ?? null,
            ]);
        });

        $product = Product::find($movement->product_id);
        $typeLabel = $movement->type === 'in' ? 'Masuk' : 'Keluar';

        return redirect()
            ->route('stock-movements.index')
            ->with('success', "Transaksi stok {$typeLabel} sebanyak {$movement->quantity} unit untuk produk '{$product->name}' berhasil dicatat! Sisa stok sekarang: {$product->stock} unit.");
    }
}
