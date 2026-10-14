@extends('layouts.app')

@section('title', 'Tambah Kategori Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('categories.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-1">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kategori
                </a>
                <h2 class="fw-bold mb-0">Tambah Kategori Baru</h2>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-body p-4">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Elektronik & Komputer" required maxlength="100">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Nama kategori wajib unik.</div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">
                            Deskripsi Kategori <span class="text-muted">(Opsional)</span>
                        </label>
                        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Penjelasan singkat mengenai kelompok barang dalam kategori ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
