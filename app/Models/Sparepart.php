<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'category_id',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimum',
        'satuan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga_beli' => 'decimal:2',
            'harga_jual' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(SparepartCategory::class, 'category_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(SparepartStockMovement::class);
    }

    public function orderItems()
    {
        return $this->hasMany(ServiceOrderItem::class);
    }

    public function isLowStock(): bool
    {
        return $this->stok <= $this->stok_minimum;
    }

    // Scope untuk peringatan stok menipis (dipakai admin)
    public function scopeLowStock($query)
    {
        return $query->where('is_active', true)
            ->whereColumn('stok', '<=', 'stok_minimum');
    }
}
