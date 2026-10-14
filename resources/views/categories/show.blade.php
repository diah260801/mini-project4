@extends('layouts.app')

@section('title', 'Kategori: ' . $category->name)

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <a href="{{ route('categories.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-1">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kategori
        </a>
        <h2 class="fw-bold mb-0">{{ $category->name }}</h2>
        <p class="text-muted mb-0 small">{{ $category->description ?? 'Tidak ada deskripsi' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.create', ['category_id' => $category->id]) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Tambah Produk di Kategori Ini
        </a>
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil"></i> Ubah Kategori
        </a>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header card-header-clean d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Daftar Produk dalam Kategori ({{ $category->products->count() }})</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Min. Stok</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($category->products as $p)
                        <tr>
                            <td class="mono fw-semibold text-primary">
                                <a href="{{ route('products.show', $p) }}" class="text-decoration-none">{{ $p->sku }}</a>
                            </td>
                            <td>
                                <a href="{{ route('products.show', $p) }}" class="fw-bold text-dark text-decoration-none">{{ $p->name }}</a>
                            </td>
                            <td class="fw-semibold">{{ $p->formatted_price }}</td>
                            <td class="text-center">
                                <x-badge-stock :stock="$p->stock" :minimum-stock="$p->minimum_stock" />
                            </td>
                            <td class="text-center text-muted">{{ $p->minimum_stock }}</td>
                            <td class="text-end">
                                <a href="{{ route('products.show', $p) }}" class="btn btn-outline-secondary btn-sm py-1 px-2">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Belum ada produk dalam kategori ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
