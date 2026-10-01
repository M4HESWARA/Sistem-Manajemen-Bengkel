@extends('layouts.public')

@section('title', 'Auto Bengkel - KSR Garage')

@section('hide-navbar')@endsection
@section('hide-footer')@endsection

@push('styles')
<!-- Memuat Tailwind CSS khusus untuk halaman ini -->
<script src="https://cdn.tailwindcss.com"></script>
<style>
    .tailwind-scope {
        font-family: 'Inter', sans-serif;
    }
    .full-bleed-container {
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
    }
    html {
        scroll-behavior: smooth;
    }
    
    /* Trik CSS untuk menyembunyikan footer bawaan dari layouts/public */
    footer:not(.tailwind-footer) {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="tailwind-scope full-bleed-container bg-[#FAFAFA] min-h-screen flex flex-col justify-between">
    
    <!-- NAVBAR / HEADER -->
    <header class="w-full bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 md:px-12 lg:px-16 py-3 md:py-4 flex justify-between items-center gap-3">
            <a href="{{ url('/') }}" class="flex items-center flex-shrink-0">
                <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage" class="h-9 md:h-[40px] w-auto object-contain">
            </a>

            <nav class="hidden md:flex items-center gap-8">
                <a href="#alur-layanan" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors no-underline">Alur Layanan</a>
                <a href="#kontak" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors no-underline">Kontak</a>
            </nav>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}" class="hidden md:inline text-sm font-semibold text-gray-700 hover:text-gray-900 transition-colors no-underline">
                    Masuk
                </a>

                <button id="navToggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="navMobile"
                    class="md:hidden inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 transition-colors hover:bg-gray-50">
                    <svg id="navIconOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                    <svg id="navIconClose" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            </div>
        </div>

        <!-- Menu khusus mobile -->
        <div id="navMobile" class="md:hidden hidden border-t border-gray-100 bg-white">
            <nav class="max-w-7xl mx-auto px-5 sm:px-6 py-3 flex flex-col gap-1">
                <a href="#alur-layanan" class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 no-underline">Alur Layanan</a>
                <a href="#kontak" class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 no-underline">Kontak</a>
                <a href="{{ route('login') }}" class="mt-1 inline-flex items-center justify-center rounded-lg bg-[#BF24B4] hover:bg-[#a31d99] px-4 py-2.5 text-sm font-semibold text-white no-underline transition-colors">Masuk</a>
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
    <section class="w-full pt-16 pb-20 md:pt-24 md:pb-28 flex flex-col items-center text-center px-4" 
             style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23f0f0f0\' fill-opacity=\'0.5\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'3\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'3\'/%3E%3C/g%3E%3C/svg%3E');">
        
        <div class="inline-flex items-center gap-2 bg-[#F6E6F5] border border-purple-200/60 text-[#BF24B4] text-xs font-semibold px-4 py-1.5 rounded-full mb-8 shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            <span>PANTAU SERVIS REAL-TIME</span>
        </div>

        <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 max-w-4xl leading-tight tracking-tight mb-6">
            Tinggalkan Motor di Bengkel<br>Tanpa Rasa Khawatir.
        </h1>

        <p class="text-gray-500 text-sm md:text-base max-w-2xl leading-relaxed mb-10 px-2">
            Percayakan motor Anda pada teknisi ahli kami. Pantau setiap tahap perbaikan secara transparan langsung dari layar HP Anda, tanpa perlu repot bolak-balik bertanya ke admin.
        </p>

        <a href="{{ route('public.cek-status') }}" class="inline-flex items-center gap-2 bg-[#BF24B4] hover:bg-[#a31d99] text-white font-semibold text-sm md:text-base px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-purple-500/20 no-underline">
            <span>Cek Status Kendaraan</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </section>

    <!-- CARA KERJA PRAKTIS -->
    <section id="alur-layanan" class="w-full py-20 bg-gray-50/50 border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-6 md:px-12">
            
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-4xl font-extrabold text-gray-900 mb-3">
                    Cara Kerja Praktis
                </h2>
                <p class="text-gray-500 text-sm md:text-base">
                    Hanya 3 tahapan singkat untuk perbaikan motor yang lebih efisien.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Langkah 1 -->
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-[#BF24B4] mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9m4 0V5"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-3">1. Datang & Daftar</h3>
                    <p class="text-gray-500 text-xs md:text-sm leading-relaxed">
                        Datang langsung ke bengkel dan sampaikan keluhan motor Anda kepada Admin kami.
                    </p>
                </div>

                <!-- Langkah 2 -->
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-[#BF24B4] mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-3">2. Cek Status via Web</h3>
                    <p class="text-gray-500 text-xs md:text-sm leading-relaxed">
                        Buka website kami tanpa perlu login. Cukup masukkan Nomor Polisi kendaraan untuk memantau progres servis secara real-time.
                    </p>
                </div>

                <!-- Langkah 3 -->
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-[#BF24B4] mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-3">3. Selesai & Bayar</h3>
                    <p class="text-gray-500 text-xs md:text-sm leading-relaxed">
                        Setelah servis selesai, Anda akan menerima detail tagihan. Lakukan pembayaran di kasir dan motor siap dibawa pulang.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- BOTTOM CTA -->
    <section class="w-full py-20 bg-white text-center border-t border-gray-100">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-2xl md:text-4xl font-extrabold text-gray-900 mb-8 tracking-tight">
                Pantau Progres Motor Anda Sekarang.
            </h2>
            <a href="{{ route('public.cek-status') }}" class="inline-flex items-center gap-2 bg-[#BF24B4] hover:bg-[#a31d99] text-white font-semibold text-sm md:text-base px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-purple-500/20 no-underline">
                <span>Cek Status Motor Saya</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </section>

    <!-- FOOTER CUSTOM -->
    <footer id="kontak" class="tailwind-footer w-full bg-white border-t border-gray-100 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-gray-100">
                <div class="md:col-span-2">
                    <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage" class="h-9 w-auto mb-4 object-contain">
                    <p class="text-gray-400 text-xs md:text-sm leading-relaxed max-w-sm">
                        Inovasi bengkel motor modern. Memberikan pelayanan yang cepat, terpercaya, dan transparan melalui sistem digital.
                    </p>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4">Layanan</h4>
                    <ul class="space-y-2.5 text-xs md:text-sm text-gray-500 list-none p-0">
                        <li>Pemeriksaan Berkala</li>
                        <li>Perbaikan Mesin</li>
                        <li>Penggantian Suku Cadang</li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4">Hubungi Kami</h4>
                    <ul class="space-y-3 text-xs md:text-sm text-gray-500 list-none p-0">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Jl. Gatot Subroto No. 88, Jakarta Selatan, DKI Jakarta 12930</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>halo@ksr-garage.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 text-left">
                <p class="text-xs text-gray-400">
                    &copy; 2026 KSR Garage. All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>

</div>
@endsection