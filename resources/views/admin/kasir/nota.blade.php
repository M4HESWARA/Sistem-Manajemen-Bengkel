<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota {{ $serviceOrder->invoice->invoice_number }} - Auto Bengkel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --bengkel-primary: #a31d99; --bengkel-accent: #BF24B4; }
        body { background: #f5f6fa; font-family: 'Segoe UI', system-ui, sans-serif; }
        .nota-wrapper { max-width: 480px; margin: 2rem auto; }
        .nota-card { background: #fff; border-radius: .75rem; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,.06); }
        .nota-header img { height: 48px; }
        .dashed { border-top: 1px dashed #ccc; margin: 1rem 0; }
        .toolbar { max-width: 480px; margin: 1rem auto 0; }

        @media print {
            body { background: #fff; }
            .toolbar, .no-print { display: none !important; }
            .nota-wrapper { margin: 0 auto; max-width: 100%; }
            .nota-card { box-shadow: none; }
        }
    </style>
</head>
<body>

    <div class="toolbar d-flex justify-content-between no-print">
        <a href="{{ route('admin.kasir') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Kasir
        </a>
        <button onclick="window.print()" class="btn btn-bengkel btn-sm" style="background: #BF24B4; color: #fff; border:none;">
            <i class="bi bi-printer"></i> Cetak Nota
        </button>
    </div>

    <div class="nota-wrapper">
        <div class="nota-card">
            <div class="text-center nota-header mb-3">
                <img src="{{ asset('images/logo-ksr-garage.png') }}" alt="KSR Garage">
                <p class="text-muted small mb-0 mt-1">Sistem Manajemen Bengkel</p>
            </div>

            <div class="dashed"></div>

            <div class="d-flex justify-content-between small">
                <span class="text-muted">No. Nota</span>
                <span class="fw-semibold">{{ $serviceOrder->invoice->invoice_number }}</span>
            </div>
            <div class="d-flex justify-content-between small">
                <span class="text-muted">No. Order</span>
                <span class="fw-semibold">{{ $serviceOrder->order_number }}</span>
            </div>
            <div class="d-flex justify-content-between small">
                <span class="text-muted">Tanggal Bayar</span>
                <span class="fw-semibold">{{ $serviceOrder->invoice->paid_at?->format('d M Y, H:i') }}</span>
            </div>

            <div class="dashed"></div>

            <div class="d-flex justify-content-between small">
                <span class="text-muted">Pelanggan</span>
                <span class="fw-semibold">{{ $serviceOrder->customer->full_name }}</span>
            </div>
            <div class="d-flex justify-content-between small">
                <span class="text-muted">Kendaraan</span>
                <span class="fw-semibold">{{ $serviceOrder->vehicle->brand }} {{ $serviceOrder->vehicle->model }}</span>
            </div>
            <div class="d-flex justify-content-between small">
                <span class="text-muted">Nomor Polisi</span>
                <span class="fw-semibold">{{ $serviceOrder->vehicle->plat_nomor }}</span>
            </div>
            <div class="d-flex justify-content-between small">
                <span class="text-muted">Mekanik</span>
                <span class="fw-semibold">{{ $serviceOrder->mechanic->full_name ?? '-' }}</span>
            </div>

            <div class="dashed"></div>

            @if ($serviceOrder->jasaItems->isNotEmpty())
                <p class="small fw-semibold mb-1">Jasa Servis</p>
                @foreach ($serviceOrder->jasaItems as $jasa)
                    <div class="d-flex justify-content-between small mb-1">
                        <span>{{ $jasa->deskripsi }}</span>
                        <span>Rp {{ number_format($jasa->biaya, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            @endif

            @if ($serviceOrder->items->isNotEmpty())
                <p class="small fw-semibold mb-1 mt-2">Sparepart</p>
                @foreach ($serviceOrder->items as $item)
                    <div class="d-flex justify-content-between small mb-1">
                        <span>{{ $item->sparepart->name }} &times;{{ $item->quantity }}</span>
                        <span>Rp {{ number_format($item->quantity * $item->harga_satuan, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            @endif

            <div class="dashed"></div>

            <div class="d-flex justify-content-between small">
                <span class="text-muted">Total Jasa</span>
                <span>Rp {{ number_format($serviceOrder->invoice->total_jasa, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between small mb-2">
                <span class="text-muted">Total Sparepart</span>
                <span>Rp {{ number_format($serviceOrder->invoice->total_sparepart, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between fs-5 fw-bold">
                <span>TOTAL BAYAR</span>
                <span>Rp {{ number_format($serviceOrder->invoice->total_tagihan, 0, ',', '.') }}</span>
            </div>

            <div class="d-flex justify-content-between small mt-2">
                <span class="text-muted">Metode Bayar</span>
                <span class="fw-semibold text-uppercase">{{ $serviceOrder->invoice->payment_method }}</span>
            </div>

            <div class="dashed"></div>

            <p class="text-center text-muted small mb-0">Terima kasih telah servis di KSR Garage 🙏</p>
        </div>
    </div>

</body>
</html>
