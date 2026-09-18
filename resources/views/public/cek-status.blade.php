@extends('layouts.public')

@section('title', 'Cek Status Servis - Auto Bengkel')

@section('content')

<section class="py-5" style="background: #fafafa; min-height: 70vh;">
    <div class="container" style="max-width: 640px;">
        <div class="text-center mb-4">
            <h1 class="fw-bold h3 mb-2">Cek Status Servis Kendaraan</h1>
            <p class="text-muted">Masukkan Nomor Polisi kendaraan Anda untuk melihat status dan riwayat servis.</p>
        </div>

        <form method="GET" class="d-flex gap-2 mb-4">
            <input type="text" name="plat_nomor" class="form-control form-control-lg text-uppercase"
                   placeholder="mis. B 1234 ABC" value="{{ request('plat_nomor') }}" required>
            <button type="submit" class="btn btn-bengkel btn-lg px-4">
                <i class="bi bi-search"></i> Cari
            </button>
        </form>

        @if ($searched)
            @if (! $vehicle)
                <div class="alert alert-warning text-center">
                    <i class="bi bi-exclamation-triangle"></i>
                    Kendaraan dengan nomor polisi tersebut tidak ditemukan dalam riwayat kami.
                </div>
            @else
                <div class="card p-3 mb-3" style="border:none; border-radius:.9rem; box-shadow:0 2px 10px rgba(0,0,0,.05);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold fs-5">{{ $vehicle->brand }} {{ $vehicle->model }}</div>
                            <div class="text-muted small">{{ $vehicle->plat_nomor }} &bull; {{ $vehicle->customer->full_name }}</div>
                        </div>
                        <i class="bi bi-scooter fs-2" style="color: var(--bengkel-accent);"></i>
                    </div>
                </div>

                @forelse ($serviceOrders as $order)
                    @php
                        $statusLabel = match ($order->status) {
                            'menunggu' => 'Menunggu Mekanik',
                            'proses_diagnosa' => 'Proses Diagnosa',
                            'sedang_diperbaiki' => 'Sedang Diperbaiki',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                            default => $order->status,
                        };
                        $statusStyle = match ($order->status) {
                            'selesai' => 'background:#dcfce7; color:#166534;',
                            'sedang_diperbaiki' => 'background:#dbeafe; color:#1e40af;',
                            'proses_diagnosa' => 'background:#fef3c7; color:#92400e;',
                            'dibatalkan' => 'background:#fee2e2; color:#991b1b;',
                            default => 'background:#f3f4f6; color:#374151;',
                        };
                    @endphp
                    <div class="card p-3 mb-2" style="border:none; border-radius:.9rem; box-shadow:0 2px 10px rgba(0,0,0,.04);">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="fw-semibold small text-muted">{{ $order->order_number }}</div>
                                <div class="small">{{ $order->tanggal_masuk?->format('d M Y, H:i') }}</div>
                            </div>
                            <span class="badge rounded-pill" style="{{ $statusStyle }} padding:.4rem .8rem;">{{ $statusLabel }}</span>
                        </div>
                        <div class="small text-muted mb-1"><strong>Keluhan:</strong> {{ $order->keluhan_awal }}</div>
                        @if ($order->catatan_solusi)
                            <div class="small text-muted mb-1"><strong>Solusi:</strong> {{ $order->catatan_solusi }}</div>
                        @endif
                        @if ($order->mechanic)
                            <div class="small text-muted mb-1"><strong>Mekanik:</strong> {{ $order->mechanic->full_name }}</div>
                        @endif
                        @if ($order->invoice)
                            <hr class="my-2">
                            <div class="d-flex justify-content-between align-items-center small">
                                <span class="text-muted">Total Tagihan</span>
                                <span class="fw-bold">Rp {{ number_format($order->invoice->total_tagihan, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center small">
                                <span class="text-muted">Status Pembayaran</span>
                                <span class="fw-semibold {{ $order->invoice->status === 'paid' ? 'text-success' : 'text-warning' }}">
                                    {{ $order->invoice->status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-muted">Belum ada riwayat servis untuk kendaraan ini.</p>
                @endforelse
            @endif
        @endif
    </div>
</section>

@endsection

@push('styles')
<style>.text-uppercase { text-transform: uppercase; }</style>
@endpush
