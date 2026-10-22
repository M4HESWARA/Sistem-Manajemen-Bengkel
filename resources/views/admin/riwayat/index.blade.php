@extends('layouts.admin')

@section('title', 'Riwayat Servis')

@section('content')

<div class="mb-4">
    <h1 class="h4 fw-bold mb-0" style="color: #F8FAFC;">Riwayat Servis</h1>
    <p class="small mb-0" style="color: #94A3B8;">Semua transaksi servis, baik yang masih berjalan maupun sudah selesai.</p>
</div>

{{-- Filter & Pencarian --}}
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-lg-4 col-md-6">
            <div class="d-flex align-items-center w-100 rounded-3" style="background: #0F172A; border: 1px solid #334155;">
                <span class="px-3"><i class="bi bi-search" style="color: #94A3B8;"></i></span>
                <input type="text" name="search" id="riwayatSearch" class="form-control border-0 shadow-none"
                       placeholder="Cari plat nomor, nama pelanggan, atau no. order..."
                       value="{{ request('search') }}" style="background: transparent;">
            </div>
        </div>
        <div class="col-lg-2 col-md-6">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="menunggu" @selected(request('status') === 'menunggu')>Menunggu</option>
                <option value="proses_diagnosa" @selected(request('status') === 'proses_diagnosa')>Proses Diagnosa</option>
                <option value="sedang_diperbaiki" @selected(request('status') === 'sedang_diperbaiki')>Sedang Diperbaiki</option>
                <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
                <option value="dibatalkan" @selected(request('status') === 'dibatalkan')>Batal</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <input type="date" name="from" class="form-control" value="{{ request('from') }}" title="Tanggal mulai" aria-label="Tanggal mulai">
        </div>
        <div class="col-lg-2 col-md-6">
            <input type="date" name="to" class="form-control" value="{{ request('to') }}" title="Tanggal akhir" aria-label="Tanggal akhir">
        </div>
        <div class="col-lg-2 col-12 d-flex gap-2">
            <button type="submit" class="btn flex-grow-1" style="background: #0F172A; border: 1px solid #334155; color: #F8FAFC; font-weight: 600;">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if (request()->anyFilled(['search', 'status', 'from', 'to']))
                <a href="{{ route('admin.riwayat') }}" class="btn" title="Reset filter"
                   style="background: transparent; border: 1px solid #334155; color: #94A3B8;">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Tabel Riwayat --}}
