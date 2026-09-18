@extends('layouts.mekanik')

@section('title', $serviceOrder->order_number)

@section('content')

<a href="{{ route('mekanik.dashboard') }}" class="text-decoration-none text-muted d-inline-block mb-2">
    <i class="bi bi-arrow-left"></i> Kembali ke Tugas Saya
</a>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

@php
    $statusLabel = match ($serviceOrder->status) {
        'menunggu' => 'Menunggu',
        'proses_diagnosa' => 'Proses Diagnosa',
        'sedang_diperbaiki' => 'Sedang Diperbaiki',
        'selesai' => 'Selesai',
        default => $serviceOrder->status,
    };
    $statusColor = match ($serviceOrder->status) {
        'proses_diagnosa' => 'background:#fef3c7; color:#92400e;',
        'sedang_diperbaiki' => 'background:#dbeafe; color:#1e40af;',
        'selesai' => 'background:#dcfce7; color:#166534;',
        default => 'background:#f3f4f6; color:#374151;',
    };
@endphp

{{-- Info Utama --}}
<div class="card p-3 mb-3">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
            <div class="fs-4 fw-bold">{{ $serviceOrder->vehicle->brand }} {{ $serviceOrder->vehicle->model }}</div>
            <div class="text-muted">{{ $serviceOrder->vehicle->plat_nomor }} &bull; {{ $serviceOrder->customer->full_name }}</div>
        </div>
        <span class="status-badge" style="{{ $statusColor }}">{{ $statusLabel }}</span>
    </div>
    <div class="border-top pt-2 mt-2">
        <div class="text-muted small">Keluhan Awal</div>
        <div class="fw-semibold">{{ $serviceOrder->keluhan_awal }}</div>
    </div>
</div>

{{-- Tombol Update Status --}}
@if ($serviceOrder->status === 'proses_diagnosa')
    <form method="POST" action="{{ route('mekanik.tugas.update-status', $serviceOrder) }}" class="mb-3">
        @csrf
        <input type="hidden" name="status" value="sedang_diperbaiki">
        <button type="submit" class="btn btn-bengkel btn-lg w-100">
            <i class="bi bi-play-circle"></i> Mulai Perbaikan
        </button>
    </form>
@endif

{{-- Verifikasi Fisik Awal --}}
@if ($serviceOrder->verification)
    <div class="card p-3 mb-3">
        <h2 class="h6 fw-bold mb-2"><i class="bi bi-camera"></i> Verifikasi Fisik Awal</h2>
        <div class="row row-cols-2 g-2 small">
            <div class="text-muted">Odometer</div>
            <div class="fw-semibold">{{ number_format($serviceOrder->verification->odometer_reading) }} km</div>
            <div class="text-muted">Bensin</div>
            <div class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $serviceOrder->verification->fuel_level) }}</div>
        </div>
        @if ($serviceOrder->verification->hasWarning())
            <div class="alert alert-warning small mt-2 mb-0 py-2">
                <i class="bi bi-exclamation-triangle-fill"></i> Ada indikator/lampu bermasalah — cek catatan admin.
            </div>
        @endif
    </div>
@endif

{{-- Riwayat Servis Sebelumnya --}}
@if ($riwayat->isNotEmpty())
    <div class="card p-3 mb-3">
        <h2 class="h6 fw-bold mb-2"><i class="bi bi-clock-history"></i> Riwayat Servis Kendaraan Ini</h2>
        @foreach ($riwayat as $r)
            <div class="small border-bottom py-2">
                <div class="fw-semibold">{{ $r->tanggal_selesai?->format('d M Y') }} — {{ $r->keluhan_awal }}</div>
                @if ($r->catatan_solusi)
                    <div class="text-muted">Solusi: {{ $r->catatan_solusi }}</div>
                @endif
            </div>
        @endforeach
    </div>
@endif

