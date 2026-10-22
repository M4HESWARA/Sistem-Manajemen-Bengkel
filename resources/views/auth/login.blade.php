<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - KSR Garage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --magenta: #FF00FF;
            --magenta-hover: #e600e6;
        }
        body {
            min-height: 100vh;
            background: #121212;
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            color: #F8FAFC;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .back-link {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            color: #94A3B8 !important;
            text-decoration: none;
            font-weight: 600;
            font-size: .9rem;
            transition: color .2s ease;
        }
        .back-link:hover { color: var(--magenta) !important; }

        .card.login-card {
            width: 100%;
            max-width: 400px;
            background: #1E293B;
            border: 1px solid #334155;
            border-radius: .75rem;
            box-shadow: 0 0 30px rgba(255, 0, 255, 0.12), 0 10px 30px rgba(0,0,0,.4);
        }

        .card.login-card h1 { color: #F8FAFC; }
        .card.login-card .form-label { color: #F8FAFC; }
        .card.login-card .text-muted,
        .card.login-card .subtitle { color: #94A3B8 !important; }

        .input-icon {
            background-color: #0F172A;
            color: #94A3B8;
            border: 1px solid #334155;
            border-right: none;
        }
        .form-control {
            background-color: #0F172A !important;
            border: 1px solid #334155;
            color: #F8FAFC !important;
        }
        .form-control::placeholder { color: #94A3B8; }
        .form-control:focus {
            background-color: #0F172A;
            border-color: var(--magenta);
            box-shadow: 0 0 0 .2rem rgba(255, 0, 255, .25);
            color: #F8FAFC;
        }
        .input-group:focus-within .input-icon {
            border-color: var(--magenta);
            color: var(--magenta);
        }

        .form-check-input {
            background-color: #0F172A;
            border-color: #334155;
        }
        .form-check-input:checked {
            background-color: var(--magenta);
            border-color: var(--magenta);
        }
        .form-check-input:focus {
            border-color: var(--magenta);
            box-shadow: 0 0 0 .2rem rgba(255, 0, 255, .25);
        }
        .form-check-label { color: #94A3B8; }

        .btn-magenta {
            background-color: var(--magenta) !important;
            border: none !important;
            color: #fff !important;
            font-weight: 700;
            letter-spacing: .5px;
            border-radius: .6rem;
            box-shadow: 0 0 20px rgba(255, 0, 255, 0.45), 0 0 45px rgba(255, 0, 255, 0.25);
            transition: box-shadow .2s ease, background-color .2s ease;
        }
        .btn-magenta:hover, .btn-magenta:focus {
            background-color: var(--magenta-hover) !important;
            color: #fff !important;
            box-shadow: 0 0 28px rgba(255, 0, 255, 0.65), 0 0 60px rgba(255, 0, 255, 0.35);
        }

        .alert-danger {
            background: rgba(255, 0, 255, .1);
            border: 1px solid rgba(255, 0, 255, .4);
            color: #ff80ff;
        }
    </style>
    <link href="{{ asset('css/bengkel-theme.css') }}" rel="stylesheet">
</head>
<body>
    <a href="{{ url('/') }}" class="back-link">
        <i class="bi bi-chevron-left"></i> Ke Halaman Utama
    </a>

    <div class="card login-card">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage" class="mb-4" style="max-width: 200px; width: 100%;">
                <h1 class="h4 fw-bold mb-1">Selamat Datang Kembali</h1>
                <p class="subtitle small mb-0">Silakan masuk ke akun Anda untuk melanjutkan</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label small fw-semibold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text input-icon"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="username" name="username"
                               value="{{ old('username') }}" required autofocus placeholder="Masukkan username">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label small fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text input-icon"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                               required placeholder="••••••••">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label small" for="remember">Ingat saya</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-magenta w-100 py-2">MASUK</button>
            </form>
        </div>
    </div>
</body>
</html>
