@extends('layouts.mekanik')

@section('title', 'Workspace Mekanik')

@push('styles')
<style>
    .scroll-panel {
        max-height: calc(100vh - 180px);
        overflow-y: auto;
        padding-right: 0.5rem;
    }
    .scroll-panel::-webkit-scrollbar {
        width: 8px;
    }
    .scroll-panel::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 4px;
    }
    .scroll-panel::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }
    .scroll-panel::-webkit-scrollbar-track {
        background: transparent;
    }
    @media (max-width: 991.98px) {
        .scroll-panel {
            max-height: none;
            overflow-y: visible;
            padding-right: 0;
        }
    }
    .task-card {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .task-card.selected {
        border: 2px solid #FF00FF !important;
        box-shadow: 0 0 15px rgba(255, 0, 255, 0.25);
    }
</style>
@endpush

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

<div class="row g-4">
    
    <!-- KOLOM KIRI: DAFTAR ANTRIAN PENUGASAN -->
    <div class="col-lg-4">
        <h5 class="fw-bold mb-4" style="color: #F8FAFC;">Daftar Antrian Penugasan</h5>

        <div class="scroll-panel">
        @forelse ($tugasAktif as $order)
            @php
                $isSelected = $selectedOrder && $selectedOrder->id === $order->id;
                $badgeLabel = $order->status === 'proses_diagnosa' ? 'MENUNGGU' : 'DALAM PERBAIKAN';
                $badgeClass = $order->status === 'proses_diagnosa' ? 'bg-warning bg-opacity-25 text-warning border border-warning' : 'bg-info bg-opacity-25 text-info border border-info';
            @endphp
            <div class="card p-3 mb-3 task-card {{ $isSelected ? 'selected' : '' }}">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <h5 class="fw-bold mb-0" style="color: #F8FAFC;">{{ $order->vehicle->plat_nomor }}</h5>
                    <span class="badge {{ $badgeClass }} rounded-pill py-1 px-2" style="font-size: 0.65rem; font-weight: 700;">{{ $badgeLabel }}</span>
                </div>
                <p class="mb-3" style="color: #94A3B8;">{{ $order->vehicle->brand }} {{ $order->vehicle->model }}</p>
                
                <div class="d-flex align-items-center gap-2 mb-3" style="color: #94A3B8;">
                    <i class="bi bi-tools"></i> <span class="small fw-medium">{{ $order->keluhan_awal }}</span>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm flex-grow-1 fw-semibold" style="background: transparent; border: 1px solid #334155; color: #94A3B8;"
                            data-bs-toggle="modal" data-bs-target="#riwayatModal{{ $order->id }}">
                        Lihat Riwayat
                    </button>
                    @if (! $isSelected)
                        <a href="{{ route('mekanik.dashboard', ['order' => $order->id]) }}"
                           class="btn btn-magenta flex-grow-1 text-center text-decoration-none d-flex align-items-center justify-content-center small py-2">
                            Mulai Perbaikan
                        </a>
                    @endif
                </div>
            </div>

            <!-- Modal Riwayat -->
            <div class="modal fade" id="riwayatModal{{ $order->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="background: #1E293B; border: 1px solid #334155;">
                        <div class="modal-header" style="border-bottom: 1px solid #334155;">
                            <h5 class="modal-title" style="color: #F8FAFC;">Riwayat — {{ $order->vehicle->plat_nomor }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" style="color: #94A3B8;">
                            @forelse ($riwayatPerOrder[$order->id] as $r)
                                <div class="small border-bottom py-2" style="border-color: #334155 !important;">
                                    <div class="fw-semibold" style="color: #F8FAFC;">{{ $r->tanggal_selesai?->format('d M Y') }} — {{ $r->keluhan_awal }}</div>
                                    @if ($r->catatan_solusi)
                                        <div>Solusi: {{ $r->catatan_solusi }}</div>
                                    @endif
                                </div>
                            @empty
                                <p class="mb-0">Belum ada riwayat servis untuk kendaraan ini.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card p-4 text-center" style="color: #94A3B8;">
                <i class="bi bi-check2-circle fs-1 mb-2"></i>
                <p class="mb-0">Belum ada tugas aktif untuk kamu saat ini.</p>
            </div>
        @endforelse
        </div>
    </div>

    <!-- KOLOM KANAN: PENGERJAAN AKTIF -->
    <div class="col-lg-8">
        <div class="scroll-panel">
        @if ($selectedOrder)
            <div class="card p-4 mb-3" style="border-top: 3px solid #FF00FF;">
                
                <div class="d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-person-workspace fs-3" style="color: #FF00FF;"></i>
                    <h3 class="fw-bold mb-0" style="color: #F8FAFC;">Pengerjaan Aktif: {{ $selectedOrder->vehicle->plat_nomor }}</h3>
                </div>

                <!-- Status Pekerjaan (Step Wizard) -->
                <div class="mb-4">
                    <label class="form-label small fw-semibold mb-2" style="color: #F8FAFC;">Status Pekerjaan</label>
                    <div class="d-flex flex-wrap gap-2">
                        @php
                            $statusOrder = ['proses_diagnosa', 'sedang_diperbaiki'];
                            $currentIdx = array_search($selectedOrder->status, $statusOrder);
                            if ($selectedOrder->status === 'selesai') { $currentIdx = 2; }
                        @endphp
                        <span class="status-pill {{ $selectedOrder->status === 'proses_diagnosa' || $selectedOrder->status === 'sedang_diperbaiki' || $selectedOrder->status === 'selesai' ? 'active' : '' }}"><i class="bi bi-search"></i> Pengecekan</span>
                        <span class="status-pill {{ $selectedOrder->status === 'sedang_diperbaiki' || $selectedOrder->status === 'selesai' ? 'active' : '' }}"><i class="bi bi-tools"></i> Bongkar</span>
                        <span class="status-pill {{ $selectedOrder->status === 'sedang_diperbaiki' || $selectedOrder->status === 'selesai' ? 'active' : '' }}"><i class="bi bi-box-seam"></i> Ganti Part</span>
                        <span class="status-pill {{ $selectedOrder->status === 'selesai' ? 'active' : '' }}"><i class="bi bi-check2-circle"></i> Selesai</span>
                    </div>
                </div>

                @if ($selectedOrder->status === 'proses_diagnosa')
                    <form method="POST" action="{{ route('mekanik.tugas.update-status', $selectedOrder) }}" class="mb-4">
                        @csrf
                        <input type="hidden" name="status" value="sedang_diperbaiki">
                        <button type="submit" class="btn btn-magenta w-100 py-2 glow-magenta">
                            Mulai Perbaikan (Bongkar & Ganti Part)
                        </button>
                    </form>
                @endif

                @if ($selectedOrder->status !== 'selesai')
                    <!-- Penggunaan Sparepart -->
                    <div class="rounded-3 p-3 mb-4" style="background: #0F172A; border: 1px solid #334155;">
                        <label class="form-label fw-bold mb-2 d-flex align-items-center gap-2" style="color: #F8FAFC;">
                            <i class="bi bi-clipboard2-check"></i> Penggunaan Sparepart
                        </label>
                        
                        <form method="POST" action="{{ route('mekanik.tugas.tambah-sparepart', $selectedOrder) }}" class="input-group mb-3">
                            @csrf
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <select name="sparepart_id" class="form-select" required>
                                <option value="" disabled selected>Cari sparepart...</option>
                                @foreach ($spareparts as $sp)
                                    <option value="{{ $sp->id }}">{{ $sp->name }} (stok: {{ $sp->stok }})</option>
                                @endforeach
                            </select>
                            <input type="number" name="quantity" class="form-control" style="max-width: 80px;" min="1" value="1" required>
                            <button class="btn btn-magenta px-3" type="submit"><i class="bi bi-plus-lg"></i></button>
                        </form>

                        <div class="table-responsive rounded border" style="border-color: #334155 !important;">
                            <table class="table table-borderless mb-0 align-middle">
                                <thead>
                                    <tr class="small" style="color: #94A3B8;">
                                        <th class="ps-3 py-2">Nama Item</th>
                                        <th width="80" class="text-center py-2">Qty</th>
                                        <th width="50" class="py-2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($selectedOrder->items as $item)
                                        <tr style="border-top: 1px solid #334155;">
                                            <td class="ps-3 small">{{ $item->sparepart->name }}</td>
                                            <td class="text-center small">{{ $item->quantity }}</td>
                                            <td class="text-end pe-3">
                                                <form method="POST" action="{{ route('mekanik.tugas.hapus-sparepart', [$selectedOrder, $item]) }}" onsubmit="return confirm('Hapus sparepart ini?');" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link p-0" style="color: #FF00FF;"><i class="bi bi-trash3"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center small py-3" style="color: #94A3B8;">Belum ada sparepart yang dipakai.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Biaya Jasa -->
                    <div class="rounded-3 p-3 mb-4" style="background: #0F172A; border: 1px solid #334155;">
                        <label class="form-label fw-bold mb-2 d-flex align-items-center gap-2" style="color: #F8FAFC;">
                            <i class="bi bi-cash"></i> Biaya Jasa
                        </label>
                        <form method="POST" action="{{ route('mekanik.tugas.tambah-jasa', $selectedOrder) }}" class="d-flex gap-2 mb-3">
                            @csrf
                            <input type="text" name="deskripsi" class="form-control" placeholder="mis. Servis Rem" required>
                            <input type="number" name="biaya" class="form-control" style="max-width: 120px;" placeholder="Rp" min="0" required>
                            <button type="submit" class="btn btn-magenta px-3"><i class="bi bi-plus-lg"></i></button>
                        </form>

                        @forelse ($selectedOrder->jasaItems as $jasa)
                            <div class="d-flex justify-content-between small border-bottom py-2 px-2 rounded mb-1" style="border-color: #334155 !important;">
                                <span style="color: #F8FAFC;">{{ $jasa->deskripsi }}</span>
                                <span class="fw-semibold" style="color: #FF00FF;">Rp {{ number_format($jasa->biaya, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <p class="small mb-0" style="color: #94A3B8;">Belum ada biaya jasa dicatat.</p>
                        @endforelse
                    </div>

                    <!-- Catatan Solusi & Tombol Selesai -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small mb-2" style="color: #F8FAFC;">Catatan Solusi & Tindakan Perbaikan</label>
                        <form method="POST" action="{{ route('mekanik.tugas.selesai', $selectedOrder) }}" onsubmit="return confirm('Yakin servis ini sudah selesai dikerjakan?');">
                            @csrf
                            <textarea name="catatan_solusi" class="form-control mb-3" rows="3" required placeholder="Detail perbaikan yang dilakukan...">{{ old('catatan_solusi') }}</textarea>
                            <button type="submit" class="btn btn-magenta w-100 py-2 d-flex align-items-center justify-content-center gap-2 glow-magenta">
                                <i class="bi bi-check-circle-fill"></i> Selesaikan Pekerjaan
                            </button>
                        </form>
                    </div>
                @else
                    <div class="alert alert-success mb-0" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #34d399;">
                        <i class="bi bi-check-circle-fill"></i> Servis ini sudah selesai.
                        <div class="small mt-1"><strong>Catatan:</strong> {{ $selectedOrder->catatan_solusi }}</div>
                    </div>
                @endif

            </div>
        @else
            <div class="card p-5 text-center h-100 d-flex flex-column align-items-center justify-content-center" style="min-height: 400px; color: #94A3B8;">
                <i class="bi bi-arrow-left-circle fs-1 mb-2"></i>
                <p class="mb-0 fw-semibold">Pilih salah satu tugas dari daftar antrian di sebelah kiri untuk mulai mengerjakannya.</p>
            </div>
        @endif
        </div>
    </div>

</div>

@endsection
