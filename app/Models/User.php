<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'full_name',
        'role',
        'phone',
        'email',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMekanik(): bool
    {
        return $this->role === 'mekanik';
    }

    // Order yang di-assign ke user ini sebagai mekanik
    public function serviceOrdersAsMechanic()
    {
        return $this->hasMany(ServiceOrder::class, 'mechanic_id');
    }

    // Order yang didaftarkan oleh user ini sebagai admin
    public function serviceOrdersAsAdmin()
    {
        return $this->hasMany(ServiceOrder::class, 'admin_id');
    }
}
