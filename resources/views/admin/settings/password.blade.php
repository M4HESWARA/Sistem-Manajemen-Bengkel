@extends('layouts.admin')

@section('title', 'Ganti Password')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="mb-4">
    <h1 class="h4 fw-bold mb-0">Ganti Password</h1>
    <p class="text-muted small mb-0">Ubah password akun Anda ({{ auth()->user()->full_name }}).</p>
</div>

<div class="card p-4" style="max-width: 480px;">
    <form method="POST" action="{{ route('admin.settings.password.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label small fw-semibold">Password Saat Ini</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Password Baru</label>
            <input type="password" name="password" class="form-control" required minlength="6">
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="form-control" required minlength="6">
        </div>

        <button type="submit" class="btn btn-bengkel w-100"><i class="bi bi-check-circle"></i> Ubah Password</button>
    </form>
</div>

@endsection
