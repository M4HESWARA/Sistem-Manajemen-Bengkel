@extends('layouts.admin')

@section('title', 'Pendaftaran Servis')

@section('content')

<div class="mb-4">
    <h1 class="h4 fw-bold mb-0">Pendaftaran Servis Baru</h1>
    <p class="text-muted small mb-0">Daftarkan kendaraan masuk beserta verifikasi fisik awal.</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Ada isian yang belum lengkap:</strong>
        <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.pendaftaran.store') }}" enctype="multipart/form-data" id="formPendaftaran">
    @csrf

    <div class="row g-3">
        {{-- Data Kendaraan & Pelanggan --}}
        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <h2 class="h6 fw-bold mb-3"><i class="bi bi-scooter"></i> Data Kendaraan & Pelanggan</h2>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nomor Polisi</label>
                    <input type="text" name="plat_nomor" id="platNomor" class="form-control text-uppercase"
                           value="{{ old('plat_nomor') }}" placeholder="mis. L 1234 AB" required autofocus>
                    <div id="platNomorStatus" class="form-text"></div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Merk</label>
                        <input type="text" name="brand" id="brand" class="form-control" value="{{ old('brand') }}" placeholder="mis. Honda">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Model</label>
                        <input type="text" name="model" id="model" class="form-control" value="{{ old('model') }}" placeholder="mis. Vario">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Tahun</label>
                        <input type="number" name="year" id="year" class="form-control" value="{{ old('year') }}" placeholder="mis. 2022">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Warna</label>
                        <input type="text" name="color" id="color" class="form-control" value="{{ old('color') }}" placeholder="mis. Hitam">
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" id="namaPelanggan" class="form-control"
                           value="{{ old('nama_pelanggan') }}" placeholder="Nama lengkap" required>
                </div>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">No. HP</label>
                        <input type="text" name="no_hp" id="noHp" class="form-control"
                               value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Alamat (opsional)</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" value="{{ old('alamat') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Keluhan Awal --}}
        <div class="col-lg-6">
            <div class="card p-4 mb-3">
                <h2 class="h6 fw-bold mb-3"><i class="bi bi-chat-left-text"></i> Keluhan Awal</h2>
                <textarea name="keluhan_awal" class="form-control" rows="4" required
                          placeholder="Contoh: Ganti oli, servis rutin, rem bunyi...">{{ old('keluhan_awal') }}</textarea>
            </div>

            {{-- Verifikasi Fisik Awal --}}
            <div class="card p-4">
                <h2 class="h6 fw-bold mb-1"><i class="bi bi-camera"></i> Verifikasi Fisik Awal</h2>
                <p class="text-muted small mb-3">Wajib diisi sebagai bukti kondisi kendaraan sebelum servis dimulai.</p>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Foto Odometer</label>
                        <input type="file" name="odometer_photo" accept="image/*" class="form-control" required
                               onchange="previewImage(this, 'previewOdometer')">
                        <img id="previewOdometer" class="mt-2 rounded border d-none" style="max-height:120px;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Kilometer (Odometer)</label>
                        <input type="number" name="odometer_reading" class="form-control"
                               value="{{ old('odometer_reading') }}" placeholder="mis. 12500" required min="0">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Foto Kondisi Bensin (opsional)</label>
                        <input type="file" name="fuel_photo" accept="image/*" class="form-control"
                               onchange="previewImage(this, 'previewFuel')">
                        <img id="previewFuel" class="mt-2 rounded border d-none" style="max-height:120px;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Kapasitas Bensin</label>
                        <select name="fuel_level" class="form-select" required>
                            <option value="" disabled selected>Pilih kondisi</option>
                            <option value="empty">Kosong (Empty)</option>
                            <option value="quarter">Seperempat</option>
                            <option value="half">Setengah</option>
                            <option value="three_quarter">Tiga Perempat</option>
                            <option value="full">Penuh (Full)</option>
                        </select>
                    </div>
                </div>

                <label class="form-label small fw-semibold">Checklist Indikator & Lampu</label>
                <div class="row row-cols-2 row-cols-md-3 g-2 mb-3">
                    @foreach ([
                        'lampu_utama_ok' => 'Lampu Utama',
                        'lampu_sein_ok' => 'Lampu Sein',
                        'check_engine_ok' => 'Check Engine',
                        'lampu_abs_ok' => 'ABS',
                        'lampu_oli_ok' => 'Indikator Oli',
                    ] as $field => $label)
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="{{ $field }}" id="{{ $field }}" checked>
                                <label class="form-check-label small" for="{{ $field }}">{{ $label }} OK</label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <label class="form-label small fw-semibold">Catatan Kondisi (opsional)</label>
                <textarea name="catatan_kondisi" class="form-control" rows="2"
                          placeholder="Catatan tambahan, mis. lecet body, dsb.">{{ old('catatan_kondisi') }}</textarea>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-bengkel px-4">
            <i class="bi bi-check-circle"></i> Daftarkan Servis
        </button>
    </div>
</form>

@endsection

@push('styles')
<style>
    .text-uppercase { text-transform: uppercase; }
</style>
@endpush

@push('scripts')
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
        preview.classList.remove('d-none');
    }
}

// Cari otomatis data kendaraan saat nomor polisi selesai diketik (debounce)
let platTimeout;
document.getElementById('platNomor').addEventListener('input', function () {
    clearTimeout(platTimeout);
    const value = this.value.trim();
    const statusEl = document.getElementById('platNomorStatus');

    if (value.length < 4) {
        statusEl.innerHTML = '';
        return;
    }

    platTimeout = setTimeout(() => {
        statusEl.innerHTML = '<span class="text-muted">Mencari data kendaraan...</span>';

        fetch(`{{ route('admin.pendaftaran.cari-kendaraan') }}?plat_nomor=${encodeURIComponent(value)}`)
            .then(res => res.json())
            .then(data => {
                if (data.found) {
                    document.getElementById('brand').value = data.vehicle.brand ?? '';
                    document.getElementById('model').value = data.vehicle.model ?? '';
                    document.getElementById('year').value = data.vehicle.year ?? '';
                    document.getElementById('color').value = data.vehicle.color ?? '';
                    document.getElementById('namaPelanggan').value = data.customer.full_name ?? '';
                    document.getElementById('noHp').value = data.customer.phone ?? '';
                    document.getElementById('alamat').value = data.customer.address ?? '';

                    statusEl.innerHTML = `<span class="text-success"><i class="bi bi-check-circle-fill"></i> Kendaraan ditemukan — sudah servis ${data.riwayat_count}x sebelumnya. Data otomatis terisi.</span>`;
                } else {
                    statusEl.innerHTML = '<span class="text-muted"><i class="bi bi-info-circle"></i> Kendaraan baru, silakan lengkapi data di bawah.</span>';
                }
            })
            .catch(() => {
                statusEl.innerHTML = '<span class="text-danger">Gagal mencari data, coba lagi.</span>';
            });
    }, 500);
});
</script>
@endpush
