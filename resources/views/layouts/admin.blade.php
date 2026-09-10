<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Auto Bengkel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .bg-watermark {
            position: fixed;
            top: 55%; 
            left: 50%;
            transform: translate(-50%, -50%);
            width: 1000px; 
            opacity: 0.08; 
            pointer-events: none;
            z-index: 0;
        }
        :root {
            --bengkel-primary: #ed02fe;
            --bengkel-accent: #ed02fe;
        }
        body {
            background: #f5f6fa;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        .topbar {
            background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
            border-bottom: 3px solid var(--bengkel-accent);
            padding: .8rem 1.5rem;
        }
        .topbar img { height: 42px; }
        .nav-pill {
            padding: .4rem .2rem;
            font-weight: 700;
            font-size: .95rem;
            line-height: 1;
            color: #374151;
            text-decoration: none;
            border-bottom: 2px solid transparent;
        }
        .nav-pill:hover { color: var(--bengkel-primary); }
        .nav-pill.active {
            color: var(--bengkel-accent);
            border-bottom: 2px solid var(--bengkel-accent);
        }
        .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: .5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #374151;
            background: transparent;
            border: none;
            font-size: 1.3rem;
        }
        .icon-btn:hover { background: #f3f4f6; color: var(--bengkel-primary); }
        .brand-caption { font-size: .75rem; color: #9ca3af; line-height: 1; margin-top: 4px; }
        .nav-divider { width: 1px; height: 24px; background: #d1d5db; }
        main { margin-top: .5rem; }
        .card { border: 1px solid #eee; border-radius: .75rem; box-shadow: 0 1px 4px rgba(0,0,0,.03); }
        .btn-bengkel {
            background: var(--bengkel-accent); 
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 0,375rem;
        }
        .btn-bengkel:hover { 
            background-color: #ffffff; 
            color: #ed02fe; 
            opacity: 0.85; 
        }
    </style>
    @stack('styles')
</head>
<body>
    <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="Watermark" class="bg-watermark">
    <nav class="topbar d-flex align-items-start justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-start gap-4 flex-wrap">
            <div>
                <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage" style="display:block;">
                <div class="brand-caption">@yield('nav-caption', 'Admin Dashboard')</div>
            </div>

            <div class="d-flex align-items-center gap-4 flex-wrap" style="padding-top: 6px;">
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

        <div class="d-flex align-items-center gap-3" style="padding-top: 6px;">
            <div class="nav-divider d-none d-md-block"></div>
            <button class="icon-btn" title="{{ auth()->user()->full_name }}"><i class="bi bi-gear"></i></button>
            <form method="POST" action="{{ route('logout') }}" class="m-0 d-flex">
                @csrf
                <button class="icon-btn" type="submit" title="Keluar"><i class="bi bi-box-arrow-right"></i></button>
            </form>
        </div>
    </nav>

    <main class="container-fluid px-3 px-md-4 py-4">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
