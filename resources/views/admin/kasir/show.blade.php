@extends('layouts.admin')

@section('title', 'Proses Pembayaran')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="mb-4">
    <h1 class="h4 fw-bold mb-0">Proses Pembayaran</h1>
    <p class="text-muted small mb-0">{{ $serviceOrder->order_number }}</p>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        {{-- Info Kendaraan & Pelanggan --}}
        <div class="card p-4 mb-3">
            <h2 class="h6 fw-bold mb-3"><i class="bi bi-scooter"></i> Info Servis</h2>
            <div class="row row-cols-2 g-2 small">
                <div class="text-muted">Kendaraan</div>
                <div class="fw-semibold">{{ $serviceOrder->vehicle->brand }} {{ $serviceOrder->vehicle->model }} — {{ $serviceOrder->vehicle->plat_nomor }}</div>
                <div class="text-muted">Pelanggan</div>
                <div class="fw-semibold">{{ $serviceOrder->customer->full_name }}</div>
                <div class="text-muted">Mekanik</div>
                <div class="fw-semibold">{{ $serviceOrder->mechanic->full_name ?? '-' }}</div>
                <div class="text-muted">Keluhan Awal</div>
                <div class="fw-semibold">{{ $serviceOrder->keluhan_awal }}</div>
                @if ($serviceOrder->catatan_solusi)
                    <div class="text-muted">Catatan Solusi</div>
                    <div class="fw-semibold">{{ $serviceOrder->catatan_solusi }}</div>
                @endif
            </div>
        </div>

        {{-- Rincian Biaya --}}
        <div class="card p-4">
            <h2 class="h6 fw-bold mb-3"><i class="bi bi-receipt"></i> Rincian Biaya</h2>

            @if ($serviceOrder->jasaItems->isEmpty() && $serviceOrder->items->isEmpty())
                <p class="text-muted small">Belum ada rincian jasa atau sparepart tercatat untuk order ini.</p>
            @endif

            @if ($serviceOrder->jasaItems->isNotEmpty())
                <p class="small fw-semibold text-muted mb-1">Biaya Jasa</p>
                <table class="table table-sm mb-3">
                    <tbody>
                        @foreach ($serviceOrder->jasaItems as $jasa)
                            <tr>
                                <td>{{ $jasa->deskripsi }}</td>
                                <td class="text-end">Rp {{ number_format($jasa->biaya, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if ($serviceOrder->items->isNotEmpty())
                <p class="small fw-semibold text-muted mb-1">Sparepart Terpakai</p>
                <table class="table table-sm mb-3">
                    <tbody>
                        @foreach ($serviceOrder->items as $item)
                            <tr>
                                <td>{{ $item->sparepart->name }} &times; {{ $item->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($item->quantity * $item->harga_satuan, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <hr>
            <div class="d-flex justify-content-between small">
                <span class="text-muted">Total Jasa</span>
                <span>Rp {{ number_format($serviceOrder->totalJasa(), 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between small mb-2">
                <span class="text-muted">Total Sparepart</span>
                <span>Rp {{ number_format($serviceOrder->totalSparepart(), 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between fs-5 fw-bold">
                <span>Total Tagihan</span>
                <span style="color: var(--bengkel-accent);">
                    Rp {{ number_format($serviceOrder->totalJasa() + $serviceOrder->totalSparepart(), 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Form Pembayaran --}}
    <div class="col-lg-5">
        <div class="card p-4">
            <h2 class="h6 fw-bold mb-3"><i class="bi bi-credit-card"></i> Konfirmasi Pembayaran</h2>

            <form method="POST" action="{{ route('admin.kasir.bayar', $serviceOrder) }}">
                @csrf
                <label class="form-label small fw-semibold">Metode Pembayaran</label>

                @foreach ([
                    'cash' => ['Tunai (Cash)', 'bi-cash'],
                    'debit' => ['Kartu Debit', 'bi-credit-card'],
                    'credit' => ['Kartu Kredit', 'bi-credit-card-2-front'],
                    'qris' => ['QRIS', 'bi-qr-code'],
                    'transfer' => ['Transfer Bank', 'bi-bank'],
                ] as $value => [$label, $icon])
                    <div class="form-check border rounded-3 p-2 mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" id="pm_{{ $value }}"
                               value="{{ $value }}" required>
                        <label class="form-check-label w-100" for="pm_{{ $value }}">
                            <i class="bi {{ $icon }}"></i> {{ $label }}
                        </label>
                    </div>
                @endforeach

                <button type="submit" class="btn btn-bengkel w-100 mt-3 py-2">
                    <i class="bi bi-check-circle"></i> Konfirmasi Pembayaran & Cetak Nota
                </button>
                <a href="{{ route('admin.kasir') }}" class="btn btn-outline-secondary w-100 mt-2">Batal</a>
            </form>
        </div>
    </div>
</div>

@endsection
