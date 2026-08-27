<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Auto Bengkel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark" style="background: linear-gradient(135deg, #7c3aed, #ec4899);">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">Auto Bengkel — Admin</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-light">Keluar</button>
            </form>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="h3 fw-bold">Selamat datang, {{ auth()->user()->full_name }} 👋</h1>
        <p class="text-muted">Ini halaman dashboard Admin — masih placeholder, akan dibangun lengkap
            sesuai desain (kartu statistik, antrian servis, peringatan stok) di langkah berikutnya.</p>
    </div>
</body>
</html>
