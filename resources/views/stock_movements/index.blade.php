@extends('layouts.app')

@section('title', 'Riwayat Mutasi Stok')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Riwayat Mutasi & Pergerakan Stok</h2>
        <p class="text-muted mb-0">Catatan transaksi stok masuk dan keluar (Business Rule P4: Terintegrasi via DB Transaction).</p>
    </div>
    <a href="{{ route('stock-movements.create') }}" class="btn btn-warning btn-sm text-dark fw-bold d-flex align-items-center gap-1 shadow-sm">
        <i class="bi bi-plus-circle"></i> Catat Transaksi Baru
    </a>
</div>

<!-- Filter Mutasi Card -->
<div class="card card-custom mb-4 p-3">
    <form method="GET" action="{{ route('stock-movements.index') }}" class="row g-2 align-items-center">
        <!-- Filter Tipe -->
        <div class="col-sm-6 col-md-3">
            <select name="type" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Tipe Transaksi --</option>
                <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Stok Masuk (+)</option>
                <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Stok Keluar (-)</option>
            </select>
        </div>

        <!-- Filter Produk -->
        <div class="col-sm-6 col-md-4">
            <select name="product_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Produk --</option>
                @foreach ($products as $p)
                    <option value="{{ $p->id }}" {{ (string) request('product_id') === (string) $p->id ? 'selected' : '' }}>
                        {{ $p->name }} ({{ $p->sku }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter Supplier -->
        <div class="col-sm-8 col-md-4">
            <select name="supplier_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Supplier --</option>
                @foreach ($suppliers as $s)
                    <option value="{{ $s->id }}" {{ (string) request('supplier_id') === (string) $s->id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Reset Button -->
        <div class="col-sm-4 col-md-1 d-flex gap-1">
            <button type="submit" class="btn btn-outline-primary w-100 p-2" title="Terapkan Filter">
                <i class="bi bi-funnel-fill"></i>
            </button>
            @if (request()->hasAny(['type', 'product_id', 'supplier_id']))
                <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary p-2" title="Reset Filter">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Riwayat Mutasi -->
<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Waktu Transaksi</th>
                        <th>Nama Produk & SKU</th>
                        <th class="text-center">Jenis Transaksi</th>
                        <th class="text-center">Jumlah (Qty)</th>
                        <th>Supplier / Rekanan</th>
                        <th>Keterangan / Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movements as $index => $m)
                        <tr>
                            <td class="text-muted small">{{ $movements->firstItem() + $index }}</td>
                            <td class="small text-muted">
                                <i class="bi bi-clock me-1"></i> {{ $m->created_at->format('d M Y, H:i') }}
                            </td>
                            <td>
                                @if ($m->product)
                                    <a href="{{ route('products.show', $m->product) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $m->product->name }}
                                    </a>
                                    <div class="small mono text-muted">{{ $m->product->sku }}</div>
                                @else
                                    <span class="text-danger small fst-italic">Produk Telah Dihapus</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($m->type === 'in')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1">
                                        <i class="bi bi-arrow-down-left me-1"></i> Stok Masuk
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1">
                                        <i class="bi bi-arrow-up-right me-1"></i> Stok Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="text-center fw-bold fs-6 {{ $m->type === 'in' ? 'text-success' : 'text-danger' }}">
                                {{ $m->type === 'in' ? '+' : '-' }}{{ $m->quantity }}
                            </td>
                            <td class="small">
                                @if ($m->supplier)
                                    <a href="{{ route('suppliers.show', $m->supplier) }}" class="text-decoration-none">
                                        <i class="bi bi-truck text-secondary me-1"></i> {{ $m->supplier->name }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ $m->note ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary"></i>
                                <h5>Belum ada riwayat transaksi mutasi</h5>
                                <p class="mb-3 small">Mulai catat penambahan stok atau pengeluaran barang inventaris.</p>
                                <a href="{{ route('stock-movements.create') }}" class="btn btn-warning btn-sm text-dark fw-bold">
                                    <i class="bi bi-plus-circle me-1"></i> Catat Transaksi Stok Baru
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($movements->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Menampilkan {{ $movements->count() }} dari {{ $movements->total() }} mutasi</span>
            {{ $movements->links() }}
        </div>
    @endif
</div>
@endsection
