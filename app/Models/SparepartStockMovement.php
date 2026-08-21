<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparepartStockMovement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'sparepart_id',
        'movement_type',
        'quantity',
        'service_order_id',
        'keterangan',
        'created_by',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
