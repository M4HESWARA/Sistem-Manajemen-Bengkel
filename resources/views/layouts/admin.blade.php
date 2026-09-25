<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - KSR Garage</title>
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --bengkel-primary: #a31d99;
            --bengkel-accent: #BF24B4;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #212529;
        }
        .dropdown-item.active, .dropdown-item:active {
            background-color: var(--bengkel-accent) !important;
            color: #ffffff !important;
        }
        .topbar {
            top: 0;
            background: #ffffff;
            border-bottom: 3px solid var(--bengkel-accent);
            padding: 0.75rem 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            position: sticky;
            z-index: 1000;
        }
        .brand-caption {
            font-size: 0.72rem;
            color: #6c757d;
            line-height: 1;
            margin-top: 3px;
            font-weight: 500;
        }
        .nav-pill-item {
            padding: 0.45rem 0.9rem;
            font-weight: 700;
            font-size: 0.88rem;
            color: #495057;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        .nav-pill-item:hover, .nav-pill-item.active {
            color: var(--bengkel-accent);
            background-color: #fcf0fc;
        }
        .icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            background: transparent;
            border: none;
            font-size: 1.1rem;
            transition: all 0.2s ease;
        }
        .icon-btn:hover {
            background: #e9ecef;
            color: #212529;
        }
        .btn-bengkel-primary {
            background-color: var(--bengkel-accent);
            border: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 0.55rem 1.25rem;
            border-radius: 0.7rem;
            box-shadow: 0 4px 10px rgba(191, 36, 180, 0.25);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-bengkel-primary:hover {
            background-color: #a31d99;
            color: #ffffff;
        }

        /* Logo Watermark Background */
        .bg-watermark {
            position: fixed;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 550px;
            opacity: 0.08;
            pointer-events: none;
            z-index: 0;
        }
        main {
            position: relative;
            z-index: 1;
        }
    </style>
    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Watermark Background -->
    <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="Watermark" class="bg-watermark">

    <!-- HEADER TOPBAR -->
    <nav class="topbar d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage" style="height: 36px; display: block;">
                <div class="brand-caption">Admin Dashboard</div>
            </a>

            <!-- Menu Navigasi Desktop (Laptop / Komputer) -->
            <div class="d-none d-md-flex align-items-center gap-1">
                <a href="{{ route('admin.dashboard') }}" class="nav-pill-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.pendaftaran') }}" class="nav-pill-item {{ request()->routeIs('admin.pendaftaran') ? 'active' : '' }}">Pendaftaran</a>
                <a href="{{ route('admin.inventori') }}" class="nav-pill-item {{ request()->routeIs('admin.inventori') ? 'active' : '' }}">Inventori</a>
                <a href="{{ route('admin.kasir') }}" class="nav-pill-item {{ request()->routeIs('admin.kasir') ? 'active' : '' }}">Kasir</a>
                <a href="{{ route('admin.laporan') }}" class="nav-pill-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">Laporan</a>
                <a href="{{ route('admin.riwayat') }}" class="nav-pill-item {{ request()->routeIs('admin.riwayat*') ? 'active' : '' }}">Riwayat</a>
            </div>

            <!-- Menu Navigasi Mobile Dropdown (HP) -->
            <div class="dropdown d-md-none">
                <!-- Kita hapus data-bs-toggle, ganti dengan fungsi onclick bawaan -->
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle fw-semibold" type="button" onclick="document.getElementById('menuMobile').classList.toggle('show')">
                    <i class="bi bi-list me-1"></i>
                </button>
                
                <!-- Tambahkan ID menuMobile di sini -->
                <ul class="dropdown-menu shadow" id="menuMobile">
                    <li><a class="dropdown-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a class="dropdown-item {{ request()->routeIs('admin.pendaftaran') ? 'active' : '' }}" href="{{ route('admin.pendaftaran') }}">Pendaftaran</a></li>
                    <li><a class="dropdown-item {{ request()->routeIs('admin.inventori') ? 'active' : '' }}" href="{{ route('admin.inventori') }}">Inventori</a></li>
                    <li><a class="dropdown-item {{ request()->routeIs('admin.kasir') ? 'active' : '' }}" href="{{ route('admin.kasir') }}">Kasir</a></li>
                    <li><a class="dropdown-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}" href="{{ route('admin.laporan') }}">Laporan</a></li>
                    <li><a class="dropdown-item {{ request()->routeIs('admin.riwayat*') ? 'active' : '' }}" href="{{ route('admin.riwayat') }}">Riwayat</a></li>
                </ul>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Tombol Registrasi Servis Baru -->
            <a href="{{ route('admin.pendaftaran') }}" class="btn-bengkel-primary text-nowrap">
                <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline">Servis Baru</span>
            </a>

            <a href="{{ route('admin.settings.index') }}" class="icon-btn" title="{{ auth()->user()->full_name }}"><i class="bi bi-gear"></i></a>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button class="icon-btn text-danger" type="submit" title="Keluar"><i class="bi bi-box-arrow-right"></i></button>
            </form>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="container-fluid px-4 py-4 flex-grow-1">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="text-center py-3 bg-white border-top text-muted small mt-auto" style="position: relative; z-index: 1;">
        <p class="mb-0">&copy; 2026 KSR Garage. Mechanical Precision & Digital Efficiency.</p>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>