<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PengaduanController;
use Illuminate\Support\Facades\Route;

// Halaman awal mengarahkan pengguna ke login atau dashboard berdasarkan status sesi.
Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return redirect()->route(
        auth()->user()->role === 'admin' ? 'dashboard.admin' : 'dashboard.customer'
    );
})->name('home');

// Rute autentikasi hanya dapat diakses oleh pengguna yang belum masuk.
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.store');
});

// Seluruh fitur berikut memerlukan sesi pengguna yang sudah terautentikasi.
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'redirectByRole'])
        ->name('dashboard');

    // Dashboard admin dibatasi oleh middleware peran admin.
    Route::get('/dashboard/admin', [DashboardController::class, 'index'])
        ->middleware('admin')
        ->name('dashboard.admin');

    Route::get('/dashboard/customer', [DashboardController::class, 'customer'])
        ->name('dashboard.customer');

    Route::get('/pengaduan/{pengaduan}/lampiran', [PengaduanController::class, 'attachment'])
        ->name('pengaduan.attachment');

    Route::get('/kategori', [KategoriController::class, 'index'])
        ->name('kategori.index');

    // Rute pengaduan untuk customer: hanya membuat pengaduan baru.
    Route::middleware('customer')->group(function () {
        Route::get('/pengaduan/create', [PengaduanController::class, 'create'])
            ->name('pengaduan.create');
        Route::post('/pengaduan', [PengaduanController::class, 'store'])
            ->name('pengaduan.store');
    });

    // Rute pengelolaan pengaduan hanya tersedia untuk admin.
    Route::middleware('admin')->group(function () {
        Route::post('/kategori', [KategoriController::class, 'store'])
            ->name('kategori.store');
        Route::get('/kategori/{kategori}/edit', [KategoriController::class, 'edit'])
            ->name('kategori.edit');
        Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])
            ->name('kategori.update');
        Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])
            ->name('kategori.destroy');

        Route::get('/pengaduan', [PengaduanController::class, 'index'])
            ->name('pengaduan.index');
        Route::get('/pengaduan/{pengaduan}/edit', [PengaduanController::class, 'edit'])
            ->name('pengaduan.edit');
        Route::put('/pengaduan/{pengaduan}', [PengaduanController::class, 'update'])
            ->name('pengaduan.update');
        Route::patch('/pengaduan/{pengaduan}/status', [PengaduanController::class, 'updateStatus'])
            ->name('pengaduan.status');
        Route::delete('/pengaduan/{pengaduan}', [PengaduanController::class, 'destroy'])
            ->name('pengaduan.destroy');
    });

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
