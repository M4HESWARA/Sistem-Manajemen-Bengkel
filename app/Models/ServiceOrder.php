<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'vehicle_id',
        'customer_id',
        'admin_id',
        'mechanic_id',
        'keluhan_awal',
        'status',
        'priority',
        'queue_number',
        'odometer_masuk',
        'catatan_solusi',
        'tanggal_masuk',
        'tanggal_selesai',
    ];

    // Default di level PHP, selaras dengan default kolom di migration.
    // Supaya $order->status sudah terisi 'menunggu' walau belum di-refresh dari DB.
    protected $attributes = [
        'status' => 'menunggu',
        'priority' => 0,
    ];

    protected function casts(): array
    {
        return [
            'tanggal_masuk' => 'datetime',
            'tanggal_selesai' => 'datetime',
        ];
    }

    // --- Relasi ---

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function items()
    {
        return $this->hasMany(ServiceOrderItem::class);
    }

    public function jasaItems()
    {
        return $this->hasMany(ServiceJasaItem::class);
    }

    public function verification()
    {
        return $this->hasOne(VehicleInitialVerification::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(ServiceStatusHistory::class)->latest('changed_at');
    }

    // --- Helper ---

    public function totalSparepart(): float
    {
        return (float) $this->items->sum(fn ($item) => $item->quantity * $item->harga_satuan);
    }

    public function totalJasa(): float
    {
        return (float) $this->jasaItems->sum('biaya');
    }

    // Generate nomor order berformat SO-YYYYMMDD-XXXX
    public static function generateOrderNumber(): string
    {
        $prefix = 'SO-' . now()->format('Ymd') . '-';
        $lastNumber = static::where('order_number', 'like', $prefix . '%')
            ->orderByDesc('order_number')
            ->value('order_number');

        $sequence = $lastNumber ? ((int) substr($lastNumber, -4)) + 1 : 1;

        return $prefix . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    // Scope: order yang khusus jadi tugas seorang mekanik (mekanik tidak boleh lihat punya orang lain)
    public function scopeForMechanic($query, int $mechanicId)
    {
        return $query->where('mechanic_id', $mechanicId);
    }
}