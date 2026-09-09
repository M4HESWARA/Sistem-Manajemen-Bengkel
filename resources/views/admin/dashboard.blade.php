@extends('layouts.admin')

@section('title', 'Dashboard Operasional')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <h1 class="h4 fw-bold mb-0">Dashboard Operasional</h1>

    <div class="d-flex align-items-center gap-2">
        <form method="GET" class="d-flex align-items-center m-0">
            <input type="date" name="date" class="form-control form-control-sm"
                   value="{{ $date->format('Y-m-d') }}" onchange="this.form.submit()">
        </form>
        <a href="{{ route('admin.pendaftaran') }}" class="btn btn-bengkel btn-sm px-3">
            <i class="bi bi-plus-lg"></i> Registrasi Servis Baru
        </a>
    </div>
</div>

{{-- Kartu Statistik --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Total Motor Selesai</p>
                    <h4 class="fw-bold mb-0">{{ $totalMotorSelesai }}</h4>
                </div>
                <div class="d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;border-radius:.75rem;background:linear-gradient(135deg,var(--bengkel-primary),var(--bengkel-accent));">
                    <i class="bi bi-scooter text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Pendapatan Hari Ini</p>
                    <h4 class="fw-bold mb-0">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h4>
                </div>
                <div class="d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;border-radius:.75rem;background:linear-gradient(135deg,var(--bengkel-primary),var(--bengkel-accent));">
                    <i class="bi bi-cash-coin text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Skor Efisiensi</p>
                    <h4 class="fw-bold mb-0">{{ $skorEfisiensi }}%</h4>
                </div>
                <div class="d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;border-radius:.75rem;background:linear-gradient(135deg,var(--bengkel-primary),var(--bengkel-accent));">
                    <i class="bi bi-speedometer2 text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Status Antrean Servis --}}
    <div class="col-lg-8">
        <div class="card p-3">
            <h2 class="h6 fw-bold mb-3">Status Antrean Servis</h2>

            <ul class="nav nav-tabs" id="antrianTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-menunggu" type="button">
                        Menunggu Mekanik
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-proses" type="button">
                        Proses Servis
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-siap-bayar" type="button">
                        Siap Bayar
                    </button>
                </li>
            </ul>

            <div class="tab-content pt-3">
                {{-- Tab: Menunggu Mekanik --}}
                <div class="tab-pane fade show active" id="tab-menunggu">
                    @forelse ($antrianMenunggu as $order)
                        <div class="d-flex flex-wrap justify-content-between align-items-center border rounded-3 p-3 mb-2 gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center"
                                     style="width:40px;height:40px;border-radius:.6rem;background:#f3f4f6;">
                                    <i class="bi bi-scooter fs-5" style="color: var(--bengkel-primary);"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">
                                        {{ $order->vehicle->brand }} {{ $order->vehicle->model }}
                                        — {{ $order->vehicle->plat_nomor }}
                                    </div>
                                    <div class="text-muted small">
                                        <span style="text-dark fw-bold;">Keluhan:</span>
                                        {{ $order->keluhan_awal }}
                                    </div>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('admin.service-orders.assign-mechanic', $order) }}"
                                  class="d-flex align-items-center gap-2">
                                @csrf
                                <select name="mechanic_id" class="form-select form-select-sm" required style="min-width:150px;">
                                    <option value="" disabled selected>Pilih mekanik</option>
                                    @foreach ($mekanikList as $mekanik)
                                        <option value="{{ $mekanik->id }}">{{ $mekanik->full_name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-outline-dark btn-sm">Assign Mekanik</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-muted small mb-0 py-3">Tidak ada kendaraan yang menunggu mekanik saat ini.</p>
                    @endforelse
                </div>

                {{-- Tab: Proses Servis --}}
                <div class="tab-pane fade" id="tab-proses">
                    @forelse ($antrianProses as $order)
                        <div class="d-flex flex-wrap justify-content-between align-items-center border rounded-3 p-3 mb-2 gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center"
                                     style="width:40px;height:40px;border-radius:.6rem;background:#f3f4f6;">
                                    <i class="bi bi-wrench-adjustable fs-5" style="color: var(--bengkel-primary);"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">
                                        {{ $order->vehicle->brand }} {{ $order->vehicle->model }}
                                        — {{ $order->vehicle->plat_nomor }}
                                    </div>
                                    <div class="text-muted small">
                                        Mekanik: {{ $order->mechanic->full_name ?? '-' }} · Status: {{ str_replace('_', ' ', ucfirst($order->status)) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0 py-3">Tidak ada kendaraan yang sedang dikerjakan saat ini.</p>
                    @endforelse
                </div>

                {{-- Tab: Siap Bayar --}}
                <div class="tab-pane fade" id="tab-siap-bayar">
                    @forelse ($antrianSiapBayar as $order)
                        <div class="d-flex flex-wrap justify-content-between align-items-center border rounded-3 p-3 mb-2 gap-2">
                            <div>
                                <div class="fw-semibold">
                                    {{ $order->vehicle->brand }} {{ $order->vehicle->model }}
                                    — {{ $order->vehicle->plat_nomor }}
                                </div>
                                <div class="text-muted small">
                                    Total: Rp {{ number_format($order->totalJasa() + $order->totalSparepart(), 0, ',', '.') }}
                                </div>
                            </div>
                            <a href="{{ route('admin.kasir') }}" class="btn btn-bengkel btn-sm">
                                <i class="bi bi-receipt"></i> Proses Pembayaran & Cetak Nota
                            </a>
                        </div>
                    @empty
                        <p class="text-muted small mb-0 py-3">Belum ada kendaraan yang siap dibayar.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

{{-- Peringatan Stok Tipis --}}
<div class="col-lg-4">
    <div class="card overflow-hidden">
        {{-- Header dengan background merah muda khusus --}}
        <div class="p-3" style="background:#fee2e2;">
            <h2 class="h6 fw-bold mb-0 text-danger">
                <i class="bi bi-exclamation-triangle-fill"></i> Peringatan Stok Tipis
            </h2>
        </div>

        <div class="p-3">
            @php
                $badgeStyles = [
                    'background:#fce7f3;color:#db2777;',
                    'background:#ffedd5;color:#c2410c;',
                    'background:#fef9c3;color:#a16207;',
                ];
            @endphp
            @forelse ($lowStockItems as $item)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <div class="fw-semibold small">{{ $item->name }}</div>
                        <div class="text-muted" style="font-size:.75rem;">Kategori: {{ $item->category->name ?? '-' }}</div>
                    </div>
                    {{-- Mengubah label satuan dinamis menjadi teks "Item" agar seragam --}}
                    <span class="badge" style="{{ $badgeStyles[$loop->index % 3] }}">Sisa {{ $item->stok }} Item</span>
                </div>
            @empty
                <p class="text-muted small mb-0 py-2">Semua stok sparepart dalam kondisi aman.</p>
            @endforelse
        </div>

        {{-- Footer link Lihat Semua Inventori dibungkus border atas --}}
        <div class="border-top p-2 text-center bg-white">
            <a href="{{ route('admin.inventori') }}" class="small fw-semibold link-inventori text-decoration-none">
                Lihat Semua Inventori
            </a>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .nav-tabs .nav-link { color: #6b7280; font-weight: 600; font-size: .85rem; border: none; }
    .nav-tabs .nav-link.active { color: var(--bengkel-primary); border-bottom: 2px solid var(--bengkel-primary); }
    
    /* Tambahan CSS baru untuk tautan inventori */
    .link-inventori {
        color: #212529; /* Warna hitam (default text dark Bootstrap) */
        transition: color 0.2s ease-in-out; /* Efek transisi halus */
    }
    .link-inventori:hover {
        color: var(--bengkel-accent); /* Berubah menjadi magenta saat di-hover */
    }
</style>
@endpush

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
