@extends('layouts.app')

@section('title', 'Ubah Supplier: ' . $supplier->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('suppliers.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-1">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Supplier
                </a>
                <h2 class="fw-bold mb-0">Ubah Data Supplier</h2>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-body p-4">
                <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            Nama Supplier / Perusahaan <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $supplier->name) }}" required maxlength="150">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">
                                Nomor Telepon / HP <span class="text-muted">(Opsional)</span>
                            </label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $supplier->phone) }}" maxlength="30">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">
                                Email Resmi <span class="text-muted">(Opsional)</span>
                            </label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $supplier->email) }}" maxlength="100">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label fw-semibold">
                            Alamat Kantor / Gudang <span class="text-muted">(Opsional)</span>
                        </label>
                        <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $supplier->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Perbarui Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
