@extends('layouts.app')

@section('title', 'Ubah Produk: ' . $product->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-1">
                    <i class="bi bi-arrow-left"></i> Kembali ke Detail Produk
                </a>
                <h2 class="fw-bold mb-0">Ubah Data Produk</h2>
                <span class="text-muted small">SKU: <code class="text-primary">{{ $product->sku }}</code></span>
            </div>
            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2">
                <i class="bi bi-pencil-square me-1"></i> Mode Edit
            </span>
        </div>

        <div class="card card-custom">
            <div class="card-body p-4">
                <form action="{{ route('products.update', $product) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <!-- Row 1: Kategori & SKU -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-semibold">
                                Kategori Produk <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="sku" class="form-label fw-semibold">
                                SKU (Stock Keeping Unit) <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="sku" id="sku" class="form-control mono @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">SKU harus unik. Sistem mengecualikan ID produk saat ini.</div>
                        </div>
                    </div>

                    <!-- Row 2: Nama Produk -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            Nama Produk <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required maxlength="150">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Row 3: Harga Produk -->
                    <div class="mb-3">
                        <label for="price" class="form-label fw-semibold">
                            Harga Satuan (Rp) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted fw-bold">Rp</span>
                            <input type="number" step="0.01" min="0" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 4: Stok & Minimum Stok -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="stock" class="form-label fw-semibold">
                                Stok Fisik Saat Ini <span class="text-danger">*</span>
                            </label>
                            <input type="number" min="0" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">
                                <i class="bi bi-info-circle"></i> Untuk penyesuaian berkala, disarankan juga mencatat lewat <strong>Transaksi Mutasi Stok</strong>.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="minimum_stock" class="form-label fw-semibold">
                                Batas Minimum Stok <span class="text-danger">*</span>
                            </label>
                            <input type="number" min="0" name="minimum_stock" id="minimum_stock" class="form-control @error('minimum_stock') is-invalid @enderror" value="{{ old('minimum_stock', $product->minimum_stock) }}" required>
                            @error('minimum_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">
                                Peringatan "Stok Menipis" muncul jika stok &le; nilai ini.
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-1">
                            <i class="bi bi-save"></i> Perbarui Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
