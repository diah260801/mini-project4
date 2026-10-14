@extends('layouts.app')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <a href="{{ route('products.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-1">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Produk
        </a>
        <h2 class="fw-bold mb-0">{{ $product->name }}</h2>
        <div class="d-flex align-items-center gap-2 mt-1">
            <span class="badge bg-light text-primary border mono">{{ $product->sku }}</span>
            <span class="text-muted small">&bull;</span>
            <a href="{{ route('categories.show', $product->category) }}" class="text-muted small text-decoration-none">
                <i class="bi bi-tag"></i> {{ $product->category->name }}
            </a>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'in']) }}" class="btn btn-outline-success btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-box-arrow-in-down"></i> Stok Masuk
        </a>
        <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'out']) }}" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-box-arrow-up-right"></i> Stok Keluar
        </a>
        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-pencil"></i> Ubah Data
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Card Info Ringkasan -->
    <div class="col-lg-4">
        <div class="card card-custom h-100">
            <div class="card-header card-header-clean">
                <h5 class="fw-bold mb-0">Informasi Produk</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3 pb-3 border-bottom">
                    <span class="text-muted small d-block">Status Ketersediaan:</span>
                    <div class="mt-1">
                        <x-badge-stock :stock="$product->stock" :minimum-stock="$product->minimum_stock" />
                    </div>
                </div>

                <div class="mb-3 pb-3 border-bottom">
                    <span class="text-muted small d-block">Harga Satuan:</span>
                    <h4 class="fw-bold text-dark mt-1 mb-0">{{ $product->formatted_price }}</h4>
                </div>

                <div class="row g-2 mb-3 pb-3 border-bottom">
                    <div class="col-6">
                        <span class="text-muted small d-block">Sisa Stok Fisik:</span>
                        <h4 class="fw-bold text-dark mt-1 mb-0">{{ $product->stock }} <small class="text-muted fs-6">unit</small></h4>
                    </div>
                    <div class="col-6">
                        <span class="text-muted small d-block">Batas Min. Stok:</span>
                        <h4 class="fw-bold text-muted mt-1 mb-0">{{ $product->minimum_stock }} <small class="text-muted fs-6">unit</small></h4>
                    </div>
                </div>

                <div class="mb-3 pb-3 border-bottom">
                    <span class="text-muted small d-block">Total Estimasi Nilai Inventaris:</span>
                    <h5 class="fw-bold text-success mt-1 mb-0">
                        Rp {{ number_format($product->stock * $product->price, 0, ',', '.') }}
                    </h5>
                </div>

                <div>
                    <span class="text-muted small d-block mb-1">Kategori:</span>
                    <a href="{{ route('categories.show', $product->category) }}" class="badge bg-light text-dark border p-2 text-decoration-none">
                        <i class="bi bi-tags-fill text-primary me-1"></i> {{ $product->category->name }}
                    </a>
                </div>
            </div>
            <div class="card-footer bg-light border-top p-3 small text-muted">
                Terdaftar sejak: {{ $product->created_at->format('d M Y, H:i') }}
            </div>
        </div>
    </div>

    <!-- Riwayat Transaksi Mutasi Stok Produk Ini -->
    <div class="col-lg-8">
        <div class="card card-custom h-100">
            <div class="card-header card-header-clean d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary fs-5"></i>
                    <h5 class="fw-bold mb-0">Riwayat Mutasi Stok Produk</h5>
                </div>
                <a href="{{ route('stock-movements.create', ['product_id' => $product->id]) }}" class="btn btn-warning btn-sm text-dark fw-bold">
                    <i class="bi bi-plus-circle me-1"></i> Catat Mutasi
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal & Waktu</th>
                                <th class="text-center">Tipe</th>
                                <th class="text-center">Qty</th>
                                <th>Supplier / Pihak Terkait</th>
                                <th>Catatan Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($product->stockMovements as $sm)
                                <tr>
                                    <td class="small text-muted">
                                        {{ $sm->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="text-center">
                                        @if ($sm->type === 'in')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="bi bi-arrow-down-left"></i> Masuk
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                <i class="bi bi-arrow-up-right"></i> Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-bold {{ $sm->type === 'in' ? 'text-success' : 'text-danger' }}">
                                        {{ $sm->type === 'in' ? '+' : '-' }}{{ $sm->quantity }}
                                    </td>
                                    <td class="small">
                                        @if ($sm->supplier)
                                            <a href="{{ route('suppliers.show', $sm->supplier) }}" class="text-decoration-none">
                                                <i class="bi bi-truck me-1"></i> {{ $sm->supplier->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        {{ $sm->note ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada catatan mutasi stok untuk produk ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
