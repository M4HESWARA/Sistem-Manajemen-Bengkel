@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('content')

<div class="mb-4">
    <h1 class="h4 fw-bold mb-0" style="color: #F8FAFC;">Pengaturan</h1>
    <p class="text-muted small mb-0">Kelola akun pengguna dan informasi bengkel.</p>
</div>

<div class="mx-auto" style="max-width: 1024px;">
    <div class="row g-4">
        <div class="col-md-6">
            <a href="{{ route('admin.settings.pengguna') }}" class="settings-link text-decoration-none">
                <div class="settings-card">
                    <div class="settings-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h2 class="settings-title">Kelola Pengguna</h2>
                    <p class="settings-desc">Tambah, edit, atau nonaktifkan akun Admin dan Mekanik.</p>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('admin.settings.bengkel') }}" class="settings-link text-decoration-none">
                <div class="settings-card">
                    <div class="settings-icon">
                        <i class="bi bi-shop"></i>
                    </div>
                    <h2 class="settings-title">Info Bengkel</h2>
                    <p class="settings-desc">Nama, alamat, telepon, dan email bengkel yang tampil di nota.</p>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .settings-link {
        display: block;
        color: inherit;
    }
    .settings-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 2rem;
        background-color: #1E293B;
        border: 1px solid #334155;
        border-radius: 1rem;
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }
    .settings-link:hover .settings-card {
        border-color: #FF00FF;
        box-shadow: 0 0 15px rgba(255, 0, 255, 0.2), 0 10px 30px rgba(0, 0, 0, 0.35);
        transform: translateY(-4px);
    }
    .settings-icon {
        width: 52px;
        height: 52px;
        border-radius: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #0F172A;
        border: 1px solid #334155;
        color: #FF00FF;
        font-size: 1.4rem;
        margin-bottom: 1rem;
        transition: box-shadow .2s ease;
    }
    .settings-link:hover .settings-icon {
        box-shadow: 0 0 12px rgba(255, 0, 255, 0.35);
    }
    .settings-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #F8FAFC;
        margin-bottom: 0.4rem;
    }
    .settings-desc {
        color: #94A3B8;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    .settings-arrow {
        margin-top: auto;
        align-self: flex-end;
        font-size: 0.85rem;
        font-weight: 600;
        color: #94A3B8;
        transition: color .2s ease;
    }
    .settings-link:hover .settings-arrow {
        color: #FF00FF;
    }
</style>
@endpush
