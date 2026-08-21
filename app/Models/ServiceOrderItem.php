<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOrderItem extends Model
{
    public $timestamps = false;

    // subtotal sengaja TIDAK masuk fillable karena kolom generated (dihitung otomatis oleh database)
    protected $fillable = [
        'service_order_id',
        'sparepart_id',
        'quantity',
        'harga_satuan',
        'input_by',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'harga_satuan' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }

    public function inputByUser()
    {
        return $this->belongsTo(User::class, 'input_by');
    }
}
