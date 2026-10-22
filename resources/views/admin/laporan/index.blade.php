@extends('layouts.admin')

@section('title', 'Laporan & Analitik')

@section('content')

@php
    // Daftar pilihan periode (12 bulan terakhir + bulan depan)
    $periodeOptions = [];
    for ($i = 11; $i >= 0; $i--) {
        $bulan = $month->copy()->startOfMonth()->subMonths($i);
        $periodeOptions[$bulan->format('Y-m')] = $bulan->translatedFormat('F Y');
    }
    $periodeAktif = $month->format('Y-m');
    $skorTertinggi = $kinerjaMekanik->max('skor_efisiensi');
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h4 fw-bold mb-0" style="color: #F8FAFC;">Laporan &amp; Analitik</h1>
        <p class="small mb-0" style="color: #94A3B8;">Rekap pendapatan, produktivitas, dan kinerja tim bengkel.</p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <form method="GET" class="d-flex align-items-center gap-2">
            <label for="filterPeriode" class="small mb-0" style="color: #94A3B8;">Periode:</label>
            <select id="filterPeriode" name="month" class="form-select form-select-sm" onchange="this.form.submit()"
                    style="background: #1E293B; border: 1px solid #334155; color: #F8FAFC; width: auto; min-width: 180px;">
                @foreach ($periodeOptions as $nilai => $label)
                    <option value="{{ $nilai }}" {{ $nilai === $periodeAktif ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

{{-- Kartu Ringkasan --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <p class="small mb-1" style="color: #94A3B8;">Total Pendapatan</p>
                    <h4 class="fw-bold mb-1" style="color: #FF00FF; text-shadow: 0 0 18px rgba(255, 0, 255, 0.45);">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </h4>
                    <p class="small mb-0" style="color: #4ade80;">
                        <i class="bi bi-arrow-{{ $persenPerubahanPendapatan >= 0 ? 'up' : 'down' }}-short"></i>
                        {{ $persenPerubahanPendapatan >= 0 ? '+' : '-' }}{{ abs($persenPerubahanPendapatan) }}% dari bulan lalu
                    </p>
                </div>
                <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:46px;height:46px;border-radius:.75rem;background:rgba(255, 0, 255, 0.15);">
                    <i class="bi bi-cash-coin fs-5" style="color: #FF00FF;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <p class="small mb-1" style="color: #94A3B8;">Total Motor Selesai</p>
                    <h4 class="fw-bold mb-1" style="color: #F8FAFC;">{{ $totalMotorServis }} Motor</h4>
                    <p class="small mb-0" style="color: #94A3B8;">Rata-rata {{ $rataRataPerHari }} motor/hari</p>
                </div>
                <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:46px;height:46px;border-radius:.75rem;background:rgba(255, 0, 255, 0.15);">
                    <i class="bi bi-scooter fs-5" style="color: #FF00FF;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <p class="small mb-1" style="color: #94A3B8;">Efisiensi Rata-rata</p>
                    <h4 class="fw-bold mb-1" style="color: #F8FAFC;">{{ $efisiensiRataRata }}%</h4>
                    <p class="small mb-0" style="color: #94A3B8;">Target: &gt;80%</p>
                </div>
                <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:46px;height:46px;border-radius:.75rem;background:rgba(255, 0, 255, 0.15);">
                    <i class="bi bi-speedometer2 fs-5" style="color: #FF00FF;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Grafik Pendapatan --}}
<div class="card p-4 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h6 fw-bold mb-0" style="color: #F8FAFC;">Grafik Pendapatan Bulanan</h2>
        <span class="small" style="color: #94A3B8;">6 bulan terakhir</span>
    </div>
    <div style="position: relative; height: 320px;">
        <canvas id="grafikPendapatan"></canvas>
    </div>
</div>

{{-- Analitik Kinerja Mekanik --}}
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h6 fw-bold mb-0" style="color: #F8FAFC;">Analitik Kinerja Mekanik</h2>
        <a href="{{ route('admin.riwayat') }}" class="small fw-semibold text-decoration-none link-laporan"
           style="color: #FF00FF; transition: text-shadow .2s ease;">
            Lihat Semua <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead style="background: rgba(255, 255, 255, 0.02);">
                <tr>
                    <th style="color: #94A3B8;">Nama Mekanik</th>
                    <th style="color: #94A3B8;">Total Servis</th>
                    <th style="color: #94A3B8;">Rata-rata Waktu/Motor</th>
                    <th style="min-width:180px; color: #94A3B8;">Skor Efisiensi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kinerjaMekanik as $i => $m)
                    @php
                        $isTop = $skorTertinggi > 0 && $m['skor_efisiensi'] === $skorTertinggi;
                        $barWarna = $isTop ? '#FF00FF' : '#22d3ee';
                        $barGlow = $isTop ? '0 0 12px rgba(255, 0, 255, 0.7)' : 'none';
                    @endphp
                    <tr>
                        <td class="fw-semibold" style="color: #F8FAFC;">
                            <span class="badge rounded-circle me-1" style="background: {{ $isTop ? '#FF00FF' : '#334155' }}; color: {{ $isTop ? '#fff' : '#94A3B8' }}; width:22px; height:22px; display:inline-flex; align-items:center; justify-content:center;">
                                {{ strtoupper(substr($m['nama'], 0, 1)) }}
                            </span>
                            {{ $m['nama'] }}
                        </td>
                        <td style="color: #F8FAFC;">{{ $m['total_servis'] }} Servis</td>
                        <td style="color: #94A3B8;">{{ $m['rata_rata_menit'] }} Menit</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:6px; background: #0F172A;">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{ $m['skor_efisiensi'] }}%; background: {{ $barWarna }}; box-shadow: {{ $barGlow }};">
                                    </div>
                                </div>
                                <span class="small fw-semibold" style="color: {{ $isTop ? '#FF00FF' : '#F8FAFC' }};">{{ $m['skor_efisiensi'] }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4" style="color: #94A3B8;">Belum ada data servis pada bulan ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('styles')
<style>
    .form-select, .form-control {
        background-color: #0F172A;
        border: 1px solid #334155;
        color: #F8FAFC;
    }
    .form-select:focus, .form-control:focus {
        background-color: #0F172A;
        border-color: #FF00FF;
        box-shadow: 0 0 0 .2rem rgba(255, 0, 255, .25);
        color: #F8FAFC;
    }
    .table > :not(caption) > * > * {
        background-color: transparent;
        color: #F8FAFC;
        border-color: #334155;
    }
    a.link-laporan:hover {
        text-shadow: 0 0 12px rgba(255, 0, 255, 0.9);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('grafikPendapatan');
    const labels = @json($grafikLabels);
    const values = @json($grafikValues);
    const highlightIndex = {{ $grafikHighlightIndex }};

    // Plugin: efek glow pada garis tren magenta
    const glowLine = {
        id: 'glowLine',
        beforeDatasetsDraw(chart, args, opts) {
            const { ctx } = chart;
            chart.data.datasets.forEach((dataset, index) => {
                if (dataset.type !== 'line') { return; }
                const meta = chart.getDatasetMeta(index);
                if (meta.hidden) { return; }

                ctx.save();
                ctx.shadowColor = 'rgba(255, 0, 255, 0.85)';
                ctx.shadowBlur = 18;
                ctx.strokeStyle = dataset.borderColor;
                ctx.lineWidth = dataset.borderWidth;
                ctx.beginPath();
                meta.data.forEach((point, i) => {
                    if (i === 0) { ctx.moveTo(point.x, point.y); } else { ctx.lineTo(point.x, point.y); }
                });
                ctx.stroke();
                ctx.restore();
            });
        }
    };

    new Chart(ctx, {
        plugins: [glowLine],
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'bar',
                    label: 'Pendapatan',
                    data: values,
                    backgroundColor: labels.map((_, i) => i === highlightIndex ? 'rgba(255, 0, 255, 0.75)' : '#334155'),
                    borderColor: labels.map((_, i) => i === highlightIndex ? '#FF00FF' : 'transparent'),
                    borderWidth: 1,
                    borderRadius: 6,
                    order: 2,
                },
                {
                    type: 'line',
                    label: 'Tren',
                    data: values,
                    borderColor: '#FF00FF',
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: '#FF00FF',
                    pointBorderColor: '#0F172A',
                    borderWidth: 2,
                    fill: false,
                    order: 1,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    ticks: { color: '#94A3B8', font: { weight: '600' } },
                    grid: { color: 'rgba(148, 163, 184, 0.12)' }
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: Math.max(...values) * 1.2 || 100000,
                    ticks: {
                        color: '#94A3B8',
                        callback: (val) => 'Rp' + (val / 1000) + 'k'
                    },
                    grid: { color: 'rgba(148, 163, 184, 0.12)' }
                }
            }
        }
    });
</script>
@endpush
