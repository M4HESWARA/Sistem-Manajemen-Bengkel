@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('content')

<div class="mb-4">
    <h1 class="h4 fw-bold mb-0">Pengaturan</h1>
    <p class="text-muted small mb-0">Kelola akun pengguna, password, dan informasi bengkel.</p>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <a href="{{ route('admin.settings.pengguna') }}" class="text-decoration-none text-reset">
            <div class="card p-4 h-100">
                <div class="d-flex align-items-center justify-content-center mb-3"
                     style="width:48px;height:48px;border-radius:.9rem;background:linear-gradient(135deg,var(--bengkel-primary),var(--bengkel-accent));">
                    <i class="bi bi-people text-white fs-5"></i>
                </div>
                <h2 class="h6 fw-bold">Kelola Pengguna</h2>
                <p class="text-muted small mb-0">Tambah, edit, atau nonaktifkan akun Admin dan Mekanik.</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('admin.settings.password') }}" class="text-decoration-none text-reset">
            <div class="card p-4 h-100">
                <div class="d-flex align-items-center justify-content-center mb-3"
                     style="width:48px;height:48px;border-radius:.9rem;background:linear-gradient(135deg,var(--bengkel-primary),var(--bengkel-accent));">
                    <i class="bi bi-key text-white fs-5"></i>
                </div>
                <h2 class="h6 fw-bold">Ganti Password</h2>
                <p class="text-muted small mb-0">Ubah password akun Anda sendiri yang sedang login.</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('admin.settings.bengkel') }}" class="text-decoration-none text-reset">
            <div class="card p-4 h-100">
                <div class="d-flex align-items-center justify-content-center mb-3"
                     style="width:48px;height:48px;border-radius:.9rem;background:linear-gradient(135deg,var(--bengkel-primary),var(--bengkel-accent));">
                    <i class="bi bi-shop text-white fs-5"></i>
                </div>
                <h2 class="h6 fw-bold">Info Bengkel</h2>
                <p class="text-muted small mb-0">Nama, alamat, telepon, dan email bengkel yang tampil di nota.</p>
            </div>
        </a>
    </div>
</div>

@endsection
