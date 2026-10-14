@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm rounded-3 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
        <div class="flex-grow-1">
            <strong>Berhasil!</strong> {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm rounded-3 mb-4" role="alert">
        <i class="bi bi-x-circle-fill fs-5 text-danger"></i>
        <div class="flex-grow-1">
            <strong>Perhatian / Error!</strong> {{ session('error') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm rounded-3 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 text-warning"></i>
        <div class="flex-grow-1">
            <strong>Peringatan!</strong> {{ session('warning') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-shield-fill-exclamation fs-5 text-danger"></i>
            <h6 class="mb-0 fw-bold">Terdapat kesalahan validasi formulir:</h6>
        </div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
