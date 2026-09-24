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
        .topbar {
            background: #ffffff;
            padding: 0.8rem 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            border-bottom: 1px solid #edf2f7;
        }
        .brand-caption {
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: 500;
        }
        .card { 
            border: 1px solid #edf2f7; 
            border-radius: 12px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
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
        .status-pill {
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #6c757d;
            border: 1px solid #dee2e6;
            background: #ffffff;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
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

    <nav class="topbar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage" style="height: 32px;">
            <div class="brand-caption border-start ps-3 ms-1 d-none d-sm-block">
                Mekanik Dashboard
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-link text-muted p-0"><i class="bi bi-gear fs-5"></i></button>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-link text-muted p-0" title="Keluar">
                    <i class="bi bi-box-arrow-right fs-5"></i>
                </button>
            </form>
        </div>
    </nav>

    <main class="container-fluid py-4 px-lg-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>