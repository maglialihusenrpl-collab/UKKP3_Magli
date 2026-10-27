<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model laporan pengaduan beserta pilihan nomor meja yang tersedia.
 */
class Pengaduan extends Model
{
    // Nomor meja yang dapat dipilih customer saat membuat pengaduan.
    public const TABLE_NUMBERS = [
        'Meja 1',
        'Meja 2',
        'Meja 3',
        'Meja 4',
        'Meja 5',
        'Meja 6',
        'Meja 7',
        'Meja 8',
        'Meja 9',
        'Meja 10',
    ];

    // Atribut pengaduan yang dapat disimpan melalui mass assignment.
    protected $fillable = [
        'nama_pelapor',
        'email',
        'no_hp',
        'nomor_meja',
        'kategori',
        'judul',
        'deskripsi',
        'lampiran',
        'status',
    ];
}
