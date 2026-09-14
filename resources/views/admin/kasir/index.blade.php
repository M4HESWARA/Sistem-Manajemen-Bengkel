@extends('layouts.admin')

@section('title', 'Kasir & Pembayaran')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="mb-3">
    <h1 class="h4 fw-bold mb-0">Kasir & Pembayaran</h1>
</div>

<div class="row g-3">
    {{-- Antrian Pembayaran --}}
    <div class="col-lg-5">
        <div class="card p-3">
            <h2 class="h6 fw-bold mb-3">Antrian Pembayaran</h2>

            @forelse ($antrianSiapBayar as $order)
                <a href="{{ route('admin.kasir', ['order' => $order->id]) }}"
                   class="d-block text-decoration-none text-reset border rounded-3 p-3 mb-2 {{ $selectedOrder && $selectedOrder->id === $order->id ? 'border-2' : '' }}"
                   style="{{ $selectedOrder && $selectedOrder->id === $order->id ? 'border-color: var(--bengkel-accent) !important; background:#fdf2ff;' : '' }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold">{{ $order->vehicle->plat_nomor }}</div>
                            <div class="text-muted small">{{ $order->customer->full_name }}</div>
                            <div class="text-muted small">
                                <i class="bi bi-clock"></i> Selesai {{ $order->tanggal_selesai?->format('H:i') }}
                            </div>
                        </div>
                        <span class="badge rounded-pill" style="background: var(--bengkel-accent); color:#fff;">Siap Bayar</span>
                    </div>
                </a>
            @empty
                <p class="text-muted small">Belum ada kendaraan yang siap dibayar.</p>
            @endforelse

            @forelse ($antrianProses as $order)
                <div class="border rounded-3 p-3 mb-2 opacity-75">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold">{{ $order->vehicle->plat_nomor }}</div>
                            <div class="text-muted small">{{ $order->customer->full_name }}</div>
                            <div class="text-muted small">
                                <i class="bi bi-clock"></i> Estimasi {{ $order->tanggal_masuk?->addHours(2)->format('H:i') }}
                            </div>
                        </div>
                        <span class="badge rounded-pill bg-secondary-subtle text-secondary">Proses Pengecekan</span>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>

    {{-- Ringkasan Tagihan --}}
    <div class="col-lg-7">
        <div class="card p-4">
            @if ($selectedOrder)
                <h2 class="h6 fw-bold mb-1">Ringkasan Tagihan</h2>
                <p class="text-muted small mb-3">
                    Order {{ $selectedOrder->order_number }} &bull; {{ $selectedOrder->vehicle->plat_nomor }}
                </p>

                @if ($selectedOrder->jasaItems->isNotEmpty())
                    <p class="small fw-bold text-uppercase mb-1" style="color: var(--bengkel-primary); letter-spacing:.03em;">Jasa Servis</p>
                    @foreach ($selectedOrder->jasaItems as $jasa)
                        <div class="d-flex justify-content-between small mb-1">
                            <span>{{ $jasa->deskripsi }}</span>
                            <span>Rp {{ number_format($jasa->biaya, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                @endif

                @if ($selectedOrder->items->isNotEmpty())
                    <p class="small fw-bold text-uppercase mb-1 mt-3" style="color: var(--bengkel-primary); letter-spacing:.03em;">Suku Cadang</p>
                    @foreach ($selectedOrder->items as $item)
                        <div class="d-flex justify-content-between small mb-1">
                            <span>{{ $item->sparepart->name }}</span>
                            <span>Rp {{ number_format($item->quantity * $item->harga_satuan, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                @endif

                @if ($selectedOrder->jasaItems->isEmpty() && $selectedOrder->items->isEmpty())
                    <p class="text-muted small">Belum ada rincian jasa atau sparepart tercatat untuk order ini.</p>
                @endif

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold">Total Tagihan</span>
                    <span class="fs-4 fw-bold" style="color: var(--bengkel-accent);">
                        Rp {{ number_format($selectedOrder->totalJasa() + $selectedOrder->totalSparepart(), 0, ',', '.') }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.kasir.bayar', $selectedOrder) }}">
                    @csrf
                    <label class="form-label small fw-semibold">Metode Pembayaran</label>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="payment_method" id="pm_cash" value="cash" required>
                            <label class="btn btn-outline-secondary w-100 py-2 payment-toggle" for="pm_cash">
                                <i class="bi bi-cash"></i> Tunai
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="payment_method" id="pm_qris" value="qris" required>
                            <label class="btn btn-outline-secondary w-100 py-2 payment-toggle" for="pm_qris">
                                <i class="bi bi-qr-code"></i> QRIS
                            </label>
                        </div>
                    </div>

                    <div id="qrisPreview" class="d-none text-center border rounded-3 p-3 mb-3 bg-light">
                        <div id="qrisCanvas" class="d-flex justify-content-center mb-2"></div>
                        <p class="small text-muted mb-1">Contoh tampilan QR — belum terhubung payment gateway sungguhan</p>
                        <p class="fw-bold mb-0">
                            Rp {{ number_format($selectedOrder->totalJasa() + $selectedOrder->totalSparepart(), 0, ',', '.') }}
                        </p>
                    </div>

                    <details class="mb-3">
                        <summary class="small text-muted" style="cursor:pointer;">Metode pembayaran lain</summary>
                        <div class="row g-2 mt-1">
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="payment_method" id="pm_debit" value="debit">
                                <label class="btn btn-outline-secondary btn-sm w-100 payment-toggle" for="pm_debit">Debit</label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="payment_method" id="pm_credit" value="credit">
                                <label class="btn btn-outline-secondary btn-sm w-100 payment-toggle" for="pm_credit">Kredit</label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="payment_method" id="pm_transfer" value="transfer">
                                <label class="btn btn-outline-secondary btn-sm w-100 payment-toggle" for="pm_transfer">Transfer</label>
                            </div>
                        </div>
                    </details>

                    <button type="submit" class="btn btn-bengkel w-100 py-2">
                        <i class="bi bi-printer"></i> Proses Pembayaran & Cetak Nota
                    </button>
                </form>
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-receipt fs-1"></i>
                    <p class="mt-2 mb-0">Belum ada kendaraan yang siap dibayar.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .payment-toggle { border-radius: .6rem; }
    .btn-check:checked + .payment-toggle {
        background: linear-gradient(135deg, var(--bengkel-primary), var(--bengkel-accent));
        color: #fff;
        border-color: transparent;
    }
</style>
@endpush

@if ($selectedOrder)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    document.querySelectorAll('input[name="payment_method"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            const preview = document.getElementById('qrisPreview');
            const canvasEl = document.getElementById('qrisCanvas');

            if (this.value === 'qris' && this.checked) {
                canvasEl.innerHTML = '';
                new QRCode(canvasEl, {
                    text: 'QRIS-DEMO|{{ $selectedOrder->order_number }}|Rp{{ $selectedOrder->totalJasa() + $selectedOrder->totalSparepart() }}',
                    width: 160,
                    height: 160,
                });
                preview.classList.remove('d-none');
            } else {
                preview.classList.add('d-none');
            }
        });
    });
</script>
@endpush
@endif
