@extends('layouts.admin')

@section('title', 'Info Bengkel')

@section('content')

<!-- Style khusus untuk efek hover magenta -->
<style>
    .back-settings-link {
        color: #6b7280 !important; /* Warna abu-abu default */
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .back-settings-link:hover {
        color: #BF24B4 !important; /* Warna magenta saat di-hover */
    }
</style>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<!-- Baris tautan yang diperbarui class-nya -->
<a href="{{ route('admin.settings.index') }}" class="back-settings-link d-inline-block mb-2 fw-semibold small">
    <i class="bi bi-arrow-left"></i> Kembali ke Pengaturan
</a>

<div class="mb-4">
    <h1 class="h4 fw-bold mb-0">Info Bengkel</h1>
    <p class="text-muted small mb-0">Data ini tampil di landing page dan nota pembayaran.</p>
</div>

<div class="d-flex justify-content-center">
    <div class="card p-4" style="max-width: 560px; width: 100%;">
        <form method="POST" action="{{ route('admin.settings.bengkel.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label small fw-semibold">Nama Bengkel</label>
                <input type="text" name="nama_bengkel" class="form-control"
                       value="{{ old('nama_bengkel', $setting->nama_bengkel) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $setting->alamat) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Telepon</label>
                <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $setting->telepon) }}">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $setting->email) }}">
            </div>

            <button type="submit" class="btn btn-bengkel w-100"><i class="bi bi-check-circle"></i> Simpan Perubahan</button>
        </form>
    </div>
</div>

@endsection