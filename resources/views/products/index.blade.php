@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Daftar Produk Inventaris</h2>
        <p class="text-muted mb-0">Kelola katalog barang, pantau ketersediaan stok fisik, dan batas minimum.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('stock-movements.create') }}" class="btn btn-warning btn-sm text-dark fw-bold d-flex align-items-center gap-1 shadow-sm">
            <i class="bi bi-arrow-down-up"></i> Mutasi Stok
        </a>
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-plus-lg"></i> Tambah Produk Baru
        </a>
    </div>
</div>

<!-- Filter, Pencarian & Pengurutan Card -->
<div class="card card-custom mb-4 p-3">
    <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-center">
        <!-- Input Search -->
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama produk atau SKU..." value="{{ request('search') }}">
            </div>
        </div>

        <!-- Filter Kategori -->
        <div class="col-sm-6 col-md-3">
            <select name="category_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Kategori --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (string) request('category_id') === (string) $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter Status Stok -->
        <div class="col-sm-6 col-md-2">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Stok</option>
                <option value="low" {{ request('status') === 'low' ? 'selected' : '' }}>⚠️ Stok Menipis</option>
            </select>
        </div>

        <!-- Sorting -->
        <div class="col-sm-8 col-md-2">
            <select name="sort_by" class="form-select" onchange="this.form.submit()">
                <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Terbaru</option>
                <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Nama (A-Z)</option>
                <option value="price" {{ request('sort_by') === 'price' ? 'selected' : '' }}>Harga</option>
                <option value="stock" {{ request('sort_by') === 'stock' ? 'selected' : '' }}>Jumlah Stok</option>
            </select>
        </div>

        <!-- Tombol Aksi / Reset -->
        <div class="col-sm-4 col-md-1 d-flex gap-1">
            <button type="submit" class="btn btn-outline-primary w-100 p-2" title="Terapkan Filter">
                <i class="bi bi-funnel-fill"></i>
            </button>
            @if (request()->hasAny(['search', 'category_id', 'status', 'sort_by', 'order']))
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary p-2" title="Reset Filter">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Daftar Produk -->
<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th class="text-center">Stok & Status</th>
                        <th class="text-center">Min. Stok</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="mono fw-semibold text-primary">
                                <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                                    {{ $product->sku }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">
                                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
                                        {{ $product->name }}
                                    </a>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('categories.show', $product->category) }}" class="badge bg-light text-dark border text-decoration-none">
                                    {{ $product->category->name }}
                                </a>
                            </td>
                            <td class="fw-semibold text-dark">
                                {{ $product->formatted_price }}
                            </td>
                            <td class="text-center">
                                <x-badge-stock :stock="$product->stock" :minimum-stock="$product->minimum_stock" />
                            </td>
                            <td class="text-center text-muted fw-semibold">
                                {{ $product->minimum_stock }}
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('stock-movements.create', ['product_id' => $product->id]) }}" class="btn btn-outline-warning text-dark" title="Mutasi Stok">
                                        <i class="bi bi-arrow-down-up"></i>
                                    </a>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-secondary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Modal Konfirmasi Hapus Produk -->
                                <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $product->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-start">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold" id="modalLabel{{ $product->id }}">Konfirmasi Hapus Produk</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="mb-2">Apakah Anda yakin ingin menghapus produk ini dari sistem?</p>
                                                <div class="p-3 bg-light rounded-3 border">
                                                    <div><strong>SKU:</strong> {{ $product->sku }}</div>
                                                    <div><strong>Nama:</strong> {{ $product->name }}</div>
                                                    <div><strong>Sisa Stok:</strong> {{ $product->stock }} unit</div>
                                                </div>
                                                <p class="text-danger small mt-2 mb-0">
                                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Perhatian: Riwayat transaksi mutasi yang berhubungan dengan produk ini juga akan terhapus.
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Ya, Hapus Produk</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                <h5>Tidak ada data produk ditemukan</h5>
                                <p class="mb-3 small">Coba ubah kata kunci pencarian atau bersihkan filter.</p>
                                <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($products->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="small text-muted">
                Menampilkan {{ $products->firstItem() ?? 0 }} sampai {{ $products->lastItem() ?? 0 }} dari total {{ $products->total() }} produk
            </div>
            <div>
                {{ $products->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
