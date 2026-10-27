<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });

        $existingNames = DB::table('pengaduans')
            ->whereNotNull('kategori')
            ->where('kategori', '<>', '')
            ->distinct()
            ->pluck('kategori')
            ->all();

        foreach (array_unique(['Pesanan', 'Pembayaran', 'Pengiriman', ...$existingNames]) as $name) {
            DB::table('categories')->insertOrIgnore([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
