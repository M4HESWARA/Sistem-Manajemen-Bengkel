<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleInitialVerification extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'service_order_id',
        'odometer_photo_url',
        'odometer_reading',
        'fuel_photo_url',
        'fuel_level',
        'lampu_utama_ok',
        'lampu_sein_ok',
        'check_engine_ok',
        'lampu_abs_ok',
        'lampu_oli_ok',
        'catatan_kondisi',
        'verified_by',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'lampu_utama_ok' => 'boolean',
            'lampu_sein_ok' => 'boolean',
            'check_engine_ok' => 'boolean',
            'lampu_abs_ok' => 'boolean',
            'lampu_oli_ok' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function verifiedByUser()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Ada indikator/lampu yang bermasalah? Berguna utk highlight di UI admin.
    public function hasWarning(): bool
    {
        return ! $this->lampu_utama_ok
            || ! $this->lampu_sein_ok
            || ! $this->check_engine_ok
            || ! $this->lampu_abs_ok
            || ! $this->lampu_oli_ok;
    }
}
