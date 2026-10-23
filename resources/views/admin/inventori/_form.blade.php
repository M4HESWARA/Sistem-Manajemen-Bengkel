<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Kode SKU</label>
        <input type="text" name="sku" class="form-control text-uppercase w-100"
               value="{{ old('sku', $sparepart->sku ?? '') }}" required placeholder="mis. OLI-001">
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Nama Sparepart</label>
        <input type="text" name="name" class="form-control w-100"
               value="{{ old('name', $sparepart->name ?? '') }}" required placeholder="mis. Oli Mesin 1L">
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Kategori</label>
        <select name="category_id" class="form-select w-100">
            <option value="">— Tanpa kategori —</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    @selected(old('category_id', $sparepart->category_id ?? null) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Atau Buat Kategori Baru</label>
        <input type="text" name="kategori_baru" class="form-control w-100"
               placeholder="Kosongkan jika pakai kategori di atas" value="{{ old('kategori_baru') }}">
    </div>
</div>

<div class="row g-3 align-items-end price-row mb-3">
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Harga Beli (Rp)</label>
        <div class="input-group w-100">
            <span class="input-group-text">Rp</span>
            <input type="number" name="harga_beli" class="form-control w-100" min="0"
                   value="{{ old('harga_beli', $sparepart->harga_beli ?? '') }}" required placeholder="0">
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Harga Jual (Rp)</label>
        <div class="input-group w-100">
            <span class="input-group-text">Rp</span>
            <input type="number" name="harga_jual" class="form-control w-100" min="0"
                   value="{{ old('harga_jual', $sparepart->harga_jual ?? '') }}" required placeholder="0">
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Satuan</label>
        @php
            $satuanOptions = ['pcs', 'botol', 'set', 'liter', 'unit', 'dus', 'box', 'kaleng', 'roll', 'meter', 'kg', 'gram', 'pasang', 'batang', 'lembar'];
            $satuanTerpilih = old('satuan', $sparepart->satuan ?? 'pcs');
            if ($satuanTerpilih && !in_array($satuanTerpilih, $satuanOptions, true)) {
                $satuanOptions[] = $satuanTerpilih;
            }
        @endphp
        <select name="satuan" class="form-select w-100" required>
            @foreach ($satuanOptions as $satuan)
                <option value="{{ $satuan }}" @selected($satuanTerpilih === $satuan)>{{ $satuan }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row g-3">
    @unless(isset($sparepart))
        <div class="col-md-6">
            <label class="form-label small fw-semibold">Stok Awal</label>
            <input type="number" name="stok" class="form-control w-100" min="0" value="{{ old('stok', 0) }}">
        </div>
    @endunless
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Stok Minimum (Batas Peringatan Menipis)</label>
        <input type="number" name="stok_minimum" class="form-control w-100" min="0"
               value="{{ old('stok_minimum', $sparepart->stok_minimum ?? 5) }}" required>
    </div>
</div>

<style>.text-uppercase { text-transform: uppercase; }</style>
