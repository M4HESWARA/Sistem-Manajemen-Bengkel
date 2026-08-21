<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'plat_nomor',
        'brand',
        'model',
        'year',
        'color',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }

    // Riwayat servis terbaru duluan - berguna untuk portal pelanggan
    public function serviceOrdersHistory()
    {
        return $this->hasMany(ServiceOrder::class)->latest('tanggal_masuk');
    }

    // Dipakai untuk pencarian riwayat via Nomor Polisi
    public static function findByPlatNomor(string $platNomor): ?self
    {
        return static::where('plat_nomor', strtoupper(trim($platNomor)))->first();
    }
}
