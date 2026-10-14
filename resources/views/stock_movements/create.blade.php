@extends('layouts.app')

@section('title', 'Catat Transaksi Mutasi Stok')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('stock-movements.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-1">
                    <i class="bi bi-arrow-left"></i> Kembali ke Riwayat Mutasi
                </a>
                <h2 class="fw-bold mb-0">Catat Transaksi Pergerakan Stok</h2>
                <p class="text-muted small mb-0">Pencatatan barang masuk (restock) atau pengeluaran barang dari gudang.</p>
            </div>
            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2">
                <i class="bi bi-arrow-down-up me-1"></i> Mutasi Stok
            </span>
        </div>

        <!-- Callout Business Rule P4 -->
        <div class="alert alert-info border-start border-4 border-info shadow-sm rounded-3 mb-4">
            <h6 class="fw-bold text-dark mb-1">
                <i class="bi bi-info-circle-fill text-info me-1"></i> Business Rules P4 yang Aktif:
            </h6>
            <ul class="mb-0 small ps-3">
                <li>Stok produk tidak boleh bernilai negatif (<code>stock &ge; 0</code>).</li>
                <li>Stok keluar tidak boleh melebihi stok fisik yang tersedia di sistem.</li>
                <li>Setiap transaksi dicatat ke tabel <code>stock_movements</code> dan memperbarui tabel <code>products</code> secara atomik via <strong>DB Transaction</strong>.</li>
            </ul>
        </div>

        <div class="card card-custom">
            <div class="card-body p-4">
                <form action="{{ route('stock-movements.store') }}" method="POST" id="formMovement">
                    @csrf

                    <!-- Pilih Produk -->
                    <div class="mb-3">
                        <label for="product_id" class="form-label fw-semibold">
                            Pilih Produk <span class="text-danger">*</span>
                        </label>
                        <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required onchange="updateStockInfo()">
                            <option value="">-- Pilih Produk yang Mengalami Perubahan Stok --</option>
                            @foreach ($products as $p)
                                <option value="{{ $p->id }}"
                                    data-stock="{{ $p->stock }}"
                                    data-name="{{ $p->name }}"
                                    data-sku="{{ $p->sku }}"
                                    {{ old('product_id', $selectedProductId) == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} (SKU: {{ $p->sku }}) &mdash; Stok Saat Ini: {{ $p->stock }} unit
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <!-- Info Stok Badge Dinamis -->
                        <div id="stockBadgeContainer" class="mt-2 p-2 rounded bg-light border d-none">
                            <span class="small text-muted">Kondisi Stok Produk Terpilih:</span>
                            <span id="stockBadgeValue" class="fw-bold text-primary"></span>
                        </div>
                    </div>

                    <!-- Tipe Transaksi (Masuk / Keluar) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold d-block">
                            Jenis Transaksi Stok <span class="text-danger">*</span>
                        </label>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <input type="radio" class="btn-check" name="type" id="type_in" value="in" {{ old('type', $defaultType) === 'in' ? 'checked' : '' }} onchange="handleTypeChange()">
                                <label class="btn btn-outline-success w-100 p-3 text-start d-flex align-items-center gap-2" for="type_in">
                                    <i class="bi bi-box-arrow-in-down fs-4"></i>
                                    <div>
                                        <div class="fw-bold">Stok Masuk (+)</div>
                                        <div class="small text-muted">Pengadaan dari supplier, restock barang</div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <input type="radio" class="btn-check" name="type" id="type_out" value="out" {{ old('type', $defaultType) === 'out' ? 'checked' : '' }} onchange="handleTypeChange()">
                                <label class="btn btn-outline-danger w-100 p-3 text-start d-flex align-items-center gap-2" for="type_out">
                                    <i class="bi bi-box-arrow-up-right fs-4"></i>
                                    <div>
                                        <div class="fw-bold">Stok Keluar (-)</div>
                                        <div class="small text-muted">Penjualan, pemakaian internal divisi</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        @error('type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kuantitas (Jumlah) -->
                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-semibold">
                            Jumlah Unit (Kuantitas) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" min="1" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" required oninput="validateLiveStock()">
                            <span class="input-group-text bg-light text-muted">Unit</span>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="liveWarning" class="alert alert-danger small py-1 px-2 mt-2 d-none">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            <span id="liveWarningText"></span>
                        </div>
                    </div>

                    <!-- Supplier (Opsional, relevan untuk Stok Masuk) -->
                    <div class="mb-3" id="supplierWrapper">
                        <label for="supplier_id" class="form-label fw-semibold">
                            Supplier / Rekanan <span class="text-muted fw-normal">(Opsional)</span>
                        </label>
                        <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                            <option value="">-- Pilih Supplier Rekanan (Jika Pengadaan) --</option>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Catatan / Keterangan -->
                    <div class="mb-4">
                        <label for="note" class="form-label fw-semibold">
                            Catatan Transaksi / Nomor Referensi <span class="text-muted fw-normal">(Opsional)</span>
                        </label>
                        <input type="text" name="note" id="note" class="form-control @error('note') is-invalid @enderror" value="{{ old('note') }}" placeholder="Contoh: No. PO-2026-001, Pemakaian operasional lab, dll." maxlength="255">
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" id="btnSubmit" class="btn btn-primary d-flex align-items-center gap-1">
                            <i class="bi bi-check-circle"></i> Proses Transaksi Stok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function getSelectedStock() {
        const select = document.getElementById('product_id');
        const selected = select.options[select.selectedIndex];
        if (selected && selected.dataset.stock !== undefined) {
            return parseInt(selected.dataset.stock, 10);
        }
        return null;
    }

    function updateStockInfo() {
        const stock = getSelectedStock();
        const container = document.getElementById('stockBadgeContainer');
        const val = document.getElementById('stockBadgeValue');

        if (stock !== null) {
            container.classList.remove('d-none');
            val.textContent = stock + ' unit tersedia di sistem';
        } else {
            container.classList.add('d-none');
        }
        validateLiveStock();
    }

    function handleTypeChange() {
        validateLiveStock();
    }

    function validateLiveStock() {
        const isOut = document.getElementById('type_out').checked;
        const stock = getSelectedStock();
        const qtyInput = document.getElementById('quantity');
        const qty = parseInt(qtyInput.value, 10) || 0;
        const warning = document.getElementById('liveWarning');
        const warningText = document.getElementById('liveWarningText');
        const btnSubmit = document.getElementById('btnSubmit');

        if (isOut && stock !== null && qty > stock) {
            warning.classList.remove('d-none');
            warningText.textContent = `Peringatan: Jumlah keluar (${qty}) melebihi stok yang ada (${stock}). Transaksi akan ditolak sistem.`;
            btnSubmit.classList.add('disabled');
        } else {
            warning.classList.add('d-none');
            btnSubmit.classList.remove('disabled');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateStockInfo();
    });
</script>
@endpush
@endsection
