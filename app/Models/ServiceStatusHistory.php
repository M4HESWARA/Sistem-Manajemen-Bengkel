<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceStatusHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'service_order_id',
        'status',
        'changed_by',
        'notes',
        'changed_at',
    ];

    protected function casts(): array
    {
        return [
            'changed_at' => 'datetime',
        ];
    }

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function changedByUser()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
