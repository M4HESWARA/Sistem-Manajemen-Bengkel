<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'address',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }
}
