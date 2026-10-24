<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengaduanController;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return redirect()->route(
        auth()->user()->role === 'admin' ? 'dashboard.admin' : 'dashboard.customer'
    );
})->name('home');

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

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'redirectByRole'])
        ->name('dashboard');

    Route::get('/dashboard/admin', [DashboardController::class, 'index'])
        ->middleware('admin')
        ->name('dashboard.admin');

    Route::get('/dashboard/customer', [DashboardController::class, 'customer'])
        ->name('dashboard.customer');

    Route::get('/pengaduan/{pengaduan}/lampiran', [PengaduanController::class, 'attachment'])
        ->name('pengaduan.attachment');

    Route::middleware('customer')->group(function () {
        Route::get('/pengaduan/create', [PengaduanController::class, 'create'])
            ->name('pengaduan.create');
        Route::post('/pengaduan', [PengaduanController::class, 'store'])
            ->name('pengaduan.store');
    });

    Route::middleware('admin')->group(function () {
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