<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0" id="tabelRiwayat">
            <thead style="background: rgba(255, 255, 255, 0.03);">
                <tr>
                    <th style="color: #94A3B8;">No. Order</th>
                    <th style="color: #94A3B8;">Kendaraan</th>
                    <th style="color: #94A3B8;">Pelanggan</th>
                    <th style="color: #94A3B8;">Mekanik</th>
                    <th style="color: #94A3B8;">Tanggal Masuk</th>
                    <th class="text-center" style="color: #94A3B8;">Status</th>
                    <th class="text-end" style="color: #94A3B8;">Total Tagihan</th>
                    <th class="text-end" style="color: #94A3B8;">Aksi</th>
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
                            'dibatalkan' => 'Batal',
                            default => $order->status,
                        };
                        $statusStyle = match ($order->status) {
                            'selesai' => 'background: rgba(22, 163, 74, 0.18); color: #4ade80; border: 1px solid rgba(22, 163, 74, 0.5);',
                            'sedang_diperbaiki' => 'background: rgba(2, 132, 199, 0.18); color: #38bdf8; border: 1px solid rgba(2, 132, 199, 0.5);',
                            'proses_diagnosa' => 'background: rgba(245, 158, 11, 0.18); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.45);',
                            'dibatalkan' => 'background: rgba(239, 68, 68, 0.18); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.45);',
                            default => 'background: rgba(148, 163, 184, 0.18); color: #94A3B8; border: 1px solid rgba(148, 163, 184, 0.4);',
                        };
                    @endphp
                    <tr>
                        <td class="small" style="color: #94A3B8;">{{ $order->order_number }}</td>
                        <td class="small">
                            <span class="fw-semibold" style="color: #F8FAFC;">{{ $order->vehicle->brand }} {{ $order->vehicle->model }}</span><br>
                            <span style="color: #94A3B8;">{{ $order->vehicle->plat_nomor }}</span>
                        </td>
                        <td class="small" style="color: #F8FAFC;">{{ $order->customer->full_name }}</td>
                        <td class="small" style="color: #94A3B8;">{{ $order->mechanic->full_name ?? '-' }}</td>
                        <td class="small" style="color: #94A3B8;">{{ $order->tanggal_masuk?->format('d/m/Y, H:i') }}</td>
                        <td class="text-center">
                            <span class="badge rounded-pill" style="{{ $statusStyle }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="text-end small">
                            @if ($order->invoice)
                                <div class="fw-semibold" style="color: #F8FAFC;">Rp {{ number_format($order->invoice->total_tagihan, 0, ',', '.') }}</div>
                                <span class="small fw-semibold" style="color: {{ $order->invoice->status === 'paid' ? '#4ade80' : '#fbbf24' }};">
                                    {{ $order->invoice->status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            @else
                                <span style="color: #94A3B8;">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                @if ($order->invoice && $order->invoice->status === 'paid')
                                    <a href="{{ route('admin.kasir.nota', $order) }}" class="btn btn-sm"
                                       style="background: transparent; border: 1px solid #94A3B8; color: #F8FAFC;">
                                        <i class="bi bi-receipt"></i> Nota
                                    </a>
                                @elseif ($order->status === 'selesai')
                                    <a href="{{ route('admin.kasir', ['order' => $order->id]) }}" class="btn btn-sm btn-magenta">
                                        <i class="bi bi-cash"></i> Bayar
                                    </a>
                                @else
                                    <span class="small" style="color: #94A3B8;">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4" style="color: #94A3B8;">Tidak ada data yang sesuai filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($riwayat->total() > 0)
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 p-3 border-top" style="border-color: #334155 !important;">
            <span class="small" style="color: #94A3B8;">
                Menampilkan {{ $riwayat->firstItem() }}-{{ $riwayat->lastItem() }} dari {{ $riwayat->total() }} transaksi
            </span>
            <div>{{ $riwayat->links('pagination::bootstrap-5') }}</div>
        </div>
    @endif
</div>

@endsection

@push('styles')
<style>
    .form-control, .form-select {
        background-color: #0F172A;
        border: 1px solid #334155;
        color: #F8FAFC;
    }
    .form-control::placeholder {
        color: #94A3B8;
    }
    .form-control:focus, .form-select:focus {
        background-color: #0F172A;
        border-color: #FF00FF;
        box-shadow: 0 0 0 .2rem rgba(255, 0, 255, .25);
        color: #F8FAFC;
    }
    #riwayatSearch:focus {
        background-color: transparent !important;
    }
    /* Zebra striping tabel riwayat */
    #tabelRiwayat tbody tr:nth-child(odd) {
        background: transparent;
    }
    #tabelRiwayat tbody tr:nth-child(even) {
        background: rgba(255, 255, 255, 0.025);
    }
    #tabelRiwayat tbody tr:hover {
        background: rgba(255, 0, 255, 0.06);
    }
    #tabelRiwayat tbody td {
        border-color: #334155;
    }
    /* Paginasi tema gelap */
    .pagination .page-link {
        background-color: #0F172A;
        border-color: #334155;
        color: #94A3B8;
    }
    .pagination .page-link:hover {
        background-color: #1E293B;
        border-color: #FF00FF;
        color: #FF00FF;
    }
    .pagination .page-item.active .page-link {
        background-color: #FF00FF;
        border-color: #FF00FF;
        color: #ffffff;
    }
    .pagination .page-item.disabled .page-link {
        background-color: #0F172A;
        border-color: #334155;
        color: #475569;
    }
</style>
@endpush
