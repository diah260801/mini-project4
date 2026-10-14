@extends('layouts.app')

@section('title', 'Dashboard Inventaris')

@section('content')
<div class="mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
            <h2 class="fw-bold mb-1">Dashboard Inventaris</h2>
            <p class="text-muted mb-0">Sistem Manajemen Stok & Inventaris &mdash; Migrasi P4 ke Laravel Fundamental</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('stock-movements.create') }}" class="btn btn-warning btn-sm text-dark fw-bold d-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-arrow-down-up"></i> Catat Transaksi Stok
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i> Tambah Produk
            </a>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <x-stat-card
            title="Total Produk"
            :value="$totalProducts"
            icon="bi-box-seam"
            color="primary"
            subtitle="item terdaftar"
            :link="route('products.index')"
            link-text="Kelola Produk"
        />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-stat-card
            title="Total Unit Stok"
            :value="number_format($totalStockUnits)"
            icon="bi-stack"
            color="info"
            subtitle="unit di gudang"
            :link="route('products.index')"
            link-text="Lihat Semua Stok"
        />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-stat-card
            title="Stok Menipis"
            :value="$totalLowStockCount"
            icon="bi-exclamation-triangle"
            color="{{ $totalLowStockCount > 0 ? 'danger' : 'success' }}"
            subtitle="perlu restock"
            :link="route('products.index', ['status' => 'low'])"
            link-text="Filter Stok Menipis"
        />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-stat-card
            title="Estimasi Nilai Stok"
            :value="'Rp ' . number_format($totalInventoryValue, 0, ',', '.')"
            icon="bi-cash-coin"
            color="success"
            subtitle="nilai aset inventaris"
        />
    </div>
</div>

@if ($totalLowStockCount > 0)
    <!-- Banner Peringatan Business Rule P4: Stok Menipis -->
    <div class="alert alert-warning border-start border-4 border-warning shadow-sm rounded-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-octagon-fill text-warning fs-4"></i>
            <div>
                <strong class="text-dark">Peringatan Business Rule P4:</strong>
                <span>Terdapat <strong>{{ $totalLowStockCount }} produk</strong> dengan jumlah stok berada pada atau di bawah batas minimum (<code>stock &le; minimum_stock</code>).</span>
            </div>
        </div>
        <a href="{{ route('products.index', ['status' => 'low']) }}" class="btn btn-warning btn-sm fw-bold">
            Lihat Produk Perlu Restock &rarr;
        </a>
    </div>
@endif

<div class="row g-4 mb-4">
    <!-- Tabel Produk Stok Menipis -->
    <div class="col-lg-6">
        <div class="card card-custom h-100">
            <div class="card-header card-header-clean d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-shield-exclamation text-warning fs-5"></i>
                    <h5 class="mb-0 fw-bold">Perlu Perhatian (Stok Menipis)</h5>
                </div>
                <a href="{{ route('products.index', ['status' => 'low']) }}" class="small text-decoration-none fw-semibold">
                    Semua ({{ $totalLowStockCount }})
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th class="text-center">Sisa Stok</th>
                                <th class="text-center">Min. Stok</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lowStockProducts as $lp)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $lp->name }}</div>
                                        <small class="text-muted mono">{{ $lp->sku }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $lp->category->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        <x-badge-stock :stock="$lp->stock" :minimum-stock="$lp->minimum_stock" />
                                    </td>
                                    <td class="text-center text-muted fw-semibold">
                                        {{ $lp->minimum_stock }}
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('stock-movements.create', ['product_id' => $lp->id, 'type' => 'in']) }}" class="btn btn-outline-success btn-sm py-1 px-2" title="Restock Masuk">
                                            <i class="bi bi-box-arrow-in-down"></i> Restock
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-1"></i>
                                        Semua stok produk saat ini aman di atas batas minimum.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Transaksi Mutasi Terakhir -->
    <div class="col-lg-6">
        <div class="card card-custom h-100">
            <div class="card-header card-header-clean d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary fs-5"></i>
                    <h5 class="mb-0 fw-bold">Mutasi Stok Terbaru</h5>
                </div>
                <a href="{{ route('stock-movements.index') }}" class="small text-decoration-none fw-semibold">
                    Semua Mutasi
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Produk</th>
                                <th class="text-center">Tipe</th>
                                <th class="text-center">Qty</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentMovements as $rm)
                                <tr>
                                    <td class="text-muted small">
                                        {{ $rm->created_at->format('d M H:i') }}
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $rm->product->name ?? 'Produk Dihapus' }}</div>
                                        <small class="text-muted mono">{{ $rm->product->sku ?? '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if ($rm->type === 'in')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="bi bi-arrow-down-left"></i> Masuk
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                <i class="bi bi-arrow-up-right"></i> Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-bold {{ $rm->type === 'in' ? 'text-success' : 'text-danger' }}">
                                        {{ $rm->type === 'in' ? '+' : '-' }}{{ $rm->quantity }}
                                    </td>
                                    <td class="small text-muted text-truncate" style="max-width: 150px;">
                                        {{ $rm->note ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada riwayat transaksi mutasi stok.
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

<!-- Informasi Arsitektur & Business Rules P4 -->
<div class="card card-custom bg-light border-0 p-4">
    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="bi bi-journal-check text-primary fs-4"></i>
        <h5 class="mb-0 fw-bold">Migrasi P4 ke Laravel &mdash; Kepatuhan Business Rule</h5>
    </div>
    <div class="row g-3 small">
        <div class="col-md-6 col-lg-4">
            <div class="p-3 bg-white rounded-3 border h-100">
                <h6 class="fw-bold text-dark mb-1"><i class="bi bi-diagram-3 text-primary me-1"></i> Arsitektur MVC Lengkap</h6>
                <p class="text-muted mb-0">Alur request terstandarisasi: <code>Request &rarr; Route (Named) &rarr; Controller (Resource) &rarr; Model (Eloquent) &rarr; Database (SQLite/MySQL) &rarr; Response (Blade)</code>. Tidak ada query di route closure.</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="p-3 bg-white rounded-3 border h-100">
                <h6 class="fw-bold text-dark mb-1"><i class="bi bi-lock text-success me-1"></i> Transaksi Stok & Integritas</h6>
                <p class="text-muted mb-0">Setiap mutasi stok (masuk/keluar) dibungkus dalam <code>DB::transaction()</code> dan validasi stok keluar tidak boleh melebihi stok yang tersedia (stok tidak boleh negatif).</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="p-3 bg-white rounded-3 border h-100">
                <h6 class="fw-bold text-dark mb-1"><i class="bi bi-trash3 text-danger me-1"></i> Proteksi Relasi Data</h6>
                <p class="text-muted mb-0">Kategori yang masih memiliki produk tidak dapat dihapus (dijaga pada level database via <code>restrictOnDelete</code> dan controller validation).</p>
            </div>
        </div>
    </div>
</div>
@endsection
