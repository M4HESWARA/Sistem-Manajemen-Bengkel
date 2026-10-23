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

<div class="mb-4">
    <h1 class="h4 fw-bold mb-0" style="color: #F8FAFC;">Kasir &amp; Pembayaran</h1>
    <p class="small mb-0" style="color: #94A3B8;">Pilih antrian kendaraan, konfirmasi pembayaran, lalu cetak nota.</p>
</div>

@php $totalTagihan = $selectedOrder ? ($selectedOrder->totalJasa() + $selectedOrder->totalSparepart()) : 0; @endphp

<div class="row g-3">
    {{-- Antrian Pembayaran --}}
    <div class="col-lg-5">
        <div class="card p-3">
            <h2 class="h6 fw-bold mb-3" style="color: #F8FAFC;">Antrian Pembayaran</h2>

            {{-- Pencarian cepat --}}
            <div class="card p-1 mb-3" style="background: #0F172A; border-color: #334155;">
                <div class="d-flex align-items-center w-100">
                    <span class="px-3"><i class="bi bi-search" style="color: #94A3B8;"></i></span>
                    <input type="text" id="kasirSearch" class="form-control border-0 shadow-none"
                           placeholder="Cari No. Plat / Nama..." autocomplete="off">
                </div>
            </div>

            <div id="kasirQueue">
                @forelse ($antrianSiapBayar as $order)
                    <a href="{{ route('admin.kasir', ['order' => $order->id]) }}"
                       data-search="{{ strtolower($order->vehicle->plat_nomor . ' ' . ($order->customer->full_name ?? '')) }}"
                       class="d-block text-decoration-none border rounded-3 p-3 mb-2 queue-item {{ $selectedOrder && $selectedOrder->id === $order->id ? 'queue-selected' : '' }}"
                       style="{{ $selectedOrder && $selectedOrder->id === $order->id ? 'border: 2px solid #FF00FF !important; box-shadow: 0 0 15px rgba(255, 0, 255, 0.25);' : 'border-color: #334155 !important;' }}">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="fw-bold" style="color: #F8FAFC;">{{ $order->vehicle->plat_nomor }}</div>
                                <div class="small" style="color: #94A3B8;">{{ $order->customer->full_name }}</div>
                                <div class="small" style="color: #94A3B8;">
                                    <i class="bi bi-clock"></i> Selesai {{ $order->tanggal_selesai?->format('H:i') }}
                                </div>
                            </div>
                            <span class="badge rounded-pill" style="background: #FF00FF; color: #fff; box-shadow: 0 0 10px rgba(255, 0, 255, 0.5);">Siap Bayar</span>
                        </div>
                    </a>
                @empty
                    <p class="small">Belum ada kendaraan yang siap dibayar.</p>
                @endforelse

                @forelse ($antrianProses as $order)
                    <div class="border rounded-3 p-3 mb-2 opacity-75 queue-item"
                         data-search="{{ strtolower($order->vehicle->plat_nomor . ' ' . ($order->customer->full_name ?? '')) }}"
                         style="border-color: #334155 !important;">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="fw-bold" style="color: #F8FAFC;">{{ $order->vehicle->plat_nomor }}</div>
                                <div class="small" style="color: #94A3B8;">{{ $order->customer->full_name }}</div>
                                <div class="small" style="color: #94A3B8;">
                                    <i class="bi bi-clock"></i> Estimasi {{ $order->tanggal_masuk?->addHours(2)->format('H:i') }}
                                </div>
                            </div>
                            <span class="badge rounded-pill" style="background: #334155; color: #94A3B8;">Proses Pengecekan</span>
                        </div>
                    </div>
                @empty
                @endforelse

                <p id="kasirEmpty" class="small d-none" style="color: #94A3B8;">Tidak ada antrian yang cocok dengan pencarian.</p>
            </div>
        </div>
    </div>

    {{-- Ringkasan Tagihan --}}
    <div class="col-lg-7">
        <div class="card p-4">
            @if ($selectedOrder)
                <h2 class="h6 fw-bold mb-1" style="color: #F8FAFC;">Ringkasan Tagihan</h2>
                <p class="small mb-3" style="color: #94A3B8;">
                    Order {{ $selectedOrder->order_number }} &bull; {{ $selectedOrder->vehicle->plat_nomor }}
                </p>

                @if ($selectedOrder->jasaItems->isNotEmpty())
                    <p class="small fw-bold text-uppercase mb-1" style="color: #FF00FF; letter-spacing:.03em;">Jasa Servis</p>
                    @foreach ($selectedOrder->jasaItems as $jasa)
                        <div class="d-flex justify-content-between small mb-1" style="color: #94A3B8;">
                            <span style="color: #F8FAFC;">{{ $jasa->deskripsi }}</span>
                            <span>Rp {{ number_format($jasa->biaya, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                @endif

                @if ($selectedOrder->items->isNotEmpty())
                    <p class="small fw-bold text-uppercase mb-1 mt-3" style="color: #FF00FF; letter-spacing:.03em;">Suku Cadang</p>
                    @foreach ($selectedOrder->items as $item)
                        <div class="d-flex justify-content-between small mb-1" style="color: #94A3B8;">
                            <span style="color: #F8FAFC;">{{ $item->sparepart->name }} <span style="color: #94A3B8;">×{{ $item->quantity }}</span></span>
                            <span>Rp {{ number_format($item->quantity * $item->harga_satuan, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                @endif

                @if ($selectedOrder->jasaItems->isEmpty() && $selectedOrder->items->isEmpty())
                    <p class="small" style="color: #94A3B8;">Belum ada rincian jasa atau sparepart tercatat untuk order ini.</p>
                @endif

                <hr style="border-color: #334155;">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold" style="color: #F8FAFC;">Total Tagihan</span>
                    <span class="fs-3 fw-bold" style="color: #FF00FF; text-shadow: 0 0 18px rgba(255, 0, 255, 0.55);">
                        Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.kasir.bayar', $selectedOrder) }}">
                    @csrf
                    <label class="form-label small fw-semibold" style="color: #F8FAFC;">Metode Pembayaran</label>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="payment_method" id="pm_cash" value="cash" required checked>
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

                    {{-- Input khusus Tunai: uang diterima & kembalian --}}
                    <div id="cashSection" class="rounded-3 p-3 mb-3" style="background: #0F172A; border: 1px solid #334155;">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-7">
                                <label class="form-label small fw-semibold mb-1" style="color: #F8FAFC;">Uang Diterima</label>
                                <input type="number" id="uangDiterima" class="form-control" min="0" step="1000"
                                       placeholder="Rp {{ number_format($totalTagihan, 0, ',', '.') }}">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small fw-semibold mb-1" style="color: #94A3B8;">Uang Kembalian</label>
                                <div class="fw-bold fs-6" id="uangKembalian" style="color: #F8FAFC;">Rp 0</div>
                            </div>
                        </div>
                        <p class="small mb-0 mt-2" id="cashHint" style="color: #94A3B8;">Uang diterima akan otomatis dihitung kembaliannya.</p>
                    </div>

                    <div id="qrisPreview" class="d-none text-center border rounded-3 p-3 mb-3" style="background: #0F172A; border-color: #334155 !important;">
                        <div id="qrisCanvas" class="d-flex justify-content-center mb-2"></div>
                        <p class="small mb-1" style="color: #94A3B8;">Contoh tampilan QR — belum terhubung payment gateway sungguhan</p>
                        <p class="fw-bold mb-0" style="color: #FF00FF;">
                            Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                        </p>
                    </div>

                    <details class="mb-3">
                        <summary class="small" style="cursor:pointer; color: #94A3B8;">Metode pembayaran lain</summary>
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

                    <button type="submit" class="btn btn-magenta w-100 py-2" style="background-color: #FF00FF; border: none; color: #fff; font-weight: 700; box-shadow: 0 0 20px rgba(255, 0, 255, 0.45);">
                        <i class="bi bi-printer"></i> Proses Pembayaran &amp; Cetak Nota
                    </button>
                </form>
            @else
                <div class="text-center py-5" style="color: #94A3B8;">
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
        background: #FF00FF;
        color: #fff !important;
        border-color: #FF00FF;
        box-shadow: 0 0 15px rgba(255, 0, 255, 0.45);
    }
    .form-control, .form-select {
        background-color: #0F172A;
        border: 1px solid #334155;
        color: #F8FAFC;
    }
    .form-control::placeholder {
        color: #94A3B8;
    }
    .form-control:focus {
        background-color: #0F172A;
        border-color: #FF00FF;
        box-shadow: 0 0 0 .2rem rgba(255, 0, 255, .25);
        color: #F8FAFC;
    }
    #kasirSearch {
        background-color: #0F172A;
        border: 1px solid #334155;
        border-radius: 10px;
        color: #F8FAFC;
        padding: 0.55rem 0.8rem;
    }
    #kasirSearch:focus {
        background-color: #0F172A;
        border-color: #FF00FF;
        box-shadow: 0 0 0 .2rem rgba(255, 0, 255, .25);
        color: #F8FAFC;
    }
    input[type='number']::-webkit-inner-spin-button,
    input[type='number']::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type='number'] {
        -moz-appearance: textfield;
        appearance: textfield;
    }
</style>
@endpush

@push('scripts')
<script>
    // Pencarian cepat antrian kasir
    (function () {
        const input = document.getElementById('kasirSearch');
        const items = document.querySelectorAll('#kasirQueue .queue-item');
        const emptyState = document.getElementById('kasirEmpty');
        if (!input || !items.length) { return; }

        input.addEventListener('input', function () {
            const keyword = this.value.trim().toLowerCase();
            let visible = 0;

            items.forEach(function (item) {
                const haystack = (item.dataset.search || '').toLowerCase();
                const match = haystack.includes(keyword);
                item.classList.toggle('d-none', !match);
                if (match) { visible++; }
            });

            if (emptyState) { emptyState.classList.toggle('d-none', visible > 0); }
        });
    })();

    // Metode pembayaran & kalkulasi kembalian
    (function () {
        const cashSection = document.getElementById('cashSection');
        const cashInput = document.getElementById('uangDiterima');
        const changeEl = document.getElementById('uangKembalian');
        const hintEl = document.getElementById('cashHint');
        const total = {{ (int) $totalTagihan }};

        function formatRupiah(value) {
            return 'Rp ' + Math.round(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function isCash() {
            const checked = document.querySelector('input[name="payment_method"]:checked');
            return checked && checked.value === 'cash';
        }

        function updateKembalian() {
            if (!cashInput || !changeEl) { return; }
            const received = parseInt(cashInput.value, 10) || 0;
            const change = received - total;

            if (received === 0) {
                changeEl.textContent = 'Rp 0';
                changeEl.style.color = '#F8FAFC';
                if (hintEl) {
                    hintEl.textContent = 'Uang diterima akan otomatis dihitung kembaliannya.';
                    hintEl.style.color = '#94A3B8';
                }
                return;
            }

            if (change < 0) {
                changeEl.textContent = formatRupiah(change);
                changeEl.style.color = '#ef4444';
                if (hintEl) {
                    hintEl.textContent = 'Uang diterima belum mencukupi total tagihan.';
                    hintEl.style.color = '#ef4444';
                }
                return;
            }

            changeEl.textContent = formatRupiah(change);
            changeEl.style.color = '#FF00FF';
            if (hintEl) {
                hintEl.textContent = 'Kembalian siap diberikan kepada pelanggan.';
                hintEl.style.color = '#94A3B8';
            }
        }

        function togglePaymentSection() {
            if (!cashSection) { return; }
            cashSection.classList.toggle('d-none', !isCash());
            updateKembalian();
        }

        document.querySelectorAll('input[name="payment_method"]').forEach(function (radio) {
            radio.addEventListener('change', togglePaymentSection);
        });

        if (cashInput) { cashInput.addEventListener('input', updateKembalian); }

        togglePaymentSection();
    })();
</script>
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
                    text: 'QRIS-DEMO|{{ $selectedOrder->order_number }}|Rp{{ $totalTagihan }}',
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
