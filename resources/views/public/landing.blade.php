@extends('layouts.public')

@section('title', 'KSR Garage - Servis Motor Transparan')

@section('hide-navbar')@endsection
@section('hide-footer')@endsection

@push('styles')
<!-- Memuat Tailwind CSS khusus untuk halaman ini -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    .tailwind-scope {
        font-family: 'Inter', sans-serif;
    }
    .full-bleed-container {
        width: 100%;
        max-width: 100%;
    }
    html {
        scroll-behavior: smooth;
    }
    body {
        background-color: #121212;
    }
    /* Trik CSS untuk menyembunyikan footer bawaan dari layouts/public */
    footer:not(.tailwind-footer) {
        display: none !important;
    }
    .glow-magenta {
        box-shadow: 0 0 20px rgba(255, 0, 255, 0.45), 0 0 45px rgba(255, 0, 255, 0.25);
    }
    .glow-magenta:hover {
        box-shadow: 0 0 28px rgba(255, 0, 255, 0.65), 0 0 60px rgba(255, 0, 255, 0.35);
    }
    .hero-grid {
        background-image:
            linear-gradient(rgba(255, 0, 255, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 0, 255, 0.05) 1px, transparent 1px);
        background-size: 44px 44px;
    }
</style>
@endpush

@section('content')
<div class="tailwind-scope full-bleed-container bg-[#121212] min-h-screen flex flex-col justify-between text-[#F8FAFC]">

    <!-- NAVBAR / HEADER -->
    <header class="w-full bg-[#121212]/80 backdrop-blur-md sticky top-0 z-50 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 md:px-12 lg:px-16 py-3 md:py-4 flex justify-between items-center gap-3">
            <a href="{{ url('/') }}" class="flex items-center flex-shrink-0">
                <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage" class="h-9 md:h-[40px] w-auto object-contain">
            </a>

            <nav class="hidden md:flex items-center gap-10">
                <a href="#alur-layanan" class="text-sm font-medium text-[#94A3B8] hover:text-[#FF00FF] transition-colors no-underline">Alur Layanan</a>
                <a href="#kontak" class="text-sm font-medium text-[#94A3B8] hover:text-[#FF00FF] transition-colors no-underline">Kontak</a>
            </nav>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}" class="hidden md:inline text-sm font-medium text-[#94A3B8] hover:text-[#FF00FF] transition-colors no-underline">
                    Masuk
                </a>

                <button id="navToggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="navMobile"
                    class="md:hidden inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-white/15 bg-white/5 text-[#F8FAFC] transition-colors hover:bg-white/10">
                    <svg id="navIconOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                    <svg id="navIconClose" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            </div>
        </div>

        <!-- Menu khusus mobile -->
        <div id="navMobile" class="md:hidden hidden border-t border-white/10 bg-[#121212]">
            <nav class="max-w-7xl mx-auto px-5 sm:px-6 py-3 flex flex-col gap-1">
                <a href="#alur-layanan" class="px-3 py-2.5 rounded-lg text-sm font-medium text-[#94A3B8] hover:bg-white/5 hover:text-[#FF00FF] no-underline">Alur Layanan</a>
                <a href="#kontak" class="px-3 py-2.5 rounded-lg text-sm font-medium text-[#94A3B8] hover:bg-white/5 hover:text-[#FF00FF] no-underline">Kontak</a>
                <a href="{{ route('login') }}" class="px-3 py-2.5 rounded-lg text-sm font-medium text-[#94A3B8] hover:bg-white/5 hover:text-[#FF00FF] no-underline">Masuk</a>
            </nav>
        </div>
    </header>

    <script>
        (function () {
            var toggle = document.getElementById('navToggle');
            var menu = document.getElementById('navMobile');
            var iconOpen = document.getElementById('navIconOpen');
            var iconClose = document.getElementById('navIconClose');
            if (!toggle || !menu) { return; }

            toggle.addEventListener('click', function () {
                var open = menu.classList.contains('hidden');
                menu.classList.toggle('hidden', !open);
                toggle.setAttribute('aria-expanded', String(open));
                toggle.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
                iconOpen.classList.toggle('hidden', open);
                iconClose.classList.toggle('hidden', !open);
            });

            menu.addEventListener('click', function (e) {
                if (e.target.closest('a')) { toggle.click(); }
            });
        })();
    </script>

    <!-- HERO SECTION -->
    <section class="w-full pt-16 pb-20 md:pt-24 md:pb-28 flex flex-col items-center text-center px-4 hero-grid relative overflow-hidden">
        <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#FF00FF]/10 blur-[140px] rounded-full pointer-events-none"></div>

        <div class="relative inline-flex items-center gap-2 bg-[#FF00FF]/10 border border-[#FF00FF]/40 text-[#FF00FF] text-xs font-bold px-4 py-1.5 rounded-full mb-8 tracking-wider">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            <span>PANTAU SERVIS REAL-TIME</span>
        </div>

        <h1 class="relative text-3xl md:text-5xl lg:text-6xl font-extrabold text-[#F8FAFC] max-w-4xl leading-tight tracking-tight mb-6">
            Tinggalkan Motor di Bengkel<br>Tanpa Rasa Khawatir.
        </h1>

        <p class="relative text-[#94A3B8] text-sm md:text-base max-w-2xl leading-relaxed mb-10 px-2">
            Percayakan motor Anda pada teknisi ahli kami. Pantau setiap tahap perbaikan secara transparan langsung dari layar HP Anda, tanpa perlu repot bolak-balik bertanya ke admin.
        </p>

        <a href="{{ route('public.cek-status') }}" class="relative inline-flex items-center gap-2 bg-[#FF00FF] hover:bg-[#e600e6] text-white font-bold text-sm md:text-base px-8 py-3.5 rounded-xl transition-all glow-magenta no-underline">
            <span>Cek Status Kendaraan</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </section>

    <!-- CARA KERJA PRAKTIS -->
    <section id="alur-layanan" class="w-full py-20 bg-[#0d0d0d] border-t border-white/10">
        <div class="max-w-6xl mx-auto px-6 md:px-12">

            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-4xl font-extrabold text-[#F8FAFC] mb-3">
                    Cara Kerja Praktis
                </h2>
                <p class="text-[#94A3B8] text-sm md:text-base">
                    Hanya 3 tahapan singkat untuk perbaikan motor yang lebih efisien.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Langkah 1 -->
                <div class="bg-[#1E293B] rounded-3xl p-8 border border-white/10 shadow-lg shadow-black/30 flex flex-col items-center text-center transition-transform hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-[#FF00FF]/10 flex items-center justify-center text-[#FF00FF] mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9m4 0V5"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-[#F8FAFC] mb-3">1. Datang &amp; Daftar</h3>
                    <p class="text-[#94A3B8] text-xs md:text-sm leading-relaxed">
                        Datang langsung ke bengkel dan sampaikan keluhan motor Anda kepada Admin kami.
                    </p>
                </div>

                <!-- Langkah 2 -->
                <div class="bg-[#1E293B] rounded-3xl p-8 border border-white/10 shadow-lg shadow-black/30 flex flex-col items-center text-center transition-transform hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-[#FF00FF]/10 flex items-center justify-center text-[#FF00FF] mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-[#F8FAFC] mb-3">2. Cek Status via Web</h3>
                    <p class="text-[#94A3B8] text-xs md:text-sm leading-relaxed">
                        Buka website kami tanpa perlu login. Cukup masukkan Nomor Polisi kendaraan untuk memantau progres servis secara real-time.
                    </p>
                </div>

                <!-- Langkah 3 -->
                <div class="bg-[#1E293B] rounded-3xl p-8 border border-white/10 shadow-lg shadow-black/30 flex flex-col items-center text-center transition-transform hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-[#FF00FF]/10 flex items-center justify-center text-[#FF00FF] mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-[#F8FAFC] mb-3">3. Selesai &amp; Bayar</h3>
                    <p class="text-[#94A3B8] text-xs md:text-sm leading-relaxed">
                        Setelah servis selesai, Anda akan menerima detail tagihan. Lakukan pembayaran di kasir dan motor siap dibawa pulang.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- BOTTOM CTA -->
    <section class="w-full py-20 bg-[#121212] text-center border-t border-white/10">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-2xl md:text-4xl font-extrabold text-[#F8FAFC] mb-8 tracking-tight">
                Pantau Progres Motor Anda Sekarang.
            </h2>
            <a href="{{ route('public.cek-status') }}" class="inline-flex items-center gap-2 bg-[#FF00FF] hover:bg-[#e600e6] text-white font-bold text-sm md:text-base px-8 py-3.5 rounded-xl transition-all glow-magenta no-underline">
                <span>Cek Status Motor Saya</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </section>

    <!-- FOOTER CUSTOM -->
    <footer id="kontak" class="tailwind-footer w-full bg-[#0d0d0d] border-t border-white/10 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-white/10">
                <div class="md:col-span-2">
                    <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage" class="h-9 w-auto mb-4 object-contain">
                    <p class="text-[#94A3B8] text-xs md:text-sm leading-relaxed max-w-sm">
                        Inovasi bengkel motor modern. Memberikan pelayanan yang cepat, terpercaya, dan transparan melalui sistem digital.
                    </p>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-[#F8FAFC] uppercase tracking-wider mb-4">Layanan</h4>
                    <ul class="space-y-2.5 text-xs md:text-sm text-[#94A3B8] list-none p-0">
                        <li>Pemeriksaan Berkala</li>
                        <li>Perbaikan Mesin</li>
                        <li>Penggantian Suku Cadang</li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-[#F8FAFC] uppercase tracking-wider mb-4">Hubungi Kami</h4>
                    <ul class="space-y-3 text-xs md:text-sm text-[#94A3B8] list-none p-0">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-[#94A3B8] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Jl. Gatot Subroto No. 88, Jakarta Selatan, DKI Jakarta 12930</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#94A3B8] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>halo@ksr-garage.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 text-left">
                <p class="text-xs text-[#94A3B8]">
                    &copy; 2026 KSR Garage. All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>

</div>
@endsection
