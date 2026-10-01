<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Workspace Mekanik') - KSR Garage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { 
            --bengkel-primary: #a31d99; 
            --bengkel-accent: #BF24B4; 
        }
        body {
            background: #f8f9fc;
            font-family: 'Segoe UI', system-ui, sans-serif;
            color: #2b2b2b;
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
        .card { 
            border: 1px solid #edf2f7; 
            border-radius: 12px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.01);
            background: #ffffff;
        }
        .btn-bengkel {
            background-color: var(--bengkel-accent);
            border: none;
            color: #fff;
            font-weight: 600;
        }
        .btn-bengkel:hover {
            background-color: var(--bengkel-primary);
            color: #fff;
        }
        /* Style pill status pekerjaan */
        .status-pill {
            padding: 0.4rem 0.9rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #6c757d;
            border: 1px solid #dee2e6;
            background: #ffffff;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .status-pill.active {
            color: var(--bengkel-accent);
            border-color: var(--bengkel-accent);
            background-color: #fdf0fc;
        }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- HEADER TOPBAR -->
    <nav class="topbar d-flex align-items-center justify-content-between gap-2">

        <!-- 1. Bagian Kiri (Logo & Menu Desktop) -->
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('mekanik.dashboard') }}" class="text-decoration-none">
                <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage" style="height: 36px; display: block;">
                <div class="brand-caption">Mekanik Dashboard</div>
            </a>

            <!-- Menu Navigasi Desktop (Laptop / Komputer) -->
            <div class="d-none d-md-flex align-items-center gap-1">
                <a href="{{ route('mekanik.dashboard') }}" class="nav-pill-item {{ request()->routeIs('mekanik.dashboard') ? 'active' : '' }}">Dashboard</a>
            </div>
        </div>

        <!-- 2. Bagian Kanan (Tombol Aksi & Menu Mobile) -->
        <div class="d-flex align-items-center gap-1 gap-md-2">

            <!-- Tombol Keluar -->
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button class="icon-btn text-danger" type="submit" title="Keluar">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>

            <!-- Menu Navigasi Mobile Dropdown (HP) -->
            <div class="dropdown d-md-none position-relative">
                <button class="icon-btn text-dark border-0" type="button" onclick="event.stopPropagation(); document.getElementById('menuMobile').classList.toggle('show')">
                    <i class="bi bi-list fs-4"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow mt-2 border-0" id="menuMobile" style="position: absolute; right: 0; top: 100%;">
                    <li><a class="dropdown-item py-2 {{ request()->routeIs('mekanik.dashboard') ? 'active' : '' }}" href="{{ route('mekanik.dashboard') }}">Dashboard</a></li>
                </ul>
            </div>

        </div>
    </nav>

    <!-- Konten Utama -->
    <main class="container-fluid py-4 px-4 px-lg-5">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script penutup menu otomatis saat mengeklik area di luar menu -->
    <script>
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('menuMobile');
            if (menu && menu.classList.contains('show')) {
                menu.classList.remove('show');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>