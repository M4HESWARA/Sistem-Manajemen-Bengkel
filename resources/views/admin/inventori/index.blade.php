@extends('layouts.admin')

@section('title', 'Inventori')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h1 class="h4 fw-bold mb-0">Manajemen Inventori</h1>
        <p class="text-muted small mb-0">Kelola stok suku cadang motor dan perlengkapan bengkel.</p>
    </div>
    <a href="{{ route('admin.inventori.create') }}" style="background-color: #BF24B4; color: #ffffff; border: none; border-radius: 0.7rem; padding: 8px 16px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="bi bi-plus-lg"></i> Tambah Barang Baru
    </a>
</div>

{{-- Kartu Ringkasan --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Total Item</p>
                    <h4 class="fw-bold mb-0">{{ $totalItem }} Item</h4>
                </div>
                <div class="d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;border-radius:.75rem;background:linear-gradient(135deg,var(--bengkel-primary),var(--bengkel-accent));">
                    <i class="bi bi-box-seam text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Stok Kritis</p>
                    <h4 class="fw-bold mb-0 text-danger">{{ $stokKritis }} Item</h4>
                </div>
                <div class="d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;border-radius:.75rem;background:#fee2e2;">
                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Kategori Barang</p>
                    <h4 class="fw-bold mb-0">{{ $totalKategori }} Kategori</h4>
                </div>
                <div class="d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;border-radius:.75rem;background:linear-gradient(135deg,var(--bengkel-primary),var(--bengkel-accent));">
                    <i class="bi bi-tags text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Pencarian --}}
<div class="card p-2 mb-3">
    <form method="GET" class="d-flex align-items-center">
        <span class="px-2"><i class="bi bi-search text-muted"></i></span>
        <input type="text" name="search" class="form-control border-0 shadow-none"
               placeholder="Cari kode atau nama barang..." value="{{ request('search') }}"
               onchange="this.form.submit()">
    </form>
</div>

{{-- Tabel --}}
<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode Part</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th class="text-end">Harga Jual</th>
                    <th class="text-center">Stok</th>
                    <th class="text-end">Aksi</th>
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
                    <tr @if($item->isLowStock()) style="background:#fef2f2;" @endif>
                        <td class="text-muted small">{{ $item->sku }}</td>
                        <td class="fw-semibold">{{ $item->name }}</td>
                        <td class="text-muted small">
                            <i class="bi {{ $catIcon }}" style="color: var(--bengkel-accent);"></i>
                            {{ $item->category->name ?? '-' }}
                        </td>
                        <td class="text-end">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="fw-semibold {{ $item->isLowStock() ? 'text-danger' : '' }}">
                                {{ $item->stok }}
                                @if ($item->isLowStock())
                                    <i class="bi bi-exclamation-triangle-fill small"></i>
                                @endif
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal" data-bs-target="#stokModal{{ $item->id }}" title="Tambah Stok">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                                <a href="{{ route('admin.inventori.edit', $item) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.inventori.toggle-active', $item) }}"
                                      onsubmit="return confirm('{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }} sparepart ini?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-{{ $item->is_active ? 'danger' : 'success' }}"
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
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.inventori.tambah-stok', $item) }}">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah Stok — {{ $item->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-muted small">Stok saat ini: <strong>{{ $item->stok }} {{ $item->satuan }}</strong></p>
                                        <label class="form-label small fw-semibold">Jumlah Tambahan</label>
                                        <input type="number" name="quantity" class="form-control" min="1" required>
                                        <label class="form-label small fw-semibold mt-2">Keterangan (opsional)</label>
                                        <input type="text" name="keterangan" class="form-control" placeholder="mis. Pembelian dari supplier">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-bengkel">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data sparepart.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($spareparts->total() > 0)
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
            <span class="text-muted small">
                Menampilkan {{ $spareparts->firstItem() }}-{{ $spareparts->lastItem() }} dari {{ $spareparts->total() }} item
            </span>
            <div>{{ $spareparts->links() }}</div>
        </div>
    @endif
</div>

@endsection
