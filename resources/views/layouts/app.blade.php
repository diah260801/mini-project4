<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Inventaris') - {{ config('app.name', 'inventory-laravel') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans & Figtree -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bs-font-sans-serif: 'Figtree', system-ui, -apple-system, sans-serif;
            --brand-primary: #2563eb;
            --brand-dark: #0f172a;
            --brand-surface: #ffffff;
            --brand-bg: #f8fafc;
            --brand-border: #e2e8f0;
            --brand-accent: #f97316;
        }

        body {
            font-family: var(--bs-font-sans-serif);
            background-color: var(--brand-bg);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-weight: 700;
        }

        .mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Navbar Styling */
        .navbar-custom {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.15);
        }

        .navbar-brand {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .nav-link {
            font-weight: 500;
            font-size: 0.95rem;
            color: #94a3b8 !important;
            transition: all 0.2s ease;
            padding: 0.5rem 0.9rem !important;
            border-radius: 8px;
        }

        .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08);
        }

        .nav-link.active {
            color: #ffffff !important;
            background: rgba(37, 99, 235, 0.35);
            font-weight: 600;
        }

        /* Card Modern Styling */
        .card-custom {
            background: #ffffff;
            border: 1px solid var(--brand-border);
            border-radius: 14px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -2px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-custom:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.03);
        }

        .card-header-clean {
            background-color: transparent;
            border-bottom: 1px solid var(--brand-border);
            padding: 1.1rem 1.4rem;
        }

        /* Button Polish */
        .btn {
            font-weight: 600;
            border-radius: 9px;
            padding: 0.55rem 1.15rem;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-color: #2563eb;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            border-color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
        }

        /* Table Modern Styling */
        .table-custom {
            margin-bottom: 0;
        }

        .table-custom thead th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            border-bottom: 1px solid var(--brand-border);
            padding: 0.85rem 1.1rem;
        }

        .table-custom tbody td {
            padding: 0.95rem 1.1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-custom tbody tr:hover td {
            background-color: #f8fafc;
        }

        /* Badge Glow */
        .badge-pill-soft {
            padding: 0.4em 0.75em;
            font-weight: 600;
            border-radius: 20px;
            font-size: 0.78rem;
        }

        /* Footer */
        footer {
            margin-top: auto;
            border-top: 1px solid var(--brand-border);
            background: #ffffff;
            color: #64748b;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Header Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top py-2">
        <div class="container-fluid px-lg-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 p-2 shadow-sm" style="width: 36px; height: 36px;">
                    <i class="bi bi-boxes"></i>
                </span>
                <span>
                    <span class="text-white">Inventaris</span><span class="text-primary-emphasis fw-light">Hub</span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1" style="font-size: 10px;">Laravel 12</span>
                </span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="bi bi-box-seam me-1"></i> Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                            <i class="bi bi-tags me-1"></i> Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                            <i class="bi bi-truck me-1"></i> Supplier
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}" href="{{ route('stock-movements.index') }}">
                            <i class="bi bi-arrow-left-right me-1"></i> Mutasi Stok
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('stock-movements.create') }}" class="btn btn-warning btn-sm text-dark fw-bold d-flex align-items-center gap-1 shadow-sm">
                        <i class="bi bi-plus-circle"></i> Transaksi Stok
                    </a>
                    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-plus-lg"></i> Tambah Produk
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="py-4 flex-grow-1">
        <div class="container-fluid px-lg-4">
            {{-- Flash Alert Component --}}
            <x-alert />

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-3">
        <div class="container-fluid px-lg-4 text-center text-md-between d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small">
            <div>
                <strong>Pertemuan 5 · Laravel Fundamental</strong> &mdash; Migrasi Inventory Management System P4 ke Laravel
            </div>
            <div>
                Pemilik: <span class="fw-semibold text-dark">Nama Mahasiswa</span> (0000000000) &bull; Pemrograman Web
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
