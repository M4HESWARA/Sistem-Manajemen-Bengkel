<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = ['nama_bengkel', 'alamat', 'telepon', 'email'];

    /**
     * Info bengkel disimpan sebagai satu baris tunggal (singleton).
     * Kalau belum pernah diisi, otomatis dibuatkan nilai default.
     */
    public static function current(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'nama_bengkel' => 'KSR Garage',
                'alamat' => 'Jl. Raya Bengkel No. 88, Jakarta Selatan',
                'telepon' => '(021) 555-0199',
                'email' => 'halo@ksrgarage.com',
            ]
        );
    }
}
