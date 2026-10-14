@extends('layouts.app')

@section('title', 'Kategori Produk')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Manajemen Kategori Produk</h2>
        <p class="text-muted mb-0">Pengelompokan barang inventaris dan relasi data kategori-produk.</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
        <i class="bi bi-plus-lg"></i> Tambah Kategori
    </a>
</div>

<!-- Search Card -->
<div class="card card-custom mb-4 p-3">
    <form method="GET" action="{{ route('categories.index') }}" class="row g-2 align-items-center">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama kategori atau deskripsi..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                <i class="bi bi-funnel"></i> Cari Kategori
            </button>
            @if (request('search'))
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Kategori -->
<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Produk Terkait</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $index => $cat)
                        <tr>
                            <td class="text-muted small">{{ $categories->firstItem() + $index }}</td>
                            <td>
                                <a href="{{ route('categories.show', $cat) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $cat->name }}
                                </a>
                            </td>
                            <td class="text-muted small text-truncate" style="max-width: 300px;">
                                {{ $cat->description ?? '-' }}
                            </td>
                            <td class="text-center">
                                @if ($cat->products_count > 0)
                                    <a href="{{ route('products.index', ['category_id' => $cat->id]) }}" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-2 py-1">
                                        <i class="bi bi-box-seam me-1"></i> {{ $cat->products_count }} Produk
                                    </a>
                                @else
                                    <span class="badge bg-light text-muted border px-2 py-1">
                                        0 Produk (Kosong)
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('categories.show', $cat) }}" class="btn btn-outline-secondary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('categories.edit', $cat) }}" class="btn btn-outline-primary" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteCatModal{{ $cat->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Modal Hapus Kategori -->
                                <div class="modal fade" id="deleteCatModal{{ $cat->id }}" tabindex="-1" aria-labelledby="catModalLabel{{ $cat->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-start">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold" id="catModalLabel{{ $cat->id }}">Hapus Kategori</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus kategori <strong>"{{ $cat->name }}"</strong>?</p>
                                                @if ($cat->products_count > 0)
                                                    <div class="alert alert-danger small mb-0">
                                                        <i class="bi bi-exclamation-octagon-fill me-1"></i>
                                                        <strong>Peringatan Business Rule P4:</strong> Kategori ini memiliki <strong>{{ $cat->products_count }} produk</strong>. Sistem akan menolak penghapusan sampai seluruh produk dipindahkan ke kategori lain atau dihapus.
                                                    </div>
                                                @else
                                                    <p class="text-muted small mb-0">Kategori ini tidak memiliki produk dan aman untuk dihapus.</p>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" {{ $cat->products_count > 0 ? 'disabled' : '' }}>
                                                        {{ $cat->products_count > 0 ? 'Tidak Dapat Dihapus' : 'Ya, Hapus Kategori' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Belum ada data kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($categories->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Menampilkan {{ $categories->count() }} kategori</span>
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
