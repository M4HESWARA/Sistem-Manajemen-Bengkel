<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Workspace Mekanik') - KSR Garage</title>
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
            --bg-panel: #1E293B;
            --bg-input: #0F172A;
            --border: #334155;
            --text-primary: #F8FAFC;
            --text-secondary: #94A3B8;
        }
        body {
            background: var(--bg-main);
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            color: var(--text-primary);
        }
        .topbar {
            top: 0;
            background: #0F172A;
            border-bottom: 1px solid var(--border);
            padding: 0.85rem 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.4);
            position: sticky;
            z-index: 1000;
        }
        .brand-caption {
            font-size: 0.68rem;
            color: var(--text-secondary);
            line-height: 1;
            margin-top: 4px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .mekanik-badge {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.3rem 0.85rem 0.3rem 0.3rem;
            background: var(--bg-panel);
            border: 1px solid var(--border);
            border-radius: 999px;
        }
        .avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-input);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            font-size: 1rem;
        }
        .badge-status {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.68rem;
            font-weight: 600;
            color: #4ade80;
        }
        .badge-status .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #4ade80;
            box-shadow: 0 0 8px rgba(74, 222, 128, 0.9);
        }
        .logout-btn {
            width: 40px;
            height: 40px;
            border-radius: 0.6rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-panel);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            font-size: 1.05rem;
            transition: all 0.2s ease;
        }
        .logout-btn:hover {
            color: var(--magenta);
            border-color: var(--magenta);
            background: var(--bg-panel);
            box-shadow: 0 0 16px rgba(255, 0, 255, 0.55);
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
        .card { 
            border: 1px solid var(--border); 
            border-radius: 12px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            background: var(--bg-panel);
            color: var(--text-primary);
        }
        .btn-magenta {
            background-color: var(--magenta);
            border: none;
            color: #fff;
            font-weight: 700;
        }
        .btn-magenta:hover {
            background-color: var(--magenta-hover);
            color: #fff;
            box-shadow: 0 0 20px rgba(255, 0, 255, 0.45);
        }
        .glow-magenta {
            box-shadow: 0 0 20px rgba(255, 0, 255, 0.45), 0 0 45px rgba(255, 0, 255, 0.25);
        }
        .status-pill {
            padding: 0.45rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-secondary);
            border: 1px solid var(--border);
            background: transparent;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .status-pill.active {
            color: #fff;
            border-color: var(--magenta);
            background-color: var(--magenta);
            box-shadow: 0 0 15px rgba(255, 0, 255, 0.4);
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
        .table {
            color: var(--text-primary);
        }
        .table > :not(caption) > * > * {
            background-color: transparent;
            color: var(--text-primary);
            border-color: var(--border);
        }
        .text-muted {
            color: var(--text-secondary) !important;
        }
        .border {
            border-color: var(--border) !important;
        }
    </style>
    <link href="{{ asset('css/bengkel-theme.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

<!-- HEADER TOPBAR -->
    <nav class="topbar d-flex align-items-center justify-content-between gap-2">

        <!-- 1. Bagian Kiri (Logo + Label) -->
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('mekanik.dashboard') }}" class="text-decoration-none d-flex flex-column align-items-start" style="line-height: 1;">
                <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage" style="height: 36px; display: block;">
                <span class="brand-caption">Dashboard Mekanik</span>
            </a>
        </div>

        <!-- 2. Bagian Kanan (Profil Mekanik & Keluar) -->
        <div class="d-flex align-items-center gap-2 ms-auto">
            <div class="mekanik-badge">
                <div class="avatar-circle">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="d-flex flex-column" style="line-height: 1.2;">
                    <span class="small fw-semibold" style="color: var(--text-primary);">{{ auth()->user()->full_name ?? auth()->user()->username }}</span>
                    <span class="badge-status"><span class="dot"></span> Mekanik On-Duty</span>
                </div>
            </div>
            <!-- Tombol Keluar -->
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button class="logout-btn" type="submit" title="Keluar">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>

        </div>

    </nav>

    <!-- Konten Utama -->
    <main class="container-fluid py-4 px-4 px-lg-5">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
