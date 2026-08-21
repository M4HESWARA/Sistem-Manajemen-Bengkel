<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceJasaItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'service_order_id',
        'deskripsi',
        'biaya',
        'input_by',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'biaya' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function inputByUser()
    {
        return $this->belongsTo(User::class, 'input_by');
    }
}
