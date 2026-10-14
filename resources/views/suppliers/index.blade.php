@extends('layouts.app')

@section('title', 'Daftar Supplier')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Manajemen Supplier / Pemasok</h2>
        <p class="text-muted mb-0">Kelola data vendor rekanan penyedia stok barang inventaris.</p>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
        <i class="bi bi-plus-lg"></i> Tambah Supplier Baru
    </a>
</div>

<!-- Search Card -->
<div class="card card-custom mb-4 p-3">
    <form method="GET" action="{{ route('suppliers.index') }}" class="row g-2 align-items-center">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama, email, atau telepon supplier..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                <i class="bi bi-funnel"></i> Cari Supplier
            </button>
            @if (request('search'))
                <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Supplier -->
<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Nama Supplier</th>
                        <th>Kontak Telepon</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th class="text-center">Total Pasokan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suppliers as $index => $sup)
                        <tr>
                            <td class="text-muted small">{{ $suppliers->firstItem() + $index }}</td>
                            <td>
                                <a href="{{ route('suppliers.show', $sup) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $sup->name }}
                                </a>
                            </td>
                            <td class="small">{{ $sup->phone ?? '-' }}</td>
                            <td class="small">
                                @if ($sup->email)
                                    <a href="mailto:{{ $sup->email }}" class="text-decoration-none">
                                        {{ $sup->email }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="small text-muted text-truncate" style="max-width: 250px;">
                                {{ $sup->address ?? '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                    {{ $sup->stock_movements_count }} Transaksi
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('suppliers.show', $sup) }}" class="btn btn-outline-secondary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('suppliers.edit', $sup) }}" class="btn btn-outline-primary" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteSupModal{{ $sup->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Modal Hapus Supplier -->
                                <div class="modal fade" id="deleteSupModal{{ $sup->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-start">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Hapus Supplier</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus data supplier <strong>"{{ $sup->name }}"</strong>?</p>
                                                <p class="text-muted small mb-0">Catatan: Riwayat mutasi stok terkait akan tetap tersimpan namun status supplier menjadi tidak terhubung.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('suppliers.destroy', $sup) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Ya, Hapus Supplier</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Belum ada data supplier.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($suppliers->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Menampilkan {{ $suppliers->count() }} supplier</span>
            {{ $suppliers->links() }}
        </div>
    @endif
</div>
@endsection
