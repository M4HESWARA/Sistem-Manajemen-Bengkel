<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparepartCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function spareparts()
    {
        return $this->hasMany(Sparepart::class, 'category_id');
    }
}
