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

<div class="row g-4">
    
    <!-- KOLOM KIRI: DAFTAR ANTRIAN PENUGASAN -->
    <div class="col-lg-4">
        <h5 class="fw-bold mb-4 text-dark">Daftar Antrian Penugasan</h5>

        @forelse ($tugasAktif as $order)
            @php
                $isSelected = $selectedOrder && $selectedOrder->id === $order->id;
                $badgeLabel = $order->status === 'proses_diagnosa' ? 'MENUNGGU' : 'DALAM PERBAIKAN';
                $badgeClass = $order->status === 'proses_diagnosa' ? 'bg-light text-danger border' : 'bg-light text-primary border';
            @endphp
            <div class="card p-3 mb-3 {{ $isSelected ? 'border-2' : '' }}"
                 style="{{ $isSelected ? 'border-color: var(--bengkel-accent) !important;' : '' }}">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <h5 class="fw-bold mb-0 text-dark">{{ $order->vehicle->plat_nomor }}</h5>
                    <span class="badge {{ $badgeClass }} rounded-pill py-1 px-2" style="font-size: 0.65rem; font-weight: 700;">{{ $badgeLabel }}</span>
                </div>
                <p class="text-muted small mb-3">{{ $order->vehicle->brand }} {{ $order->vehicle->model }}</p>
                
                <div class="d-flex align-items-center gap-2 mb-3 text-secondary small fw-medium">
                    <i class="bi bi-tools"></i> {{ $order->keluhan_awal }}
                </div>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light border flex-grow-1 text-muted fw-semibold small py-2"
                            data-bs-toggle="modal" data-bs-target="#riwayatModal{{ $order->id }}">
                        Lihat Riwayat
                    </button>
                    @if (! $isSelected)
                        <a href="{{ route('mekanik.dashboard', ['order' => $order->id]) }}"
                           class="btn btn-bengkel flex-grow-1 text-center text-decoration-none d-flex align-items-center justify-content-center small py-2">
                            Mulai Perbaikan
                        </a>
                    @endif
                </div>
            </div>

            <!-- Modal Riwayat -->
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

    <!-- KOLOM KANAN: PENGERJAAN AKTIF -->
    <div class="col-lg-8">
        @if ($selectedOrder)
            <div class="card p-4 mb-3" style="border-top: 4px solid var(--bengkel-accent);">
                
                <div class="d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-person-workspace fs-3" style="color: var(--bengkel-accent);"></i>
                    <h3 class="fw-bold mb-0 text-dark">Pengerjaan Aktif: {{ $selectedOrder->vehicle->plat_nomor }}</h3>
                </div>

                <!-- Status Pekerjaan (Pills) -->
                <div class="mb-4">
                    <label class="form-label text-muted small fw-semibold mb-2">Status Pekerjaan</label>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="status-pill active"><i class="bi bi-search"></i> Pengecekan</span>
                        <span class="status-pill"><i class="bi bi-tools"></i> Bongkar</span>
                        <span class="status-pill"><i class="bi bi-box-seam"></i> Ganti Part</span>
                        <span class="status-pill"><i class="bi bi-check2-circle"></i> Selesai</span>
                    </div>
                </div>

                @if ($selectedOrder->status === 'proses_diagnosa')
                    <form method="POST" action="{{ route('mekanik.tugas.update-status', $selectedOrder) }}" class="mb-4">
                        @csrf
                        <input type="hidden" name="status" value="sedang_diperbaiki">
                        <button type="submit" class="btn btn-bengkel w-100 py-2">
                            Mulai Perbaikan (Bongkar & Ganti Part)
                        </button>
                    </form>
                @endif

                @if ($selectedOrder->status !== 'selesai')
                    <!-- Penggunaan Sparepart -->
                    <div class="bg-light rounded-3 p-3 mb-4 border">
                        <label class="form-label text-dark fw-bold mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-clipboard2-check"></i> Penggunaan Sparepart
                        </label>
                        
                        <form method="POST" action="{{ route('mekanik.tugas.tambah-sparepart', $selectedOrder) }}" class="input-group mb-3">
                            @csrf
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <select name="sparepart_id" class="form-select border-start-0 ps-0" required>
                                <option value="" disabled selected>Cari sparepart...</option>
                                @foreach ($spareparts as $sp)
                                    <option value="{{ $sp->id }}">{{ $sp->name }} (stok: {{ $sp->stok }})</option>
                                @endforeach
                            </select>
                            <input type="number" name="quantity" class="form-control" style="max-width: 80px;" min="1" value="1" required>
                            <button class="btn btn-outline-secondary px-3" type="submit"><i class="bi bi-plus-lg"></i></button>
                        </form>

                        <div class="table-responsive bg-white rounded border">
                            <table class="table table-borderless mb-0 align-middle">
                                <thead class="border-bottom">
                                    <tr class="small text-muted">
                                        <th class="ps-3 py-2">Nama Item</th>
                                        <th width="80" class="text-center py-2">Qty</th>
                                        <th width="50" class="py-2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($selectedOrder->items as $item)
                                        <tr class="border-bottom">
                                            <td class="ps-3 small">{{ $item->sparepart->name }}</td>
                                            <td class="text-center small">{{ $item->quantity }}</td>
                                            <td class="text-end pe-3">
                                                <form method="POST" action="{{ route('mekanik.tugas.hapus-sparepart', [$selectedOrder, $item]) }}" onsubmit="return confirm('Hapus sparepart ini?');" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0"><i class="bi bi-trash3"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted small py-3">Belum ada sparepart yang dipakai.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Biaya Jasa -->
                    <div class="bg-light rounded-3 p-3 mb-4 border">
                        <label class="form-label text-dark fw-bold mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-cash"></i> Biaya Jasa
                        </label>
                        <form method="POST" action="{{ route('mekanik.tugas.tambah-jasa', $selectedOrder) }}" class="d-flex gap-2 mb-3">
                            @csrf
                            <input type="text" name="deskripsi" class="form-control" placeholder="mis. Servis Rem" required>
                            <input type="number" name="biaya" class="form-control" style="max-width: 120px;" placeholder="Rp" min="0" required>
                            <button type="submit" class="btn btn-outline-secondary px-3"><i class="bi bi-plus-lg"></i></button>
                        </form>

                        @forelse ($selectedOrder->jasaItems as $jasa)
                            <div class="d-flex justify-content-between small border-bottom py-2 bg-white px-2 rounded mb-1">
                                <span>{{ $jasa->deskripsi }}</span>
                                <span class="fw-semibold">Rp {{ number_format($jasa->biaya, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <p class="text-muted small mb-0">Belum ada biaya jasa dicatat.</p>
                        @endforelse
                    </div>

                    <!-- Catatan Solusi & Tombol Selesai -->
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small mb-2">Catatan Solusi & Tindakan Perbaikan</label>
                        <form method="POST" action="{{ route('mekanik.tugas.selesai', $selectedOrder) }}" onsubmit="return confirm('Yakin servis ini sudah selesai dikerjakan?');">
                            @csrf
                            <textarea name="catatan_solusi" class="form-control mb-3" rows="3" required placeholder="Detail perbaikan yang dilakukan...">{{ old('catatan_solusi') }}</textarea>
                            <button type="submit" class="btn btn-bengkel w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-check-circle-fill"></i> Selesaikan Pekerjaan
                            </button>
                        </form>
                    </div>
                @else
                    <div class="alert alert-success mb-0">
                        <i class="bi bi-check-circle-fill"></i> Servis ini sudah selesai.
                        <div class="small mt-1"><strong>Catatan:</strong> {{ $selectedOrder->catatan_solusi }}</div>
                    </div>
                @endif

            </div>
        @else
            <div class="card p-5 text-center text-muted h-100 d-flex flex-column align-items-center justify-content-center" style="min-height: 400px;">
                <i class="bi bi-arrow-left-circle fs-1 mb-2"></i>
                <p class="mb-0 fw-semibold">Pilih salah satu tugas dari daftar antrian di sebelah kiri untuk mulai mengerjakannya.</p>
            </div>
        @endif
    </div>

</div>

@endsection