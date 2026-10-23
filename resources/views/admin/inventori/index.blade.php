@extends('layouts.admin')

@section('title', 'Inventori')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h4 fw-bold mb-0" style="color: #F8FAFC;">Manajemen Inventori</h1>
        <p class="small mb-0" style="color: #94A3B8;">Kelola stok suku cadang motor dan perlengkapan bengkel.</p>
    </div>
    <a href="{{ route('admin.inventori.create') }}" class="btn btn-magenta">
        <i class="bi bi-plus-lg"></i> Tambah Barang Baru
    </a>
</div>

{{-- Kartu Ringkasan --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="small mb-1" style="color: #94A3B8;">Total Item</p>
                    <h4 class="fw-bold mb-0" style="color: #F8FAFC;">{{ $totalItem }} Item</h4>
                </div>
                <div class="d-flex align-items-center justify-content-center"
                     style="width:46px;height:46px;border-radius:.75rem;background:rgba(255, 0, 255, 0.15);">
                    <i class="bi bi-box-seam fs-5" style="color: #FF00FF;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="small mb-1" style="color: #94A3B8;">Stok Kritis</p>
                    <h4 class="fw-bold mb-0" style="color: #ef4444;">{{ $stokKritis }} Item</h4>
                </div>
                <div class="d-flex align-items-center justify-content-center"
                     style="width:46px;height:46px;border-radius:.75rem;background:rgba(239, 68, 68, 0.18);">
                    <i class="bi bi-exclamation-triangle-fill fs-5" style="color: #ef4444;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="small mb-1" style="color: #94A3B8;">Kategori Barang</p>
                    <h4 class="fw-bold mb-0" style="color: #F8FAFC;">{{ $totalKategori }} Kategori</h4>
                </div>
                <div class="d-flex align-items-center justify-content-center"
                     style="width:46px;height:46px;border-radius:.75rem;background:rgba(255, 0, 255, 0.15);">
                    <i class="bi bi-tags fs-5" style="color: #FF00FF;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Pencarian --}}
<div class="card p-1 mb-3">
    <form method="GET" class="d-flex align-items-center w-100">
        <span class="px-3"><i class="bi bi-search" style="color: #94A3B8;"></i></span>
        <input type="text" name="search" id="inventoriSearch" class="form-control border-0 shadow-none"
               placeholder="Cari kode atau nama barang..." value="{{ request('search') }}"
               onchange="this.form.submit()">
    </form>
</div>

{{-- Tabel --}}
<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead style="background: rgba(255, 255, 255, 0.02);">
                <tr>
                    <th style="color: #94A3B8;">Kode Part</th>
                    <th style="color: #94A3B8;">Nama Barang</th>
                    <th style="color: #94A3B8;">Kategori</th>
                    <th class="text-end" style="color: #94A3B8;">Harga Jual</th>
                    <th class="text-center" style="color: #94A3B8;">Stok</th>
                    <th class="text-end" style="color: #94A3B8;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($spareparts as $item)
                    @php
                        $catName = strtolower($item->category->name ?? '');
                        $catIcon = match (true) {
                            str_contains($catName, 'oli') || str_contains($catName, 'pelumas') => 'bi-droplet-fill',
                            str_contains($catName, 'ban') => 'bi-circle',
                            str_contains($catName, 'listrik') || str_contains($catName, 'aki') => 'bi-lightning-charge-fill',
                            str_contains($catName, 'mesin') => 'bi-gear-fill',
                            default => 'bi-wrench-adjustable',
                        };
                    @endphp
                    <tr @if($item->isLowStock()) style="background: rgba(239, 68, 68, 0.12);" @endif>
                        <td class="small" style="color: #94A3B8;">{{ $item->sku }}</td>
                        <td class="fw-semibold" style="color: #F8FAFC;">{{ $item->name }}</td>
                        <td class="small" style="color: #94A3B8;">
                            <i class="bi {{ $catIcon }}" style="color: #FF00FF;"></i>
                            {{ $item->category->name ?? '-' }}
                        </td>
                        <td class="text-end" style="color: #F8FAFC;">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="fw-semibold {{ $item->isLowStock() ? '' : '' }}" style="color: {{ $item->isLowStock() ? '#ef4444' : '#F8FAFC' }};">
                                {{ $item->stok }}
                                @if ($item->isLowStock())
                                    <i class="bi bi-exclamation-triangle-fill small"></i>
                                @endif
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-sm" style="background: transparent; border: 1px solid #334155; color: #94A3B8;"
                                        data-bs-toggle="modal" data-bs-target="#stokModal{{ $item->id }}" title="Tambah Stok">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                                <a href="{{ route('admin.inventori.edit', $item) }}" class="btn btn-sm" style="background: transparent; border: 1px solid #334155; color: #94A3B8;" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.inventori.toggle-active', $item) }}"
                                      onsubmit="return confirm('{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }} sparepart ini?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background: transparent; border: 1px solid #334155; color: {{ $item->is_active ? '#ef4444' : '#22c55e' }};"
                                            title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi bi-{{ $item->is_active ? 'x-circle' : 'check-circle' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Tambah Stok --}}
                    <div class="modal fade" id="stokModal{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="background: #1E293B; border: 1px solid #334155;">
                                <form method="POST" action="{{ route('admin.inventori.tambah-stok', $item) }}">
                                    @csrf
                                    <div class="modal-header" style="border-bottom: 1px solid #334155;">
                                        <h5 class="modal-title" style="color: #F8FAFC;">Tambah Stok — {{ $item->name }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body" style="color: #F8FAFC;">
                                        <p class="small" style="color: #94A3B8;">Stok saat ini: <strong style="color: #F8FAFC;">{{ $item->stok }} {{ $item->satuan }}</strong></p>
                                        <label class="form-label small fw-semibold" style="color: #F8FAFC;">Jumlah Tambahan</label>
                                        <input type="number" name="quantity" class="form-control" min="1" required>
                                        <label class="form-label small fw-semibold mt-2" style="color: #94A3B8;">Keterangan (opsional)</label>
                                        <input type="text" name="keterangan" class="form-control" placeholder="mis. Pembelian dari supplier">
                                    </div>
                                    <div class="modal-footer" style="border-top: 1px solid #334155;">
                                        <button type="button" class="btn btn-outline-secondary" style="border-color: #334155; color: #94A3B8;" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-magenta">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4" style="color: #94A3B8;">Belum ada data sparepart.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($spareparts->total() > 0)
        <div class="d-flex justify-content-between align-items-center p-3 border-top" style="border-color: #334155 !important;">
            <span class="small" style="color: #94A3B8;">
                Menampilkan {{ $spareparts->firstItem() }}-{{ $spareparts->lastItem() }} dari {{ $spareparts->total() }} item
            </span>
            <div>{{ $spareparts->links() }}</div>
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
    #inventoriSearch {
        background-color: #0F172A;
        border: 1px solid #334155;
        border-radius: 10px;
        color: #F8FAFC;
        padding: 0.55rem 0.8rem;
    }
    #inventoriSearch:focus {
        background-color: #0F172A;
        border-color: #FF00FF;
        box-shadow: 0 0 0 .2rem rgba(255, 0, 255, .25);
        color: #F8FAFC;
    }
</style>
@endpush
