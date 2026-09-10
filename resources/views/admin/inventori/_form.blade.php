<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Kode SKU</label>
        <input type="text" name="sku" class="form-control text-uppercase"
               value="{{ old('sku', $sparepart->sku ?? '') }}" required placeholder="mis. OLI-001">
    </div>
    <div class="col-md-8">
        <label class="form-label small fw-semibold">Nama Sparepart</label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name', $sparepart->name ?? '') }}" required placeholder="mis. Oli Mesin 1L">
    </div>

    <div class="col-md-6">
        <label class="form-label small fw-semibold">Kategori (pilih yang sudah ada)</label>
        <select name="category_id" class="form-select">
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
        <label class="form-label small fw-semibold">Atau buat kategori baru</label>
        <input type="text" name="kategori_baru" class="form-control"
               placeholder="Kosongkan jika pakai kategori di atas" value="{{ old('kategori_baru') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label small fw-semibold">Harga Beli</label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" name="harga_beli" class="form-control" min="0"
                   value="{{ old('harga_beli', $sparepart->harga_beli ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Harga Jual</label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" name="harga_jual" class="form-control" min="0"
                   value="{{ old('harga_jual', $sparepart->harga_jual ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Satuan</label>
        <input type="text" name="satuan" class="form-control"
               value="{{ old('satuan', $sparepart->satuan ?? 'pcs') }}" required placeholder="pcs, liter, set">
    </div>

    @unless(isset($sparepart))
        <div class="col-md-6">
            <label class="form-label small fw-semibold">Stok Awal</label>
            <input type="number" name="stok" class="form-control" min="0" value="{{ old('stok', 0) }}">
        </div>
    @endunless
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Stok Minimum (batas peringatan menipis)</label>
        <input type="number" name="stok_minimum" class="form-control" min="0"
               value="{{ old('stok_minimum', $sparepart->stok_minimum ?? 5) }}" required>
    </div>
</div>

<style>.text-uppercase { text-transform: uppercase; }</style>
