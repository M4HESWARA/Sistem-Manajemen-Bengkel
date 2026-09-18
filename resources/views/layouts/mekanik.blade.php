<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Workspace Mekanik') - Auto Bengkel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --bengkel-primary: #7c3aed; --bengkel-accent: #ed02fe; }
        body {
            background: #f5f6fa;
            font-family: 'Segoe UI', system-ui, sans-serif;
            font-size: 1.05rem;
            padding-bottom: 2rem;
        }
        .topbar {
            background: linear-gradient(135deg, var(--bengkel-primary), var(--bengkel-accent));
            padding: 1rem 1.25rem;
            color: #fff;
        }
        .topbar img { height: 32px; filter: brightness(0) invert(1); }
        .card { border: none; border-radius: 1rem; box-shadow: 0 2px 10px rgba(0,0,0,.05); }

        /* Tombol besar, mudah dipencet dengan jempol - sesuai kebutuhan PRD */
        .btn { min-height: 48px; font-weight: 600; border-radius: .75rem; font-size: 1rem; }
        .btn-lg { min-height: 56px; font-size: 1.1rem; }
        .form-control, .form-select { min-height: 48px; font-size: 1.05rem; }

        .btn-bengkel {
            background: linear-gradient(135deg, var(--bengkel-primary), var(--bengkel-accent));
            border: none;
            color: #fff;
        }
        .btn-bengkel:hover { opacity: .9; color: #fff; }

        .status-badge { font-size: .85rem; padding: .5rem .9rem; border-radius: 2rem; font-weight: 700; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="topbar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage">
            <div>
                <div class="fw-bold" style="line-height:1;">{{ auth()->user()->full_name }}</div>
                <div class="small" style="opacity:.85;">Workspace Mekanik</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-light btn-sm" style="min-height:auto;">
                <i class="bi bi-box-arrow-right"></i> Keluar
            </button>
        </form>
    </nav>

    <main class="container-fluid px-3 py-3" style="max-width: 640px;">
        @yield('content')
    </main>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
