<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
    ];

    public function pengaduans(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'kategori', 'name');
    }
}
