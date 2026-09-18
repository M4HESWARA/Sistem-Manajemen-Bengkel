@extends('layouts.mekanik')

@section('title', 'Workspace Mekanik')

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

{{-- Daftar Antrian Penugasan --}}
<div class="mb-3">
    <h1 class="h6 fw-bold mb-2">Daftar Antrian Penugasan</h1>

    @forelse ($tugasAktif as $order)
        @php
            $isSelected = $selectedOrder && $selectedOrder->id === $order->id;
            $badgeLabel = $order->status === 'proses_diagnosa' ? 'Proses Diagnosa' : 'Sedang Diperbaiki';
            $badgeStyle = $order->status === 'proses_diagnosa' ? 'background:#fef3c7; color:#92400e;' : 'background:#dbeafe; color:#1e40af;';
        @endphp
        <div class="card p-3 mb-2 {{ $isSelected ? 'border-2' : '' }}"
             style="{{ $isSelected ? 'border-color: var(--bengkel-accent) !important;' : '' }}">
            <div class="d-flex justify-content-between align-items-start mb-1">
                <div>
                    <div class="fw-bold">{{ $order->vehicle->plat_nomor }}</div>
                    <div class="text-muted small">{{ $order->vehicle->brand }} {{ $order->vehicle->model }}</div>
                </div>
                <span class="status-badge" style="{{ $badgeStyle }}">{{ $badgeLabel }}</span>
            </div>
            <div class="text-muted small mb-2">
                <i class="bi bi-clock"></i> {{ $order->keluhan_awal }}
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm flex-grow-1" style="min-height:auto;"
                        data-bs-toggle="modal" data-bs-target="#riwayatModal{{ $order->id }}">
                    <i class="bi bi-clock-history"></i> Lihat Riwayat
                </button>
                @if (! $isSelected)
                    <a href="{{ route('mekanik.dashboard', ['order' => $order->id]) }}"
                       class="btn btn-bengkel btn-sm flex-grow-1" style="min-height:auto;">
                        Kerjakan
                    </a>
                @endif
            </div>
        </div>

        {{-- Modal Riwayat per kendaraan --}}
        <div class="modal fade" id="riwayatModal{{ $order->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Riwayat — {{ $order->vehicle->plat_nomor }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @forelse ($riwayatPerOrder[$order->id] as $r)
                            <div class="small border-bottom py-2">
                                <div class="fw-semibold">{{ $r->tanggal_selesai?->format('d M Y') }} — {{ $r->keluhan_awal }}</div>
                                @if ($r->catatan_solusi)
                                    <div class="text-muted">Solusi: {{ $r->catatan_solusi }}</div>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted small mb-0">Belum ada riwayat servis untuk kendaraan ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card p-4 text-center text-muted">
            <i class="bi bi-check2-circle fs-1 mb-2"></i>
            <p class="mb-0">Belum ada tugas aktif untuk kamu saat ini. 🎉</p>
        </div>
    @endforelse
</div>

