<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->string('nama_pelapor')->nullable();
            $table->string('email')->nullable();
            $table->string('no_hp', 30)->nullable();
            $table->string('kategori', 100)->nullable();
            $table->string('judul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('lampiran')->nullable();
            $table->string('status')->default('Menunggu');
        });
    }

    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn([
                'nama_pelapor',
                'email',
                'no_hp',
                'kategori',
                'judul',
                'deskripsi',
                'lampiran',
                'status',
            ]);
        });
    }
};
