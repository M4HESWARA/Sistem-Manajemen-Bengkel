<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Auto Bengkel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bengkel-primary: #7c3aed;
            --bengkel-accent: #ed02fe;
        }
        body {
            background: #f5f6fa;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: .6rem 1.5rem;
        }
        .topbar img { height: 38px; }
        .nav-pill {
            padding: .4rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: .9rem;
            color: #6b7280;
            text-decoration: none;
        }
        .nav-pill:hover { color: var(--bengkel-primary); }
        .nav-pill.active {
            background: linear-gradient(135deg, var(--bengkel-primary), var(--bengkel-accent));
            color: #fff;
        }
        .icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            background: #f3f4f6;
            border: none;
        }
        .icon-btn:hover { background: #e5e7eb; }
        .card { border: none; border-radius: .9rem; box-shadow: 0 2px 10px rgba(0,0,0,.04); }
        .btn-bengkel {
            background: linear-gradient(135deg, var(--bengkel-primary), var(--bengkel-accent));
            border: none;
            color: #fff;
            font-weight: 600;
        }
        .btn-bengkel:hover { opacity: .9; color: #fff; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="topbar d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-4 flex-wrap">
            <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage">

            <div class="d-flex gap-1 flex-wrap">
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-pill {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.pendaftaran') }}"
                   class="nav-pill {{ request()->routeIs('admin.pendaftaran') ? 'active' : '' }}">Pendaftaran</a>
                <a href="{{ route('admin.inventori') }}"
                   class="nav-pill {{ request()->routeIs('admin.inventori') ? 'active' : '' }}">Inventori</a>
                <a href="{{ route('admin.kasir') }}"
                   class="nav-pill {{ request()->routeIs('admin.kasir') ? 'active' : '' }}">Kasir</a>
                <a href="{{ route('admin.laporan') }}"
                   class="nav-pill {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">Laporan</a>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="text-muted small d-none d-md-inline">{{ auth()->user()->full_name }}</span>
            <button class="icon-btn"><i class="bi bi-gear"></i></button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="icon-btn" type="submit"><i class="bi bi-box-arrow-right"></i></button>
            </form>
        </div>
    </nav>

    <main class="container-fluid px-3 px-md-4 py-4">
        @yield('content')
    </main>
</body>
</html>
