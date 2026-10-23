@extends('layouts.admin')

@section('title', 'Tambah Sparepart')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.inventori') }}" class="back-link d-inline-flex align-items-center gap-1 mb-2 text-decoration-none">
        <i class="bi bi-arrow-left"></i> Kembali ke Inventori
    </a>
    <h1 class="h4 fw-bold mb-0" style="color: #F8FAFC;">Tambah Sparepart</h1>
    <p class="small mb-0" style="color: #94A3B8;">Tambahkan data sparepart baru ke inventaris.</p>
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
    <form method="POST" action="{{ route('admin.inventori.store') }}">
        @csrf
        @include('admin.inventori._form')

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.inventori') }}" class="btn btn-outline-slate px-4">Batal</a>
            <button type="submit" class="btn btn-magenta px-4"><i class="bi bi-check-circle"></i> Simpan</button>
        </div>
    </form>
</div>

@endsection

@push('styles')
<style>
    .back-link {
        color: #94A3B8;
        font-size: 0.85rem;
        font-weight: 600;
        transition: color 0.2s ease;
    }
    .back-link:hover {
        color: #FF00FF;
    }
    html { color-scheme: dark; }
    .form-control,
    .form-select,
    .input-group-text {
        background-color: #0F172A !important;
        border: 1px solid #334155 !important;
        color: #F8FAFC !important;
        color-scheme: dark;
    }
    .form-control::placeholder {
        color: #94A3B8 !important;
        opacity: 1 !important;
    }
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2394A3B8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
        background-repeat: no-repeat !important;
        background-position: right 0.75rem center !important;
        background-size: 16px 12px !important;
        padding-right: 2.25rem;
    }
    .form-select option {
        background-color: #1E293B !important;
        color: #F8FAFC !important;
    }
    .form-control:focus,
    .form-select:focus {
        background-color: #0F172A !important;
        border-color: #FF00FF !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 0, 255, 0.25) !important;
        color: #F8FAFC !important;
    }
    input[type='number']::-webkit-inner-spin-button,
    input[type='number']::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type='number'] {
        -moz-appearance: textfield;
        appearance: textfield;
    }
    .btn-outline-slate {
        background-color: transparent;
        border: 1px solid #334155;
        color: #E2E8F0;
        font-weight: 600;
    }
    .btn-outline-slate:hover,
    .btn-outline-slate:focus {
        background-color: #1E293B;
        border-color: #FF00FF;
        color: #FFFFFF;
    }
</style>
@endpush
