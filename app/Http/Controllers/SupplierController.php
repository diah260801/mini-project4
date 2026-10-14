<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    /**
     * Menampilkan daftar supplier / pemasok.
     */
    public function index(Request $request): View
    {
        $query = Supplier::withCount('stockMovements');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Menampilkan formulir pendaftaran supplier baru.
     */
    public function create(): View
    {
        return view('suppliers.create');
    }

    /**
     * Menyimpan supplier baru.
     */
    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        Supplier::create($request->validated());

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil didaftarkan!');
    }

    /**
     * Menampilkan profil supplier dan riwayat transaksi pengadaan.
     */
    public function show(Supplier $supplier): View
    {
        $supplier->load(['stockMovements.product']);
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Menampilkan form edit supplier.
     */
    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Memperbarui informasi data supplier.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($request->validated());

        return redirect()
            ->route('suppliers.index')
            ->with('success', "Data supplier '{$supplier->name}' berhasil diperbarui!");
    }

    /**
     * Menghapus supplier.
     */
    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplierName = $supplier->name;
        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', "Supplier '{$supplierName}' berhasil dihapus.");
    }
}
