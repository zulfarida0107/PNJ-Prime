<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model Eloquent untuk tabel `users` (skema modular 6 role & profil lengkap).
 * Catatan: `App\Models\User` tetap menunjuk ke tabel `user` agar fitur Auth existing tidak terganggu.
 */
class PortalUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim_nip',
        'prodi_unit',
        'phone',
        'address',
        'company_name',
        'nib',
        'npwp',
        'kyc_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function submittedProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'submitted_by');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }
}
