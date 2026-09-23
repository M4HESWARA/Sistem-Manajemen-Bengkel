@extends('layouts.admin')

@section('title', 'Riwayat Servis')

@section('content')

<div class="mb-3">
    <h1 class="h4 fw-bold mb-0">Riwayat Servis</h1>
    <p class="text-muted small mb-0">Semua transaksi servis, baik yang masih berjalan maupun sudah selesai.</p>
</div>

{{-- Filter --}}
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Cari plat nomor, nama pelanggan, atau no. order..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="menunggu" @selected(request('status') === 'menunggu')>Menunggu</option>
                <option value="proses_diagnosa" @selected(request('status') === 'proses_diagnosa')>Proses Diagnosa</option>
                <option value="sedang_diperbaiki" @selected(request('status') === 'sedang_diperbaiki')>Sedang Diperbaiki</option>
                <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
                <option value="dibatalkan" @selected(request('status') === 'dibatalkan')>Dibatalkan</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}" title="Dari tanggal">
        </div>
        <div class="col-md-2">
            <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}" title="Sampai tanggal">
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-outline-secondary flex-grow-1">Filter</button>
            @if (request()->anyFilled(['search', 'status', 'from', 'to']))
                <a href="{{ route('admin.riwayat') }}" class="btn btn-sm btn-outline-danger" title="Reset filter">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Order</th>
                    <th>Kendaraan</th>
                    <th>Pelanggan</th>
                    <th>Mekanik</th>
                    <th>Tanggal Masuk</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Total Tagihan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayat as $order)
                    @php
                        $statusLabel = match ($order->status) {
                            'menunggu' => 'Menunggu',
                            'proses_diagnosa' => 'Proses Diagnosa',
                            'sedang_diperbaiki' => 'Sedang Diperbaiki',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                            default => $order->status,
                        };
                        $statusStyle = match ($order->status) {
                            'selesai' => 'background:#dcfce7; color:#166534;',
                            'sedang_diperbaiki' => 'background:#dbeafe; color:#1e40af;',
                            'proses_diagnosa' => 'background:#fef3c7; color:#92400e;',
                            'dibatalkan' => 'background:#fee2e2; color:#991b1b;',
                            default => 'background:#f3f4f6; color:#374151;',
                        };
                    @endphp
                    <tr>
                        <td class="text-muted small">{{ $order->order_number }}</td>
                        <td class="fw-semibold small">
                            {{ $order->vehicle->brand }} {{ $order->vehicle->model }}<br>
                            <span class="text-muted">{{ $order->vehicle->plat_nomor }}</span>
                        </td>
                        <td class="small">{{ $order->customer->full_name }}</td>
                        <td class="small text-muted">{{ $order->mechanic->full_name ?? '-' }}</td>
                        <td class="small text-muted">{{ $order->tanggal_masuk?->format('d M Y, H:i') }}</td>
                        <td class="text-center">
                            <span class="badge rounded-pill" style="{{ $statusStyle }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="text-end small">
                            @if ($order->invoice)
                                <div class="fw-semibold">Rp {{ number_format($order->invoice->total_tagihan, 0, ',', '.') }}</div>
                                <span class="badge {{ $order->invoice->status === 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning-emphasis' }}">
                                    {{ $order->invoice->status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if ($order->invoice && $order->invoice->status === 'paid')
                                <a href="{{ route('admin.kasir.nota', $order) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-receipt"></i> Nota
                                </a>
                            @elseif ($order->status === 'selesai')
                                <a href="{{ route('admin.kasir', ['order' => $order->id]) }}" class="btn btn-sm btn-bengkel">
                                    <i class="bi bi-cash"></i> Bayar
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Tidak ada data yang sesuai filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($riwayat->total() > 0)
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
            <span class="text-muted small">
                Menampilkan {{ $riwayat->firstItem() }}-{{ $riwayat->lastItem() }} dari {{ $riwayat->total() }} transaksi
            </span>
            <div>{{ $riwayat->links() }}</div>
        </div>
    @endif
</div>

@endsection
