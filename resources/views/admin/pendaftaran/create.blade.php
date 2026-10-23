@extends('layouts.admin')

@section('title', 'Pendaftaran Servis')

@section('content')

<div class="mb-4">
    <h1 class="h4 fw-bold mb-0" style="color: #F8FAFC;">Pendaftaran Servis Baru</h1>
    <p class="small mb-0" style="color: #94A3B8;">Daftarkan kendaraan masuk beserta verifikasi fisik awal.</p>
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
                <h2 class="h6 fw-bold mb-3" style="color: #F8FAFC;"><i class="bi bi-scooter" style="color: #FF00FF;"></i> Data Kendaraan & Pelanggan</h2>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" style="color: #F8FAFC;">Nomor Polisi</label>
                    <input type="text" name="plat_nomor" id="platNomor" class="form-control text-uppercase"
                           value="{{ old('plat_nomor') }}" placeholder="mis. L 1234 AB" required autofocus>
                    <div id="platNomorStatus" class="form-text"></div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" style="color: #F8FAFC;">Merk</label>
                        <select name="brand" id="brand" class="form-select">
                            <option value="">— Pilih Merk —</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" style="color: #F8FAFC;">Model</label>
                        <select name="model" id="model" class="form-select" disabled>
                            <option value="">— Pilih Model —</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" style="color: #F8FAFC;">Tahun</label>
                        <input type="number" name="year" id="year" class="form-control" value="{{ old('year') }}" placeholder="mis. 2022">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" style="color: #F8FAFC;">Warna</label>
                        <input type="text" name="color" id="color" class="form-control" value="{{ old('color') }}" placeholder="mis. Hitam">
                    </div>
                </div>

                <hr style="border-color: #334155;">

                <div class="mb-3">
                    <label class="form-label small fw-semibold" style="color: #F8FAFC;">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" id="namaPelanggan" class="form-control"
                           value="{{ old('nama_pelanggan') }}" placeholder="Nama lengkap" required>
                </div>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" style="color: #F8FAFC;">No. HP</label>
                        <input type="text" name="no_hp" id="noHp" class="form-control"
                               value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" style="color: #94A3B8;">Alamat (opsional)</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" value="{{ old('alamat') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Keluhan Awal --}}
        <div class="col-lg-6">
            <div class="card p-4 mb-3">
                <h2 class="h6 fw-bold mb-3" style="color: #F8FAFC;"><i class="bi bi-chat-left-text" style="color: #FF00FF;"></i> Keluhan Awal</h2>
                <textarea name="keluhan_awal" class="form-control" rows="4" required
                          placeholder="Contoh: Ganti oli, servis rutin, rem bunyi...">{{ old('keluhan_awal') }}</textarea>
            </div>

            {{-- Verifikasi Fisik Awal --}}
            <div class="card p-4">
                <h2 class="h6 fw-bold mb-1" style="color: #F8FAFC;"><i class="bi bi-camera" style="color: #FF00FF;"></i> Verifikasi Fisik Awal</h2>
                <p class="small mb-3" style="color: #94A3B8;">Wajib diisi sebagai bukti kondisi kendaraan sebelum servis dimulai.</p>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" style="color: #F8FAFC;">Foto Odometer</label>
                        <div class="file-upload-dark" style="position: relative; display: flex; align-items: center; gap: 0.75rem; min-height: 42px; padding: 0.35rem 0.6rem; background-color: #0F172A; border: 1px solid #334155; border-radius: 0.5rem;">
                            <input type="file" name="odometer_photo" id="odometerPhoto" accept="image/*" class="file-input-hidden" required
                                   style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;"
                                   onchange="previewImage(this, 'previewOdometer'); tampilkanNamaFile(this, 'odometerFileName')">
                            <label for="odometerPhoto" class="file-btn-dark" style="display: inline-flex; align-items: center; background-color: #1E293B; color: #E2E8F0; border: 1px solid #475569; border-radius: 0.375rem; padding: 0.4rem 0.85rem; font-size: 0.85rem; font-weight: 600; line-height: 1.2; margin: 0; white-space: nowrap; cursor: pointer;">
                                <i class="bi bi-upload me-1"></i> Choose File
                            </label>
                            <span id="odometerFileName" class="file-name-text" style="color: #94A3B8; font-size: 0.82rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Belum ada file dipilih</span>
                        </div>
                        <img id="previewOdometer" class="mt-2 rounded border d-none" style="max-height:120px; border-color: #334155 !important;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" style="color: #F8FAFC;">Kilometer (Odometer)</label>
                        <input type="number" name="odometer_reading" class="form-control"
                               value="{{ old('odometer_reading') }}" placeholder="mis. 12500" required min="0">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" style="color: #F8FAFC;">Foto Kondisi Bensin (opsional)</label>
                        <div class="file-upload-dark" style="position: relative; display: flex; align-items: center; gap: 0.75rem; min-height: 42px; padding: 0.35rem 0.6rem; background-color: #0F172A; border: 1px solid #334155; border-radius: 0.5rem;">
                            <input type="file" name="fuel_photo" id="fuelPhoto" accept="image/*" class="file-input-hidden"
                                   style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;"
                                   onchange="previewImage(this, 'previewFuel'); tampilkanNamaFile(this, 'fuelFileName')">
                            <label for="fuelPhoto" class="file-btn-dark" style="display: inline-flex; align-items: center; background-color: #1E293B; color: #E2E8F0; border: 1px solid #475569; border-radius: 0.375rem; padding: 0.4rem 0.85rem; font-size: 0.85rem; font-weight: 600; line-height: 1.2; margin: 0; white-space: nowrap; cursor: pointer;">
                                <i class="bi bi-upload me-1"></i> Choose File
                            </label>
                            <span id="fuelFileName" class="file-name-text" style="color: #94A3B8; font-size: 0.82rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Belum ada file dipilih</span>
                        </div>
                        <img id="previewFuel" class="mt-2 rounded border d-none" style="max-height:120px; border-color: #334155 !important;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" style="color: #F8FAFC;">Kapasitas Bensin</label>
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

                <label class="form-label small fw-semibold" style="color: #F8FAFC;">Checklist Indikator & Lampu</label>
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
                                <label class="form-check-label small" for="{{ $field }}" style="color: #F8FAFC;">{{ $label }} OK</label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <label class="form-label small fw-semibold" style="color: #94A3B8;">Catatan Kondisi (opsional)</label>
                <textarea name="catatan_kondisi" class="form-control" rows="2"
                          placeholder="Catatan tambahan, mis. lecet body, dsb.">{{ old('catatan_kondisi') }}</textarea>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary" style="border-color: #334155; color: #94A3B8;">Batal</a>
        <button type="submit" class="btn btn-magenta px-4" style="background-color: #FF00FF; border: none; color: #fff; font-weight: 700; box-shadow: 0 0 20px rgba(255, 0, 255, 0.45);">
            <i class="bi bi-check-circle"></i> Daftarkan Servis
        </button>
    </div>