@if (in_array($serviceOrder->status, ['proses_diagnosa', 'sedang_diperbaiki']))

    {{-- Sparepart Terpakai --}}
    <div class="card p-3 mb-3">
        <h2 class="h6 fw-bold mb-2"><i class="bi bi-nut"></i> Sparepart Terpakai</h2>

        @forelse ($serviceOrder->items as $item)
            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                <div class="small">
                    <div class="fw-semibold">{{ $item->sparepart->name }} &times;{{ $item->quantity }}</div>
                    <div class="text-muted">Rp {{ number_format($item->quantity * $item->harga_satuan, 0, ',', '.') }}</div>
                </div>
                <form method="POST" action="{{ route('mekanik.tugas.hapus-sparepart', [$serviceOrder, $item]) }}"
                      onsubmit="return confirm('Hapus sparepart ini? Stok akan dikembalikan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm" style="min-height:auto;">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        @empty
            <p class="text-muted small mb-2">Belum ada sparepart yang dipakai.</p>
        @endforelse

        <form method="POST" action="{{ route('mekanik.tugas.tambah-sparepart', $serviceOrder) }}" class="mt-2">
            @csrf
            <div class="row g-2">
                <div class="col-8">
                    <select name="sparepart_id" class="form-select" required>
                        <option value="" disabled selected>Pilih sparepart...</option>
                        @foreach ($spareparts as $sp)
                            <option value="{{ $sp->id }}">{{ $sp->name }} (stok: {{ $sp->stok }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <input type="number" name="quantity" class="form-control" placeholder="Jml" min="1" value="1" required>
                </div>
            </div>
            <button type="submit" class="btn btn-outline-primary w-100 mt-2">
                <i class="bi bi-plus-lg"></i> Tambah Sparepart
            </button>
        </form>
    </div>

    {{-- Jasa Servis --}}
    <div class="card p-3 mb-3">
        <h2 class="h6 fw-bold mb-2"><i class="bi bi-tools"></i> Biaya Jasa</h2>

        @forelse ($serviceOrder->jasaItems as $jasa)
            <div class="d-flex justify-content-between small border-bottom py-2">
                <span>{{ $jasa->deskripsi }}</span>
                <span>Rp {{ number_format($jasa->biaya, 0, ',', '.') }}</span>
            </div>
        @empty
            <p class="text-muted small mb-2">Belum ada biaya jasa dicatat.</p>
        @endforelse

        <form method="POST" action="{{ route('mekanik.tugas.tambah-jasa', $serviceOrder) }}" class="mt-2">
            @csrf
            <div class="row g-2">
                <div class="col-7">
                    <input type="text" name="deskripsi" class="form-control" placeholder="mis. Servis Rem" required>
                </div>
                <div class="col-5">
                    <input type="number" name="biaya" class="form-control" placeholder="Rp" min="0" required>
                </div>
            </div>
            <button type="submit" class="btn btn-outline-primary w-100 mt-2">
                <i class="bi bi-plus-lg"></i> Tambah Jasa
            </button>
        </form>
    </div>

    {{-- Selesaikan Servis --}}
    @if ($serviceOrder->status === 'sedang_diperbaiki')
        <div class="card p-3 mb-3">
            <h2 class="h6 fw-bold mb-2"><i class="bi bi-check-circle"></i> Selesaikan Servis</h2>
            <form method="POST" action="{{ route('mekanik.tugas.selesai', $serviceOrder) }}"
                  onsubmit="return confirm('Yakin servis ini sudah selesai dikerjakan?');">
                @csrf
                <label class="form-label small fw-semibold">Catatan Solusi</label>
                <textarea name="catatan_solusi" class="form-control mb-2" rows="3" required
                          placeholder="Jelaskan apa yang sudah dikerjakan/diperbaiki...">{{ old('catatan_solusi') }}</textarea>
                <button type="submit" class="btn btn-bengkel btn-lg w-100">
                    <i class="bi bi-flag-fill"></i> Tandai Selesai
                </button>
            </form>
        </div>
    @endif

@elseif ($serviceOrder->status === 'selesai')
    <div class="card p-3 mb-3" style="background:#f0fdf4;">
        <h2 class="h6 fw-bold mb-2 text-success"><i class="bi bi-check-circle-fill"></i> Servis Sudah Selesai</h2>
        <p class="small mb-0"><strong>Catatan Solusi:</strong> {{ $serviceOrder->catatan_solusi }}</p>
    </div>
@endif

@endsection
