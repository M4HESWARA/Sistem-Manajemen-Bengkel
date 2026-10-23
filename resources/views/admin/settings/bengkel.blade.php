@extends('layouts.admin')

@section('title', 'Info Bengkel')

@section('content')

<a href="{{ route('admin.settings.index') }}" class="back-settings-link d-inline-block mb-2 fw-semibold small">
    <i class="bi bi-arrow-left"></i> Kembali ke Pengaturan
</a>

<div class="mb-4">
    <h1 class="h4 fw-bold mb-0" style="color: #F8FAFC;">Info Bengkel</h1>
    <p class="text-muted small mb-0">Data ini tampil di landing page dan nota pembayaran.</p>
</div>

<div class="d-flex justify-content-center">
    <div class="card w-100" style="max-width: 672px; padding: 2rem;">
        <form method="POST" action="{{ route('admin.settings.bengkel.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label small fw-semibold">Nama Bengkel</label>
                <input type="text" name="nama_bengkel" class="form-control"
                       placeholder="mis. KSR Garage"
                       value="{{ old('nama_bengkel', $setting->nama_bengkel) }}" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Telepon</label>
                    <input type="text" name="telepon" class="form-control"
                           placeholder="08xxxxxxxxxx"
                           value="{{ old('telepon', $setting->telepon) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control"
                           placeholder="bengkel@email.com"
                           value="{{ old('email', $setting->email) }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold">Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" style="min-height: 100px;"
                          placeholder="Nama jalan, kelurahan, kota...">{{ old('alamat', $setting->alamat) }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.settings.index') }}" class="btn btn-batal px-4">Batal</a>
                <button type="submit" class="btn btn-magenta px-4">
                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<style>
    .back-settings-link {
        color: #94A3B8 !important;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .back-settings-link:hover {
        color: #FF00FF !important;
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
