<?php

namespace App\Observers;

use App\Exceptions\InsufficientStockException;
use App\Models\ServiceOrderItem;
use App\Models\Sparepart;
use App\Models\SparepartStockMovement;
use Illuminate\Support\Facades\DB;

/**
 * Menggantikan trigger database utk potong stok otomatis + validasi stok cukup.
 *
 * PENTING: pemanggilan ServiceOrderItem::create(...) di Controller/Service SEBAIKNYA
 * dibungkus DB::transaction() supaya penguncian baris (lockForUpdate) di sini benar-benar
 * atomik terhadap request bersamaan dari mekanik lain. Contoh pemakaian:
 *
 *   DB::transaction(function () use ($data) {
 *       ServiceOrderItem::create($data);
 *   });
 */
class ServiceOrderItemObserver
{
    /**
     * Dipanggil sebelum record disimpan ke database.
     * Mengunci baris sparepart, mengecek stok, lalu menguranginya.
     *
     * @throws InsufficientStockException
     */
    public function creating(ServiceOrderItem $item): void
    {
        DB::transaction(function () use ($item) {
            $sparepart = Sparepart::where('id', $item->sparepart_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($sparepart->stok < $item->quantity) {
                throw new InsufficientStockException(
                    sparepartName: $sparepart->name,
                    available: $sparepart->stok,
                    requested: $item->quantity,
                );
            }

            // Snapshot harga jual saat transaksi, jika belum diisi manual
            $item->harga_satuan ??= $sparepart->harga_jual;

            $sparepart->decrement('stok', $item->quantity);
        });
    }

    /**
     * Dipanggil setelah record berhasil disimpan.
     * Catat pergerakan stok keluar untuk audit trail.
     */
    public function created(ServiceOrderItem $item): void
    {
        SparepartStockMovement::create([
            'sparepart_id' => $item->sparepart_id,
            'movement_type' => 'keluar',
            'quantity' => $item->quantity,
            'service_order_id' => $item->service_order_id,
            'keterangan' => 'Pemakaian pada order servis #' . $item->service_order_id,
            'created_by' => $item->input_by,
        ]);
    }

    /**
     * Kalau item pemakaian sparepart dihapus (mis. salah input), kembalikan stok.
     */
    public function deleted(ServiceOrderItem $item): void
    {
        DB::transaction(function () use ($item) {
            Sparepart::where('id', $item->sparepart_id)
                ->lockForUpdate()
                ->increment('stok', $item->quantity);

            SparepartStockMovement::create([
                'sparepart_id' => $item->sparepart_id,
                'movement_type' => 'masuk',
                'quantity' => $item->quantity,
                'service_order_id' => $item->service_order_id,
                'keterangan' => 'Pembatalan/koreksi pemakaian pada order servis #' . $item->service_order_id,
                'created_by' => $item->input_by,
            ]);
        });
    }
}
