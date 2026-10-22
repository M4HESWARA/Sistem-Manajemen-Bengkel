@extends('layouts.public')

@section('title', 'Cek Status Servis - KSR Garage')

@section('hide-navbar')@endsection
@section('hide-footer')@endsection

@push('styles')
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
    /* Sembunyikan footer & navbar bawaan dari template master */
    footer:not(.tailwind-footer),
    header:not(.tailwind-header) {
        display: none !important;
    }
    .glow-magenta {
        box-shadow: 0 0 20px rgba(255, 0, 255, 0.45), 0 0 45px rgba(255, 0, 255, 0.25);
    }
    .glow-magenta:hover {
        box-shadow: 0 0 28px rgba(255, 0, 255, 0.65), 0 0 60px rgba(255, 0, 255, 0.35);
    }
    .search-card-glow {
        box-shadow: 0 0 30px rgba(255, 0, 255, 0.12), inset 0 0 0 1px rgba(255, 0, 255, 0.08);
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
    <header class="tailwind-header w-full bg-[#121212]/80 backdrop-blur-md sticky top-0 z-50 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-16 py-4 flex justify-between items-center">
            <!-- Logo Bengkal -->
            <a href="{{ url('/') }}" class="flex items-center">
                <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage" class="h-[40px] w-auto object-contain">
            </a>

            <!-- Tombol Kembali ke Halaman Utama -->
            <div>
                <a href="{{ url('/') }}" class="group text-xs md:text-sm font-medium text-[#94A3B8] hover:text-[#FF00FF] transition-colors no-underline flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#94A3B8] group-hover:text-[#FF00FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span>Kembali ke Halaman Utama</span>
                </a>
            </div>
        </div>
    </header>

    <!-- CONTENT CEK STATUS -->
    <main class="w-full pt-16 pb-20 md:pt-24 md:pb-28 flex flex-col items-center justify-center text-center px-4 flex-grow hero-grid relative overflow-hidden">
        <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#FF00FF]/10 blur-[140px] rounded-full pointer-events-none"></div>

        <h1 class="relative text-3xl md:text-5xl font-extrabold text-[#F8FAFC] max-w-3xl leading-tight mb-4">
            Pantau Progres<br>Motor Anda
        </h1>

        <p class="relative text-[#94A3B8] text-sm md:text-base max-w-md leading-relaxed mb-8">
            Masukkan Nomor Polisi kendaraan Anda untuk melihat status perbaikan saat ini.
        </p>

        <!-- FORM CEK STATUS -->
        <div class="relative w-full max-w-xl bg-[#1E293B] p-2 md:p-3 rounded-2xl border border-[#334155] search-card-glow">
            <form action="{{ route('public.cek-status') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
                <div class="relative w-full flex items-center">
                    <svg class="w-5 h-5 text-[#94A3B8] absolute left-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text"
                           name="plat_nomor"
                           placeholder="B 1234 ABC"
                           value="{{ request('plat_nomor') }}"
                           required
                           class="w-full pl-11 pr-4 py-3.5 bg-[#0F172A] rounded-xl text-[#F8FAFC] placeholder-gray-500 text-sm md:text-base font-bold uppercase tracking-wide outline-none border border-transparent focus:border-[#FF00FF] focus:ring-2 focus:ring-[#FF00FF]/50 transition-all">
                </div>
                <button type="submit"
                        class="w-full sm:w-auto bg-[#FF00FF] hover:bg-[#e600e6] text-white font-bold text-sm px-6 py-3.5 rounded-xl transition-all whitespace-nowrap glow-magenta">
                    Cek Status
                </button>
            </form>
        </div>

        {{-- JIKA ADA RESULT / HASIL PENCARIAN --}}
        @if($searched)
            @php
                $statusLabels = [
                    'menunggu' => 'Menunggu Antrean',
                    'proses_diagnosa' => 'Sedang Diagnosa',
                    'sedang_diperbaiki' => 'Sedang Diperbaiki',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                ];
                $statusColors = [
                    'menunggu' => 'bg-amber-400/15 text-amber-300 border border-amber-400/30',
                    'proses_diagnosa' => 'bg-blue-400/15 text-blue-300 border border-blue-400/30',
                    'sedang_diperbaiki' => 'bg-[#FF00FF]/15 text-[#FF00FF] border border-[#FF00FF]/40',
                    'selesai' => 'bg-emerald-400/15 text-emerald-300 border border-emerald-400/30',
                    'dibatalkan' => 'bg-red-400/15 text-red-300 border border-red-400/30',
                ];
                $steps = [
                    'menunggu' => 1,
                    'proses_diagnosa' => 2,
                    'sedang_diperbaiki' => 3,
                    'selesai' => 4,
                ];
                $stepLabels = ['Pendaftaran', 'Diagnosa', 'Pengerjaan', 'Selesai'];
            @endphp

            @if($vehicle)
                <div class="w-full max-w-xl mt-8 text-left space-y-4">
                    <div class="bg-[#1E293B] p-6 rounded-2xl border border-[#334155] shadow-lg shadow-black/30">
                        <h3 class="text-lg font-bold text-[#F8FAFC] mb-4 border-b border-white/10 pb-2">Kendaraan Ditemukan</h3>
                        <div class="space-y-3 text-sm text-[#94A3B8]">
                            <div class="flex justify-between">
                                <span class="text-[#94A3B8]">Nomor Polisi:</span>
                                <span class="font-bold text-[#F8FAFC]">{{ $vehicle->plat_nomor }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#94A3B8]">Kendaraan:</span>
                                <span class="font-semibold text-[#F8FAFC]">{{ trim(($vehicle->brand ?? '') . ' ' . ($vehicle->model ?? '')) ?: '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#94A3B8]">Pemilik:</span>
                                <span class="font-semibold text-[#F8FAFC]">{{ $vehicle->customer->full_name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    @forelse($serviceOrders as $order)
                        <div class="bg-[#1E293B] p-6 rounded-2xl border border-[#334155] shadow-lg shadow-black/30">
                            <div class="flex items-center justify-between mb-5">
                                <span class="text-xs font-semibold text-[#94A3B8]">{{ $order->order_number }}</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$order->status] ?? 'bg-white/10 text-[#94A3B8] border border-white/10' }}">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </div>

                            {{-- TIMELINE PROGRESS --}}
                            @php $activeStep = $steps[$order->status] ?? 1; @endphp
                            <div class="mb-6">
                                <div class="flex items-center">
                                    @foreach($stepLabels as $i => $label)
                                        @php $stepNum = $i + 1; @endphp
                                        <div class="flex items-center {{ $loop->last ? '' : 'flex-1' }}">
                                            <div class="flex flex-col items-center">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all
                                                    {{ $stepNum <= $activeStep ? 'bg-[#FF00FF] border-[#FF00FF] text-white glow-magenta' : 'bg-[#0F172A] border-[#334155] text-[#94A3B8]' }}">
                                                    {{ $stepNum }}
                                                </div>
                                                <span class="mt-2 text-[10px] md:text-xs font-medium whitespace-nowrap {{ $stepNum <= $activeStep ? 'text-[#F8FAFC]' : 'text-[#94A3B8]' }}">{{ $label }}</span>
                                            </div>
                                            @unless($loop->last)
                                                <div class="flex-1 h-0.5 mx-1 -mt-5 {{ $stepNum < $activeStep ? 'bg-[#FF00FF]' : 'bg-[#334155]' }}"></div>
                                            @endunless
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="space-y-2 text-sm text-[#94A3B8]">
                                <div class="flex justify-between gap-4">
                                    <span class="text-[#94A3B8] shrink-0">Keluhan:</span>
                                    <span class="font-medium text-[#F8FAFC] text-right">{{ $order->keluhan_awal }}</span>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <span class="text-[#94A3B8] shrink-0">Mekanik:</span>
                                    <span class="font-medium text-[#F8FAFC] text-right">{{ $order->mechanic->full_name ?? 'Belum ditugaskan' }}</span>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <span class="text-[#94A3B8] shrink-0">Masuk:</span>
                                    <span class="font-medium text-[#F8FAFC] text-right">{{ optional($order->tanggal_masuk)->format('d M Y, H:i') ?? '-' }}</span>
                                </div>
                                @if($order->status === 'selesai')
                                <div class="flex justify-between gap-4">
                                    <span class="text-[#94A3B8] shrink-0">Pembayaran:</span>
                                    <span class="font-semibold text-right {{ ($order->invoice->status ?? null) === 'paid' ? 'text-emerald-300' : 'text-amber-300' }}">
                                        {{ ($order->invoice->status ?? null) === 'paid' ? 'Lunas' : 'Belum Dibayar' }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="bg-[#1E293B] p-6 rounded-2xl border border-[#334155] shadow-lg shadow-black/30 text-sm text-[#94A3B8] text-center">
                            Belum ada riwayat servis untuk kendaraan ini.
                        </div>
                    @endforelse
                </div>
            @else
                <div class="w-full max-w-xl mt-8 text-left bg-[#1E293B] p-6 rounded-2xl border border-red-400/40 shadow-lg shadow-black/30">
                    <h3 class="text-lg font-bold text-[#F8FAFC] mb-2">Kendaraan Tidak Ditemukan</h3>
                    <p class="text-sm text-[#94A3B8]">
                        Tidak ada kendaraan dengan nomor polisi
                        <span class="font-semibold text-[#F8FAFC]">{{ strtoupper(trim(request('plat_nomor'))) }}</span>.
                        Pastikan penulisan nomor polisi sudah benar.
                    </p>
                </div>
            @endif
        @endif

    </main>

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