</form>

@endsection

@push('styles')
<style>
    .text-uppercase { text-transform: uppercase; }
    html { color-scheme: dark; }
    .form-control,
    .form-select,
    textarea.form-control {
        background-color: #0F172A !important;
        background-image: none;
        border: 1px solid #334155 !important;
        color: #F8FAFC !important;
        color-scheme: dark;
    }
    /* Panah dropdown terang agar terlihat di latar gelap */
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2394A3B8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
        background-repeat: no-repeat !important;
        background-position: right 0.75rem center !important;
        background-size: 16px 12px !important;
        padding-right: 2.25rem;
    }
    .form-control::placeholder,
    .form-control::-webkit-input-placeholder,
    .form-select::placeholder,
    textarea.form-control::placeholder {
        color: #94A3B8 !important;
        opacity: 1 !important;
    }
    .form-select option {
        background-color: #1E293B !important;
        color: #F8FAFC !important;
    }
    .form-select option:checked,
    .form-select option:hover {
        background-color: #334155 !important;
        color: #FFFFFF !important;
    }
    .form-select:disabled,
    .form-control:disabled,
    .form-select[disabled] {
        background-color: #1E293B !important;
        border-color: #334155 !important;
        color: #94A3B8 !important;
        opacity: 1;
        cursor: not-allowed;
    }
    .form-select:disabled {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748B' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }
    .form-control:focus, .form-select:focus {
        background-color: #0F172A !important;
        border-color: #FF00FF !important;
        box-shadow: 0 0 0 .2rem rgba(255, 0, 255, .25);
        color: #F8FAFC !important;
    }
    .form-check-input {
        background-color: #0F172A;
        border-color: #334155;
    }
    .form-check-input:checked {
        background-color: #FF00FF;
        border-color: #FF00FF;
    }
    .form-check-input:focus {
        border-color: #FF00FF;
        box-shadow: 0 0 0 .2rem rgba(255, 0, 255, .25);
    }
    /* Upload file kustom bertema gelap */
    .file-upload-dark {
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-height: 42px;
        padding: 0.35rem 0.6rem;
        background-color: #0F172A;
        border: 1px solid #334155;
        border-radius: 0.5rem;
    }
    .file-input-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
    .file-btn-dark {
        display: inline-flex;
        align-items: center;
        background-color: #1E293B;
        color: #E2E8F0;
        border: 1px solid #475569;
        border-radius: 0.375rem;
        padding: 0.4rem 0.85rem;
        font-size: 0.85rem;
        font-weight: 600;
        line-height: 1.2;
        margin: 0;
        white-space: nowrap;
        cursor: pointer;
        transition: background-color .2s ease, border-color .2s ease, color .2s ease;
    }
    .file-btn-dark:hover {
        background-color: #334155;
        border-color: #64748B;
        color: #FFFFFF;
    }
    .file-input-hidden:focus-visible + .file-btn-dark {
        outline: 2px solid #FF00FF;
        outline-offset: 2px;
    }
    .file-name-text {
        color: #94A3B8;
        font-size: 0.82rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endpush

@push('scripts')
<script>
const MOTOR_DATA = {
    Honda: [
        'BeAT', 'BeAT Street', 'Vario 125', 'Vario 160', 'Scoopy', 'Genio',
        'PCX 160', 'ADV 160', 'Supra X 125', 'Supra GTR 150', 'Revo', 'Revo Fit',
        'Blade 110', 'CB150R StreetFire', 'CBR150R', 'CBR250RR', 'CRF150L',
        'CRF250L', 'CRF450L', 'CT125', 'Monkey', 'Super Cub C125', 'Verza 150',
        'MegaPro Mono', 'Tiger', 'CS1', 'Vario Techno 125', 'Spacy', 'Vario 110',
    ],
    Yamaha: [
        'Mio M3', 'Mio S', 'Mio Z', 'Mio Gear 125', 'GT 125', 'Lexi', 'Lexi LX',
        'NMAX', 'NMAX Connected', 'Aerox 155', 'Aerox 155 Connected', 'Fazzio',
        'Grand Filano', 'FreeGO', 'SMAX', 'MX King 150', 'Jupiter Z1',
        'Jupiter MX King', 'Vixion', 'Vixion R', 'All New Vixion', 'R15',
        'R15M', 'R25', 'XSR 155', 'WR 155R', 'WR 250F', 'XTZ 125', 'Byson',
        'Scorpio Z', 'Force 150', 'Aria 110',
    ],
    Suzuki: [
        'Satria F150', 'Satria F150 Final Edition', 'NEX II', 'NEX Crossover',
        'Address', 'Address Playful', 'GSX-R150', 'GSX-S150', 'Burgman Street 125EX',
        'Thunder 125', 'Thunder 150', 'Smash', 'Shogun', 'Shogun Axelo',
        'Belang 150', 'R150', 'Inazuma 250', 'V-Strom 250', 'Hayabusa',
        'GSX-R1000', 'Lets', 'Jelata 110',
    ],
    Kawasaki: [
        'Ninja 250', 'Ninja 250 SL', 'Ninja ZX-25R', 'Ninja ZX-6R', 'Ninja 400',
        'Ninja H2', 'KLX 150', 'KLX 150BF', 'KLX 230', 'KLX 250',
        'D-Tracker 150', 'D-Tracker X', 'W175', 'W175 Cafe', 'W800',
        'Z250', 'Z900', 'Z125 Pro', 'Versys 650', 'Versys 1000',
        'Vulcan S', 'Eliminator', 'ZX-10R', 'ZX-14R', 'KX450', 'KLX230 SM',
    ],
    Vespa: [
        'Sprint 150', 'Primavera 150', 'GTS 150', 'GTS 300', 'ET4 125',
        'LX 125', 'PX 150', 'Modern Classic',
    ],
    'Lainnya': [],
};

const brandSelect = document.getElementById('brand');
const modelSelect = document.getElementById('model');

Object.keys(MOTOR_DATA).forEach(brand => {
    brandSelect.add(new Option(brand, brand));
});

function renderModels(brand) {
    const models = MOTOR_DATA[brand] || [];
    modelSelect.innerHTML = '';
    modelSelect.add(new Option('— Pilih Model —', ''));
    models.forEach(m => modelSelect.add(new Option(m, m)));
    modelSelect.disabled = !brand || models.length === 0;
}

brandSelect.addEventListener('change', function () {
    renderModels(this.value);
});

// Set merk+model sekaligus (untuk old() & auto-fill), termasuk nilai di luar daftar
function setBrandModel(brand, model) {
    if (brand && !(brand in MOTOR_DATA)) {
        brandSelect.add(new Option(brand, brand));
    }
    brandSelect.value = brand ?? '';
    renderModels(brandSelect.value);
    if (model && ![...modelSelect.options].some(o => o.value === model)) {
        modelSelect.add(new Option(model, model));
    }
    modelSelect.value = model ?? '';
}

// Prefill dari input lama saat validasi gagal
setBrandModel(@json(old('brand')), @json(old('model')));

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
        preview.classList.remove('d-none');
    }
}

function tampilkanNamaFile(input, targetId) {
    const target = document.getElementById(targetId);
    if (!target) { return; }
    target.textContent = (input.files && input.files[0])
        ? input.files[0].name
        : 'Belum ada file dipilih';
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
                    setBrandModel(data.vehicle.brand ?? '', data.vehicle.model ?? '');
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
