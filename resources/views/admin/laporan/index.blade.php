@extends('layouts.admin')

@section('title', 'Laporan & Analitik')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h1 class="h4 fw-bold mb-0">Laporan & Analitik</h1>
    <form method="GET" class="d-flex align-items-center gap-2">
        <label class="small text-muted mb-0">Bulan:</label>
        <input type="month" name="month" class="form-control form-control-sm"
               value="{{ $month->format('Y-m') }}" onchange="this.form.submit()">
    </form>
</div>

{{-- Kartu Ringkasan --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Total Pendapatan</p>
                    <h4 class="fw-bold mb-0" style="color: var(--bengkel-accent);">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </h4>
                    <p class="small mb-0 {{ $persenPerubahanPendapatan >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="bi bi-arrow-{{ $persenPerubahanPendapatan >= 0 ? 'up' : 'down' }}-short"></i>
                        {{ abs($persenPerubahanPendapatan) }}% dari bulan lalu
                    </p>
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
                    <p class="text-muted small mb-1">Total Motor Servis</p>
                    <h4 class="fw-bold mb-0">{{ $totalMotorServis }} Motor</h4>
                    <p class="small text-muted mb-0">Rata-rata {{ $rataRataPerHari }} motor/hari</p>
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
                    <p class="text-muted small mb-1">Efisiensi Rata-rata</p>
                    <h4 class="fw-bold mb-0">{{ $efisiensiRataRata }}%</h4>
                    <p class="small text-muted mb-0">Target: &gt;80%</p>
                </div>
                <div class="d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;border-radius:.75rem;background:linear-gradient(135deg,var(--bengkel-primary),var(--bengkel-accent));">
                    <i class="bi bi-speedometer2 text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Grafik Pendapatan --}}
<div class="card p-4 mb-3">
    <h2 class="h6 fw-bold mb-3">Grafik Pendapatan Bulanan</h2>
    <div style="position: relative; height: 320px;">
        <canvas id="grafikPendapatan"></canvas>
    </div>
</div>

{{-- Analitik Kinerja Mekanik --}}
<div class="card p-4">
    <h2 class="h6 fw-bold mb-3">Analitik Kinerja Mekanik</h2>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Mekanik</th>
                    <th>Total Servis</th>
                    <th>Rata-rata Waktu/Motor</th>
                    <th style="min-width:160px;">Skor Efisiensi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kinerjaMekanik as $i => $m)
                    <tr>
                        <td class="fw-semibold">
                            <span class="badge rounded-circle me-1" style="background: var(--bengkel-accent); width:22px; height:22px; display:inline-flex; align-items:center; justify-content:center;">
                                {{ strtoupper(substr($m['nama'], 0, 1)) }}
                            </span>
                            {{ $m['nama'] }}
                        </td>
                        <td>{{ $m['total_servis'] }} Servis</td>
                        <td>{{ $m['rata_rata_menit'] }} Menit</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:6px;">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{ $m['skor_efisiensi'] }}%; background: {{ $i === 0 ? 'var(--bengkel-accent)' : '#22c55e' }};">
                                    </div>
                                </div>
                                <span class="small fw-semibold">{{ $m['skor_efisiensi'] }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada data servis pada bulan ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('grafikPendapatan');
    const labels = @json($grafikLabels);
    const values = @json($grafikValues);
    const highlightIndex = {{ $grafikHighlightIndex }};

    new Chart(ctx, {
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'bar',
                    label: 'Pendapatan',
                    data: values,
                    backgroundColor: labels.map((_, i) => i === highlightIndex ? '#ed02fe' : '#e5e7eb'),
                    borderRadius: 6,
                    order: 2,
                },
                {
                    type: 'line',
                    label: 'Tren',
                    data: values,
                    borderColor: '#ed02fe',
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: '#ed02fe',
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
                y: {
                    beginAtZero: true,
                    suggestedMax: Math.max(...values) * 1.2 || 100000,
                    ticks: {
                        callback: (val) => 'Rp' + (val / 1000) + 'k'
                    }
                }
            }
        }
    });
</script>
@endpush
