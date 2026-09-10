<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PendaftaranController;
use App\Http\Controllers\Admin\ServiceOrderActionController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman utama -> arahkan ke login
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Area Admin (khusus role admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::post('/service-orders/{serviceOrder}/assign-mechanic', [ServiceOrderActionController::class, 'assignMechanic'])
        ->name('service-orders.assign-mechanic');

    // Pendaftaran Servis
    Route::get('/pendaftaran', [PendaftaranController::class, 'create'])->name('pendaftaran');
    Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/pendaftaran/cari-kendaraan', [PendaftaranController::class, 'cariKendaraan'])->name('pendaftaran.cari-kendaraan');

    // Menu yang belum dibangun - halaman "segera hadir" sementara
    Route::get('/inventori', fn () => view('admin.coming-soon', ['title' => 'Inventori']))->name('inventori');
    Route::get('/kasir', fn () => view('admin.coming-soon', ['title' => 'Kasir']))->name('kasir');
    Route::get('/laporan', fn () => view('admin.coming-soon', ['title' => 'Laporan']))->name('laporan');
});

/*
|--------------------------------------------------------------------------
| Dashboard Mekanik (khusus role mekanik)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:mekanik'])->prefix('mekanik')->name('mekanik.')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard.mekanik'))->name('dashboard');
});
