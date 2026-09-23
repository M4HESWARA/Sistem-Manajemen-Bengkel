<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Auto Bengkel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --bengkel-primary: #7c3aed; --bengkel-accent: #ed02fe; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; color: #1f2937; }
        .navbar-public { padding: 1rem 1.5rem; background: #fff; border-bottom: 1px solid #f0f0f0; }
        .navbar-public img { height: 45px; }
        .nav-link-public { color: #4b5563; font-weight: 600; font-size: .9rem; text-decoration: none; }
        .nav-link-public:hover { color: var(--bengkel-primary); }
        .btn-bengkel {
            background: linear-gradient(135deg, var(--bengkel-primary), var(--bengkel-accent));
            border: none; color: #fff; font-weight: 600; border-radius: .6rem;
        }
        .btn-bengkel:hover { opacity: .9; color: #fff; }
        .badge-outline {
            border: 1px solid var(--bengkel-accent); color: var(--bengkel-accent);
            border-radius: 2rem; padding: .35rem .9rem; font-size: .8rem; font-weight: 600;
            display: inline-block;
        }
        footer { background: #fafafa; border-top: 1px solid #eee; padding: 3rem 0 1.5rem; }
        footer a { color: #6b7280; text-decoration: none; font-size: .9rem; }
        footer a:hover { color: var(--bengkel-primary); }
        footer img { height: 32px; }
    </style>
    @stack('styles')
</head>
<body>
    @unless (View::hasSection('hide-navbar'))
    <nav class="navbar-public d-flex align-items-center justify-content-between">
        <a href="{{ route('public.landing') }}"><img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage"></a>
        <div class="d-none d-md-flex gap-4">
            <a href="{{ route('public.landing') }}#cara-kerja" class="nav-link-public">Alur Layanan</a>
            <a href="{{ route('public.cek-status') }}" class="nav-link-public">Cek Status</a>
        </div>
        <a href="{{ route('login') }}" class="nav-link-public">Masuk</a>
    </nav>
    @endunless

    @yield('content')

    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-md-5">
                    <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage" class="mb-2">
                    <p class="text-muted small mb-0">
                        Bengkel terpercaya yang memberikan pelayanan cepat, transparan, dan terpercaya melalui sistem digital.
                    </p>
                </div>
                <div class="col-md-3">
                    <p class="fw-bold small mb-2">Layanan</p>
                    <div class="d-flex flex-column gap-1">
                        <a href="#">Pemeriksaan Berkala</a>
                        <a href="#">Perbaikan Mesin</a>
                        <a href="#">Penggantian Suku Cadang</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <p class="fw-bold small mb-2">Hubungi Kami</p>
                    <p class="text-muted small mb-1"><i class="bi bi-geo-alt"></i> Jl. Raya Bengkel No. 88, Jakarta Selatan</p>
                    <p class="text-muted small mb-1"><i class="bi bi-telephone"></i> (021) 555-0199</p>
                    <p class="text-muted small mb-0"><i class="bi bi-envelope"></i> halo@ksrgarage.com</p>
                </div>
            </div>
            <hr class="my-4">
            <p class="text-center text-muted small mb-0">&copy; {{ date('Y') }} KSR Garage. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>