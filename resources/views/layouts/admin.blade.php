<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - KSR Garage</title>
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --magenta: #FF00FF;
            --magenta-hover: #e600e6;
            --bg-main: #121212;
            --bg-panel: #0F172A;
            --bg-card: #1E293B;
            --bg-input: #0F172A;
            --border: #334155;
            --text-primary: #F8FAFC;
            --text-secondary: #94A3B8;
        }
        body {
            background-color: var(--bg-main);
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--text-primary);
        }
        .dropdown-item.active, .dropdown-item:active {
            background-color: var(--magenta) !important;
            color: #ffffff !important;
        }
        .topbar {
            top: 0;
            background: var(--bg-panel);
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.4);
            position: sticky;
            z-index: 1000;
        }
        .brand-caption {
            font-size: 0.72rem;
            color: var(--text-secondary);
            line-height: 1;
            margin-top: 3px;
            font-weight: 500;
        }
        .nav-pill-item {
            padding: 0.45rem 0.75rem;
            font-weight: 500;
            font-size: 0.88rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 0.4rem;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }
        .nav-pill-item:hover {
            color: var(--magenta);
            background-color: rgba(255, 0, 255, 0.08);
        }
        .nav-pill-item.active {
            color: var(--magenta);
            background-color: var(--bg-card);
            border-bottom-color: var(--magenta);
            font-weight: 600;
        }
        .icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            background: transparent;
            border: none;
            font-size: 1.1rem;
            transition: all 0.2s ease;
        }
        .icon-btn:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--magenta);
        }
        .btn-magenta {
            background-color: var(--magenta);
            border: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 0.55rem 1.25rem;
            border-radius: 0.7rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            box-shadow: 0 0 20px rgba(255, 0, 255, 0.45);
            transition: all 0.2s ease;
        }
        .btn-magenta:hover {
            background-color: var(--magenta-hover);
            color: #ffffff;
            box-shadow: 0 0 28px rgba(255, 0, 255, 0.65);
        }

        main {
            position: relative;
            z-index: 1;
            background: var(--bg-main);
            color: var(--text-primary);
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: 12px;
        }
        .table {
            color: var(--text-primary);
        }
        .table > :not(caption) > * > * {
            background-color: transparent;
            color: var(--text-primary);
            border-color: var(--border);
        }
        .table thead th {
            color: var(--text-secondary);
            background-color: rgba(255, 255, 255, 0.02);
        }
        .form-control, .form-select {
            background-color: var(--bg-input);
            border: 1px solid var(--border);
            color: var(--text-primary);
        }
        .form-control::placeholder {
            color: var(--text-secondary);
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--bg-input);
            border-color: var(--magenta);
            box-shadow: 0 0 0 .2rem rgba(255, 0, 255, .25);
            color: var(--text-primary);
        }
        .input-group-text {
            background-color: var(--bg-input);
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }
        .text-muted {
            color: var(--text-secondary) !important;
        }
        .border {
            border-color: var(--border) !important;
        }
        .dropdown-menu {
            background: var(--bg-card);
            border: 1px solid var(--border);
        }
        .dropdown-item {
            color: var(--text-primary);
        }
        .dropdown-item:hover {
            background: rgba(255, 0, 255, 0.1);
            color: var(--magenta);
        }

        @media (max-width: 1199.98px) {
            .topbar .nav-pill-item {
                padding: 0.45rem 0.65rem;
                font-size: 0.82rem;
            }
        }

        @media (max-width: 575.98px) {
            .topbar {
                padding: 0.65rem 1rem;
            }
            .topbar img {
                height: 30px !important;
            }
        }
    </style>
    <link href="{{ asset('css/bengkel-theme.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- HEADER TOPBAR -->
    <nav class="topbar d-flex align-items-center justify-content-between gap-2">
        
        <!-- 1. Bagian Kiri (Logo & Menu Desktop) -->
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex flex-column align-items-start">
                <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage" style="height: 36px; display: block;">
                <span class="d-none d-md-inline" style="color: #94A3B8; font-size: 0.7rem; margin-top: 2px;">Admin Dashboard</span>
            </a>

            <!-- Menu Navigasi Desktop (Laptop / Komputer) -->
            <div class="d-none d-lg-flex align-items-center gap-2 ms-4">
                <a href="{{ route('admin.dashboard') }}" class="nav-pill-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.pendaftaran') }}" class="nav-pill-item {{ request()->routeIs('admin.pendaftaran') ? 'active' : '' }}">Pendaftaran</a>
                <a href="{{ route('admin.inventori') }}" class="nav-pill-item {{ request()->routeIs('admin.inventori*') ? 'active' : '' }}">Inventori</a>
                <a href="{{ route('admin.kasir') }}" class="nav-pill-item {{ request()->routeIs('admin.kasir*') ? 'active' : '' }}">Kasir</a>
                <a href="{{ route('admin.laporan') }}" class="nav-pill-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">Laporan</a>
                <a href="{{ route('admin.riwayat') }}" class="nav-pill-item {{ request()->routeIs('admin.riwayat*') ? 'active' : '' }}">Riwayat</a>
            </div>
        </div>

        <!-- 2. Bagian Kanan (Tombol Aksi & Menu Mobile) -->
        <div class="d-flex align-items-center gap-1 gap-md-2">
            
            <!-- Tombol Registrasi Servis Baru -->
            <a href="{{ route('admin.pendaftaran') }}" class="btn-magenta text-nowrap px-2 px-md-3">
                <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline">Servis Baru</span>
            </a>

            <!-- Profil Admin -->
            <div class="d-none d-md-flex align-items-center gap-2 ms-2 me-1">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: var(--bg-card); border: 1px solid var(--border);">
                    <i class="bi bi-person-fill" style="color: var(--text-secondary);"></i>
                </div>
                <span class="small" style="color: #F8FAFC;">Admin Bengkel</span>
            </div>
            
            <!-- Tombol Pengaturan -->
            <a href="{{ route('admin.settings.index') }}" class="icon-btn" title="Pengaturan">
                <i class="bi bi-gear"></i>
            </a>
            
            <!-- Tombol Keluar -->
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button class="icon-btn text-danger" type="submit" title="Keluar">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>

            <!-- Menu Navigasi Mobile Dropdown (HP) -->
            <div class="dropdown d-lg-none position-relative">
                <button class="icon-btn border-0" type="button" onclick="event.stopPropagation(); document.getElementById('menuMobile').classList.toggle('show')">
                    <i class="bi bi-list fs-4"></i>
                </button>
                
                <ul class="dropdown-menu dropdown-menu-end shadow mt-2" id="menuMobile" style="position: absolute; right: 0; top: 100%;">
                    <li><a class="dropdown-item py-2 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                    <li><a class="dropdown-item py-2 {{ request()->routeIs('admin.pendaftaran') ? 'active' : '' }}" href="{{ route('admin.pendaftaran') }}"><i class="bi bi-clipboard-plus me-2"></i> Pendaftaran</a></li>
                    <li><a class="dropdown-item py-2 {{ request()->routeIs('admin.inventori*') ? 'active' : '' }}" href="{{ route('admin.inventori') }}"><i class="bi bi-box-seam me-2"></i> Inventori</a></li>
                    <li><a class="dropdown-item py-2 {{ request()->routeIs('admin.kasir*') ? 'active' : '' }}" href="{{ route('admin.kasir') }}"><i class="bi bi-cash-stack me-2"></i> Kasir</a></li>
                    <li><a class="dropdown-item py-2 {{ request()->routeIs('admin.laporan') ? 'active' : '' }}" href="{{ route('admin.laporan') }}"><i class="bi bi-bar-chart-line me-2"></i> Laporan</a></li>
                    <li><a class="dropdown-item py-2 {{ request()->routeIs('admin.riwayat*') ? 'active' : '' }}" href="{{ route('admin.riwayat') }}"><i class="bi bi-clock-history me-2"></i> Riwayat</a></li>
                </ul>
            </div>

        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="container-fluid px-4 py-4 flex-grow-1">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="text-center py-3 border-top mt-auto" style="background: var(--bg-panel); border-color: var(--border) !important; color: var(--text-secondary); position: relative; z-index: 1;">
        <p class="mb-0 small">&copy; 2026 KSR Garage. Mechanical Precision & Digital Efficiency.</p>
    </footer>

    <!-- Script penutup menu otomatis saat mengeklik area di luar menu -->
    <script>
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('menuMobile');
            if (menu && menu.classList.contains('show')) {
                menu.classList.remove('show');
            }
        });
    </script>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
