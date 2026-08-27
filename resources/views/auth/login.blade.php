<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Auto Bengkel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bengkel-primary: #7c3aed;
            --bengkel-primary-dark: #6d28d9;
            --bengkel-accent: #ec4899;
        }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--bengkel-primary) 0%, var(--bengkel-accent) 100%);
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 20px 50px rgba(0,0,0,.25);
        }
        .brand-badge {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--bengkel-primary), var(--bengkel-accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            font-size: 1.4rem;
        }
        .btn-bengkel {
            background: linear-gradient(135deg, var(--bengkel-primary), var(--bengkel-accent));
            border: none;
            color: #fff;
            font-weight: 600;
        }
        .btn-bengkel:hover {
            opacity: .9;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="card login-card">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <div class="brand-badge mx-auto mb-3">AB</div>
                <h1 class="h4 fw-bold mb-1">Auto Bengkel</h1>
                <p class="text-muted small mb-0">Sistem Manajemen Bengkel</p>
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
                    <input type="text" class="form-control" id="username" name="username"
                           value="{{ old('username') }}" required autofocus placeholder="Masukkan username">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label small fw-semibold">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                           required placeholder="Masukkan password">
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label small" for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-bengkel w-100 py-2">Masuk</button>
            </form>
        </div>
    </div>
</body>
</html>
