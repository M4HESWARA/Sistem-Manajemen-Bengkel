@extends('layouts.public')

@section('title', 'Auto Bengkel - KSR Garage')

@section('content')

{{-- Hero --}}
<section class="text-center py-5" style="background: #fafafa;">
    <div class="container py-4" style="max-width: 700px;">
        <span class="badge-outline mb-3"><i class="bi bi-shield-check"></i> Verifikasi Fisik Kendaraan Setiap Servis</span>
        <h1 class="fw-bold display-6 mb-3">Tinggalkan Motor di Bengkel Tanpa Rasa Khawatir.</h1>
        <p class="text-muted mb-4">
            Pantau setiap tahap perbaikan motor Anda secara real-time, tanpa perlu bertanya berkali-kali ke bengkel.
            Cukup masukkan Nomor Polisi, semua transparan lewat sistem digital kami.
        </p>
        <a href="{{ route('public.cek-status') }}" class="btn btn-bengkel btn-lg px-4">
            Cek Status Kendaraan <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</section>

{{-- Cara Kerja --}}
<section id="cara-kerja" class="py-5">
    <div class="container text-center" style="max-width: 720px;">
        <h2 class="fw-bold h3 mb-2">Cara Kerja Praktis</h2>
        <p class="text-muted mb-5">Cukup 3 langkah simpel untuk perbaikan motor yang lebih efisien.</p>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="d-flex align-items-center justify-content-center mx-auto mb-3"
                     style="width:56px;height:56px;border-radius:1rem;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,.06);">
                    <i class="bi bi-door-open fs-4" style="color: var(--bengkel-accent);"></i>
                </div>
                <h3 class="h6 fw-bold">1. Datang &amp; Daftar</h3>
                <p class="text-muted small">Datang langsung ke bengkel dan sampaikan keluhan motor Anda kepada Admin kami.</p>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center justify-content-center mx-auto mb-3"
                     style="width:56px;height:56px;border-radius:1rem;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,.06);">
                    <i class="bi bi-search fs-4" style="color: var(--bengkel-accent);"></i>
                </div>
                <h3 class="h6 fw-bold">2. Cek Status via Web</h3>
                <p class="text-muted small">Cukup masukkan Nomor Polisi kendaraan untuk memantau progres servis kapan saja.</p>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center justify-content-center mx-auto mb-3"
                     style="width:56px;height:56px;border-radius:1rem;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,.06);">
                    <i class="bi bi-chat-square-heart fs-4" style="color: var(--bengkel-accent);"></i>
                </div>
                <h3 class="h6 fw-bold">3. Selesai &amp; Bayar</h3>
                <p class="text-muted small">Setelah servis selesai, lakukan pembayaran di kasir dan motor siap dibawa pulang.</p>
            </div>
        </div>
    </div>
</section>

{{-- CTA Bawah --}}
<section class="text-center py-5" style="background: #fff;">
    <div class="container">
        <h2 class="fw-bold h4 mb-3">Pantau Progres Motor Anda Sekarang.</h2>
        <a href="{{ route('public.cek-status') }}" class="btn btn-bengkel btn-lg px-4">
            Cek Status Motor Saya <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</section>

@endsection
