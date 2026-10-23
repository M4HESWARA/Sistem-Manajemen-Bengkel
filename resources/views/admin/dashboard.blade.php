@extends('layouts.admin')

@section('title', 'Dashboard Operasional')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <h1 class="h4 fw-bold mb-0" style="color: #F8FAFC;">Dashboard Operasional</h1>

    <div class="d-flex align-items-center gap-2">
        <input type="date" name="date" class="form-control form-control-sm" style="background: #1E293B; border: 1px solid #334155; color: #F8FAFC; width: auto;"
               value="{{ $date->format('Y-m-d') }}" onchange="this.form.submit()">
    </div>
</div>

{{-- Kartu Statistik --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="small mb-1" style="color: #94A3B8;">Total Motor Selesai</p>
                    <h4 class="fw-bold mb-0" style="color: #F8FAFC; font-size: 1.8rem;">{{ $totalMotorSelesai }}</h4>
                </div>
                <div class="d-flex align-items-center justify-content-center"
                     style="width:46px;height:46px;border-radius:.75rem;background:rgba(255, 0, 255, 0.15);">
                    <i class="bi bi-scooter fs-5" style="color: #FF00FF;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="small mb-1" style="color: #94A3B8;">Pendapatan Hari Ini</p>
                    <h4 class="fw-bold mb-0" style="color: #F8FAFC; font-size: 1.5rem;">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h4>
                </div>
                <div class="d-flex align-items-center justify-content-center"
                     style="width:46px;height:46px;border-radius:.75rem;background:rgba(255, 0, 255, 0.15);">
                    <i class="bi bi-cash-coin fs-5" style="color: #FF00FF;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="small mb-1" style="color: #94A3B8;">Skor Efisiensi</p>
                    <h4 class="fw-bold mb-0" style="color: #F8FAFC; font-size: 1.8rem;">{{ $skorEfisiensi }}%</h4>
                </div>
                <div class="d-flex align-items-center justify-content-center"
                     style="width:46px;height:46px;border-radius:.75rem;background:rgba(255, 0, 255, 0.15);">
                    <i class="bi bi-speedometer2 fs-5" style="color: #FF00FF;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Status Antrean Servis --}}
    <div class="col-lg-8">
        <div class="card p-3">
            <h2 class="h6 fw-bold mb-3" style="color: #F8FAFC;">Status Antrean Servis</h2>

            <ul class="nav nav-tabs border-0" id="antrianTab" role="tablist">
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
                        <div class="d-flex flex-wrap justify-content-between align-items-center border rounded-3 p-3 mb-2 gap-2" style="border-color: #334155 !important; background: transparent;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center"
                                     style="width:42px;height:42px;border-radius:.6rem;background:rgba(255, 0, 255, 0.12);">
                                    <i class="bi bi-scooter fs-5" style="color: #FF00FF;"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold" style="color: #F8FAFC;">
                                        {{ $order->vehicle->brand }} {{ $order->vehicle->model }}
                                        — {{ $order->vehicle->plat_nomor }}
                                    </div>
                                    <div class="small" style="color: #94A3B8;">
                                        <span style="color: #FF00FF;">Keluhan:</span>
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
                                <button type="submit" class="btn btn-magenta btn-sm">Assign Mekanik</button>
                            </form>
                        </div>
                    @empty
                        <p class="small mb-0 py-3" style="color: #94A3B8;">Tidak ada kendaraan yang menunggu mekanik saat ini.</p>
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
                            <a href="{{ route('admin.kasir', ['order' => $order->id]) }}" class="btn btn-bengkel btn-sm">
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
        <div class="card p-3" style="border-color: rgba(239, 68, 68, 0.4);">
            <h2 class="h6 fw-bold mb-3" style="color: #ef4444;">
                <i class="bi bi-exclamation-triangle-fill"></i> Peringatan Stok Tipis
            </h2>

            @php
                $badgeStyles = [
                    'background:rgba(239, 68, 68, 0.18);color:#f87171;border:1px solid rgba(239, 68, 68, 0.5);',
                    'background:rgba(249, 115, 22, 0.18);color:#fb923c;border:1px solid rgba(249, 115, 22, 0.5);',
                    'background:rgba(234, 179, 8, 0.18);color:#facc15;border:1px solid rgba(234, 179, 8, 0.5);',
                ];
            @endphp
            @forelse ($lowStockItems as $item)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: #334155 !important;">
                    <div>
                        <div class="fw-semibold small" style="color: #F8FAFC;">{{ $item->name }}</div>
                        <div style="font-size:.75rem; color: #94A3B8;">Kategori: {{ $item->category->name ?? '-' }}</div>
                    </div>
                    <span class="badge" style="{{ $badgeStyles[$loop->index % 3] }}">Sisa {{ $item->stok }} {{ $item->satuan }}</span>
                </div>
            @empty
                <p class="small mb-0 py-2" style="color: #94A3B8;">Semua stok sparepart dalam kondisi aman.</p>
            @endforelse

            <a href="{{ route('admin.inventori') }}" class="d-block text-center small fw-semibold mt-3"
               style="color: #FF00FF;">Lihat Semua Inventori</a>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .nav-tabs { border-bottom: 1px solid #334155; gap: 1.5rem; }
    .nav-tabs .nav-link { color: #94A3B8; font-weight: 500; font-size: .85rem; border: none; padding: 0.6rem 0; background: transparent; }
    .nav-tabs .nav-link:hover { color: #F8FAFC; }
    .nav-tabs .nav-link.active { color: #F8FAFC; background: transparent; border: none; border-bottom: 2px solid #FF00FF; border-radius: 0; font-weight: 600; }
    .form-select, .form-control { background-color: #1E293B; border: 1px solid #334155; color: #F8FAFC; }
    .form-select:focus, .form-control:focus { background-color: #1E293B; border-color: #FF00FF; box-shadow: 0 0 0 .2rem rgba(255, 0, 255, .25); color: #F8FAFC; }
</style>
@endpush
