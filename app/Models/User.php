<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model akun pengguna untuk autentikasi dan pembagian peran customer/admin.
 */
class User extends Authenticatable
{
    use Notifiable;

    // Kolom yang boleh diisi saat akun dibuat atau diperbarui melalui aplikasi.
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    // Mencegah data sensitif ikut muncul saat model diserialisasi.
    protected $hidden = [
        'password',
        'remember_token',
    ];
}