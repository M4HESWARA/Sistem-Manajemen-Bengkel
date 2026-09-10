<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sparepart;
use App\Models\SparepartCategory;
use App\Models\SparepartStockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InventoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Sparepart::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->boolean('low_stock_only')) {
            $query->lowStock();
        }

        $spareparts = $query->orderBy('name')->paginate(15)->withQueryString();
        $categories = SparepartCategory::orderBy('name')->get();

        $totalItem = Sparepart::count();
        $stokKritis = Sparepart::lowStock()->count();
        $totalKategori = SparepartCategory::count();

        return view('admin.inventori.index', compact(
            'spareparts', 'categories', 'totalItem', 'stokKritis', 'totalKategori'
        ));
    }

    public function create()
    {
        $categories = SparepartCategory::orderBy('name')->get();

        return view('admin.inventori.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $categoryId = $this->resolveCategory($request);

        Sparepart::create([
            'sku' => strtoupper($data['sku']),
            'name' => $data['name'],
            'category_id' => $categoryId,
            'harga_beli' => $data['harga_beli'],
            'harga_jual' => $data['harga_jual'],
            'stok' => $data['stok'] ?? 0,
            'stok_minimum' => $data['stok_minimum'],
            'satuan' => $data['satuan'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.inventori')->with('success', "Sparepart '{$data['name']}' berhasil ditambahkan.");
    }

    public function edit(Sparepart $sparepart)
    {
        $categories = SparepartCategory::orderBy('name')->get();

        return view('admin.inventori.edit', compact('sparepart', 'categories'));
    }

    public function update(Request $request, Sparepart $sparepart): RedirectResponse
    {
        $data = $this->validateData($request, $sparepart->id);
        $categoryId = $this->resolveCategory($request);

        $sparepart->update([
            'sku' => strtoupper($data['sku']),
            'name' => $data['name'],
            'category_id' => $categoryId,
            'harga_beli' => $data['harga_beli'],
            'harga_jual' => $data['harga_jual'],
            'stok_minimum' => $data['stok_minimum'],
            'satuan' => $data['satuan'],
        ]);

        return redirect()->route('admin.inventori')->with('success', "Sparepart '{$sparepart->name}' berhasil diperbarui.");
    }

    public function toggleActive(Sparepart $sparepart): RedirectResponse
    {
        $sparepart->update(['is_active' => ! $sparepart->is_active]);

        $status = $sparepart->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Sparepart '{$sparepart->name}' berhasil {$status}.");
    }

    public function tambahStok(Request $request, Sparepart $sparepart): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $sparepart->increment('stok', $request->quantity);

        SparepartStockMovement::create([
            'sparepart_id' => $sparepart->id,
            'movement_type' => 'masuk',
            'quantity' => $request->quantity,
            'keterangan' => $request->keterangan ?: 'Penambahan stok manual',
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', "Stok '{$sparepart->name}' berhasil ditambah {$request->quantity} {$sparepart->satuan}.");
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'sku' => ['required', 'string', 'max:50', Rule::unique('spareparts', 'sku')->ignore($ignoreId)],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['nullable', 'exists:sparepart_categories,id'],
            'kategori_baru' => ['nullable', 'string', 'max:100'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'stok' => ['nullable', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:20'],
        ]);
    }

    private function resolveCategory(Request $request): ?int
    {
        if ($request->filled('kategori_baru')) {
            $category = SparepartCategory::firstOrCreate(['name' => trim($request->kategori_baru)]);

            return $category->id;
        }

        return $request->input('category_id') ?: null;
    }
}
