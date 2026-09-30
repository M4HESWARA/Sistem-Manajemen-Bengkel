<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Auto Bengkel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bengkel-primary: #a31d99;
            --bengkel-accent: #BF24B4;
            --bengkel-accent-dark: #c000cf;
        }
        body {
            min-height: 100vh;
            background: #f8f9fb;
            font-family: 'Segoe UI', system-ui, sans-serif;
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
            color: #6b7280 !important; 
            text-decoration: none;
            font-weight: 600;
            font-size: .9rem;
        }
        .back-link:hover { color: var(--bengkel-accent) !important; }

        .brand-logo {
            font-family: 'Segoe UI', sans-serif;
            font-weight: 800;
            font-style: italic;
            font-size: 1.8rem;
            letter-spacing: -.5px;
            background: linear-gradient(135deg, var(--bengkel-primary), var(--bengkel-accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 2rem;
        }

        .login-card {
            width: 100%;
            max-width: 380px;
            border: none;
            border-top: 4px solid var(--bengkel-accent);
            border-radius: .75rem;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }

        .input-icon {
            background-color: #fff;
            color: #9ca3af;
        }
        .form-control:focus {
            border-color: var(--bengkel-accent);
            box-shadow: 0 0 0 .2rem rgba(236,72,153,.15);
        }

        .btn-bengkel {
            background-color: var(--bengkel-accent) !important;
            border: none !important;
            color: #fff !important;
            font-weight: 600;
        }
        .btn-bengkel:hover, .btn-bengkel:focus {
            background-color: var(--bengkel-accent-dark) !important;
            color: #fff !important;
        }

        .link-accent {
            color: var(--bengkel-accent);
            text-decoration: none;
            font-size: .85rem;
        }
        .link-accent:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <a href="{{ url('/') }}" class="back-link">
        <i class="bi bi-chevron-left"></i> Ke Halaman Utama
    </a>

    <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage" class="mb-4" style="max-width: 220px; width: 100%;">

    <div class="card login-card">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <h1 class="h4 fw-bold mb-1">Selamat Datang Kembali</h1>
                <p class="text-muted small mb-0">Silakan masuk ke akun Anda untuk melanjutkan</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
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
                        <label class="form-check-label small" for="remember">Remember me</label>
                    </div>
                    </div>

                <button type="submit" class="btn btn-bengkel w-100 py-2">MASUK</button>
            </form>
        </div>
    </div>
</body>
</html>
