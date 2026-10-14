@extends('layouts.app')

@section('title', 'Supplier: ' . $supplier->name)

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <a href="{{ route('suppliers.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-1">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Supplier
        </a>
        <h2 class="fw-bold mb-0">{{ $supplier->name }}</h2>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('stock-movements.create', ['supplier_id' => $supplier->id, 'type' => 'in']) }}" class="btn btn-warning btn-sm text-dark fw-bold">
            <i class="bi bi-box-arrow-in-down"></i> Catat Pasokan Stok
        </a>
        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil"></i> Ubah Data
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card card-custom h-100">
            <div class="card-header card-header-clean">
                <h5 class="fw-bold mb-0">Kontak & Alamat</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <span class="text-muted small d-block">Nomor Telepon:</span>
                    <div class="fw-semibold text-dark mt-1">
                        <i class="bi bi-telephone text-primary me-1"></i> {{ $supplier->phone ?? 'Tidak ada' }}
                    </div>
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block">Email Resmi:</span>
                    <div class="fw-semibold text-dark mt-1">
                        <i class="bi bi-envelope text-primary me-1"></i> {{ $supplier->email ?? 'Tidak ada' }}
                    </div>
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block">Alamat:</span>
                    <div class="text-dark mt-1 small">
                        <i class="bi bi-geo-alt text-primary me-1"></i> {{ $supplier->address ?? 'Tidak ada catatan alamat' }}
                    </div>
                </div>

                <div class="pt-3 border-top">
                    <span class="text-muted small d-block">Total Riwayat Pengadaan:</span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-6 mt-1">
                        {{ $supplier->stockMovements->count() }} Transaksi
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card card-custom h-100">
            <div class="card-header card-header-clean">
                <h5 class="fw-bold mb-0">Riwayat Pengadaan dari Supplier Ini</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th class="text-center">Kuantitas</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($supplier->stockMovements as $sm)
                                <tr>
                                    <td class="small text-muted">{{ $sm->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <a href="{{ route('products.show', $sm->product) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $sm->product->name }}
                                        </a>
                                        <div class="small text-muted mono">{{ $sm->product->sku }}</div>
                                    </td>
                                    <td class="text-center fw-bold text-success">
                                        +{{ $sm->quantity }} unit
                                    </td>
                                    <td class="small text-muted">{{ $sm->note ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        Belum ada riwayat pengadaan dari supplier ini.
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