{{-- Pengerjaan Aktif --}}
@if ($selectedOrder)
    @php
        $step = match ($selectedOrder->status) {
            'proses_diagnosa' => 0,
            'sedang_diperbaiki' => 1,
            'selesai' => 3,
            default => 0,
        };
    @endphp

    <div class="card p-3 mb-3" style="border-top: 4px solid var(--bengkel-accent);">
        <h2 class="h5 fw-bold mb-1">
            <i class="bi bi-person-workspace"></i> Pengerjaan Aktif: {{ $selectedOrder->vehicle->plat_nomor }}
        </h2>
        <p class="text-muted small mb-3">Status Pekerjaan</p>

        {{-- Stepper 4 Tahap --}}
        <div class="d-flex gap-1 mb-3 flex-wrap">
            @php
                $steps = [
                    ['label' => 'Pengecekan', 'icon' => 'bi-search', 'active' => $step >= 0],
                    ['label' => 'Bongkar', 'icon' => 'bi-tools', 'active' => $step >= 1],
                    ['label' => 'Ganti Part', 'icon' => 'bi-arrow-left-right', 'active' => $step >= 1],
                    ['label' => 'Selesai', 'icon' => 'bi-check-circle', 'active' => $step >= 3],
                ];
            @endphp
            @foreach ($steps as $s)
                <span class="stepper-pill {{ $s['active'] ? 'active' : '' }}">
                    <i class="bi {{ $s['icon'] }}"></i> {{ $s['label'] }}
                </span>
            @endforeach
        </div>

        @if ($selectedOrder->status === 'proses_diagnosa')
            <form method="POST" action="{{ route('mekanik.tugas.update-status', $selectedOrder) }}" class="mb-3">
                @csrf
                <input type="hidden" name="status" value="sedang_diperbaiki">
                <button type="submit" class="btn btn-bengkel btn-lg w-100">
                    <i class="bi bi-play-circle"></i> Mulai Perbaikan (Bongkar &amp; Ganti Part)
                </button>
            </form>
        @endif

        @if ($selectedOrder->status !== 'selesai')
            {{-- Penggunaan Sparepart --}}
            <div class="border rounded-3 p-3 mb-3">
                <p class="fw-bold small mb-2" style="color: var(--bengkel-primary);">
                    <i class="bi bi-nut"></i> Penggunaan Sparepart
                </p>

                <form method="POST" action="{{ route('mekanik.tugas.tambah-sparepart', $selectedOrder) }}" class="d-flex gap-2 mb-2">
                    @csrf
                    <select name="sparepart_id" class="form-select" required>
                        <option value="" disabled selected>Cari sparepart...</option>
                        @foreach ($spareparts as $sp)
                            <option value="{{ $sp->id }}">{{ $sp->name }} (stok: {{ $sp->stok }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="quantity" class="form-control" style="max-width:70px;" min="1" value="1" required>
                    <button type="submit" class="btn btn-bengkel" style="min-width:48px;">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </form>

                @forelse ($selectedOrder->items as $item)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <span class="small">{{ $item->sparepart->name }}</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-muted">Qty: {{ $item->quantity }}</span>
                            <form method="POST" action="{{ route('mekanik.tugas.hapus-sparepart', [$selectedOrder, $item]) }}"
                                  onsubmit="return confirm('Hapus sparepart ini? Stok akan dikembalikan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" style="min-height:auto;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Belum ada sparepart yang dipakai.</p>
                @endforelse
            </div>

            {{-- Biaya Jasa --}}
            <div class="border rounded-3 p-3 mb-3">
                <p class="fw-bold small mb-2" style="color: var(--bengkel-primary);">
                    <i class="bi bi-cash"></i> Biaya Jasa
                </p>

                <form method="POST" action="{{ route('mekanik.tugas.tambah-jasa', $selectedOrder) }}" class="d-flex gap-2 mb-2">
                    @csrf
                    <input type="text" name="deskripsi" class="form-control" placeholder="mis. Servis Rem" required>
                    <input type="number" name="biaya" class="form-control" style="max-width:110px;" placeholder="Rp" min="0" required>
                    <button type="submit" class="btn btn-bengkel" style="min-width:48px;">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </form>

                @forelse ($selectedOrder->jasaItems as $jasa)
                    <div class="d-flex justify-content-between small border-bottom py-2">
                        <span>{{ $jasa->deskripsi }}</span>
                        <span>Rp {{ number_format($jasa->biaya, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Belum ada biaya jasa dicatat.</p>
                @endforelse
            </div>

            {{-- Catatan Solusi --}}
            <p class="fw-bold small mb-2" style="color: var(--bengkel-primary);">
                <i class="bi bi-pencil-square"></i> Catatan Solusi &amp; Tindakan Perbaikan
            </p>
            <form method="POST" action="{{ route('mekanik.tugas.selesai', $selectedOrder) }}"
                  onsubmit="return confirm('Yakin servis ini sudah selesai dikerjakan?');">
                @csrf
                <textarea name="catatan_solusi" class="form-control mb-3" rows="3" required
                          placeholder="Detail perbaikan yang dilakukan...">{{ old('catatan_solusi') }}</textarea>
                <button type="submit" class="btn btn-bengkel btn-lg w-100">
                    <i class="bi bi-check-circle"></i> Selesaikan Pekerjaan
                </button>
            </form>
        @else
            <div class="alert alert-success mb-0">
                <i class="bi bi-check-circle-fill"></i> Servis ini sudah selesai.
                <div class="small mt-1"><strong>Catatan:</strong> {{ $selectedOrder->catatan_solusi }}</div>
            </div>
        @endif
    </div>
@endif

@endsection

@push('styles')
<style>
    .stepper-pill {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .4rem .8rem;
        border-radius: 2rem;
        font-size: .8rem;
        font-weight: 600;
        background: #f3f4f6;
        color: #9ca3af;
    }
    .stepper-pill.active {
        background: linear-gradient(135deg, var(--bengkel-primary), var(--bengkel-accent));
        color: #fff;
    }
</style>
@endpush
