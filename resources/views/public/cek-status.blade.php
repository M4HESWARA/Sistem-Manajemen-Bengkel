@extends('layouts.public')

@section('title', 'Cek Status Servis - KSR Garage')

@section('hide-navbar')@endsection
@section('hide-footer')@endsection

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<style>
    .tailwind-scope {
        font-family: 'Inter', sans-serif;
    }
    .full-bleed-container {
        width: 100%;
        max-width: 100%;
    }
    /* Sembunyikan footer & navbar bawaan dari template master */
    footer:not(.tailwind-footer),
    header:not(.tailwind-header) {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="tailwind-scope full-bleed-container bg-[#FAFAFA] min-h-screen flex flex-col justify-between">
    
    <!-- NAVBAR / HEADER -->
    <header class="tailwind-header w-full bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-16 py-4 flex justify-between items-center">
            <!-- Logo Bengkel -->
            <a href="{{ url('/') }}" class="flex items-center">
                <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage" class="h-[40px] w-auto object-contain">
            </a>

            <!-- Tombol Kembali ke Halaman Utama -->
            <div>
                <a href="{{ url('/') }}" class="group text-xs md:text-sm font-medium text-gray-500 hover:text-[#BF24B4] transition-colors no-underline flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#BF24B4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span>Kembali ke Halaman Utama</span>
                </a>
            </div>
        </div>
    </header>

    <!-- CONTENT CEK STATUS -->
    <main class="w-full pt-16 pb-20 md:pt-24 md:pb-28 flex flex-col items-center justify-center text-center px-4 flex-grow"
          style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23f0f0f0\' fill-opacity=\'0.5\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'3\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'3\'/%3E%3C/g%3E%3C/svg%3E');">
        
        <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 max-w-3xl leading-tight mb-4">
            Pantau Progres<br>Motor Anda
        </h1>

        <p class="text-gray-500 text-sm md:text-base max-w-md leading-relaxed mb-8">
            Masukkan Nomor Polisi kendaraan Anda untuk melihat status perbaikan saat ini.
        </p>

        <!-- FORM CEK STATUS -->
        <div class="w-full max-w-xl bg-white p-2 md:p-3 rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100">
            <form action="{{ route('public.cek-status') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
                <div class="relative w-full flex items-center">
                    <svg class="w-5 h-5 text-gray-400 absolute left-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" 
                           name="nopol" 
                           placeholder="B 1234 ABC" 
                           value="{{ request('nopol') }}" 
                           required 
                           class="w-full pl-11 pr-4 py-3 bg-transparent text-gray-800 placeholder-gray-400 text-sm md:text-base focus:outline-none uppercase font-semibold">
                </div>
                <button type="submit" 
                        class="w-full sm:w-auto bg-[#BF24B4] hover:bg-[#a31d99] text-white font-semibold text-sm px-6 py-3.5 rounded-xl transition-all whitespace-nowrap">
                    Cek Status
                </button>
            </form>
        </div>

        {{-- JIKA ADA RESULT / HASIL PENCARIAN --}}
        @if(isset($transaksi))
        <div class="w-full max-w-xl mt-8 text-left bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Hasil Status Perbaikan</h3>
            <div class="space-y-3 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span class="text-gray-400">Nomor Polisi:</span>
                    <span class="font-bold text-gray-800">{{ $transaksi->no_polisi ?? request('nopol') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Status:</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-[#BF24B4]">
                        {{ $transaksi->status ?? 'Dalam Pengerjaan' }}
                    </span>
                </div>
            </div>
        </div>
        @endif

    </main>

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