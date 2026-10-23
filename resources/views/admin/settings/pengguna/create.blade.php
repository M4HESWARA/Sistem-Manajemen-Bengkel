@extends('layouts.admin')

@section('title', 'Tambah Akun')

@section('content')

<a href="{{ route('admin.settings.pengguna') }}" class="back-link d-inline-block mb-2 small fw-semibold">
    <i class="bi bi-arrow-left"></i> Kembali ke Kelola Pengguna
</a>

<div class="mb-4">
    <h1 class="h4 fw-bold mb-0">Tambah Akun</h1>
    <p class="text-muted small mb-0">Buat akun baru untuk Admin atau Mekanik.</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Ada isian yang belum lengkap:</strong>
        <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card p-4">
    <form method="POST" action="{{ route('admin.settings.pengguna.store') }}">
        @csrf
        @include('admin.settings.pengguna._form')

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.settings.pengguna') }}" class="btn btn-batal px-4">Batal</a>
            <button type="submit" class="btn btn-magenta px-4"><i class="bi bi-check-circle"></i> Simpan</button>
        </div>
    </form>
</div>

@endsection

@push('styles')
<style>
    .back-link {
        color: #94A3B8;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .back-link:hover {
        color: #FF00FF;
    }
    .form-control, .form-select {
        background-color: #0F172A !important;
        border: 1px solid #334155 !important;
        color: #F8FAFC !important;
        color-scheme: dark;
    }
    .form-control::placeholder {
        color: #94A3B8 !important;
        opacity: 1;
    }
    .form-select option {
        background-color: #1E293B;
        color: #F8FAFC;
    }
    .form-control:focus, .form-select:focus {
        background-color: #0F172A !important;
        border-color: #FF00FF !important;
        box-shadow: 0 0 0 .2rem rgba(255, 0, 255, .25);
        color: #F8FAFC !important;
    }
    .btn-batal {
        background: transparent;
        border: 1px solid #334155;
        color: #E2E8F0;
        font-weight: 600;
    }
    .btn-batal:hover {
        background: #1E293B;
        border-color: #94A3B8;
        color: #FFFFFF;
    }
</style>
@endpush
