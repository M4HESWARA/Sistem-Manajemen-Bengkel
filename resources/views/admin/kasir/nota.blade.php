<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota {{ $serviceOrder->invoice->invoice_number }} - KSR Garage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --page-bg: #121212;
            --card-bg: #FFFFFF;
            --input-bg: #0F172A;
            --panel-bg: #1E293B;
            --border: #334155;
            --text-primary: #F8FAFC;
            --text-secondary: #94A3B8;
            --magenta: #FF00FF;
        }

        html, body {
            min-height: 100%;
        }

        body {
            margin: 0;
            background: var(--page-bg) !important;
            color: var(--text-primary);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .nota-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem 1rem;
            background: var(--page-bg);
        }

        .nota-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            max-width: 32rem;
            margin-bottom: 1rem;
        }

        .btn-kembali {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background-color: var(--panel-bg);
            border: 1px solid var(--border);
            border-radius: 0.6rem;
            color: #E2E8F0;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            line-height: 1.4;
            padding: 0.5rem 1rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-kembali:hover,
        .btn-kembali:focus {
            background-color: var(--panel-bg);
            border-color: var(--magenta);
            color: var(--magenta);
            box-shadow: 0 0 18px rgba(255, 0, 255, 0.35);
            text-decoration: none;
        }

        .btn-cetak {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background-color: var(--magenta);
            border: none;
            border-radius: 0.6rem;
            color: #FFFFFF;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 700;
            line-height: 1.4;
            padding: 0.5rem 1.1rem;
            box-shadow: 0 0 20px rgba(255, 0, 255, 0.45);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-cetak:hover,
        .btn-cetak:focus {
            background-color: #E600E6;
            color: #FFFFFF;
            box-shadow: 0 0 28px rgba(255, 0, 255, 0.65);
        }

        .nota-wrapper {
            width: 100%;
            max-width: 32rem;
            margin: 0 auto;
        }

        .nota-card {
            background: var(--card-bg) !important;
            color: #000000;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.75);
        }

        .nota-header img {
            height: 48px;
        }

        .dashed {
            border-top: 1px dashed #CBD5E1;
            margin: 1rem 0;
        }

        @media print {
            @page {
                margin: 10mm;
            }

            html,
            body {
                background: #FFFFFF !important;
                color: #000000 !important;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print,
            .nota-toolbar,
            .toolbar,
            .print\:hidden {
                display: none !important;
            }

            .nota-page {
                display: block;
                min-height: auto;
                padding: 0;
                background: #FFFFFF !important;
            }

            .nota-wrapper {
                max-width: 100%;
                margin: 0 auto;
            }

            .nota-card {
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="nota-page">
        <div class="nota-toolbar no-print print:hidden">
            <a href="{{ route('admin.kasir') }}" class="btn-kembali">
                <i class="bi bi-arrow-left"></i> Kembali ke Kasir
            </a>
            <button onclick="window.print()" class="btn-cetak">
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
    </div>

</body>
</html>
