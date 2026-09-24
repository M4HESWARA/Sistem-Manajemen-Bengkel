<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InventoriController;
use App\Http\Controllers\Admin\KasirController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PendaftaranController;
use App\Http\Controllers\Admin\RiwayatServisController;
use App\Http\Controllers\Admin\ServiceOrderActionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Mekanik\MekanikController;
use App\Http\Controllers\Public\CekStatusController;
use App\Http\Controllers\Public\LandingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik (tanpa login) - Portal Pelanggan
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('public.landing');
Route::get('/cek-status', [CekStatusController::class, 'index'])->name('public.cek-status');

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

    // Inventori Sparepart
    Route::get('/inventori', [InventoriController::class, 'index'])->name('inventori');
    Route::get('/inventori/tambah', [InventoriController::class, 'create'])->name('inventori.create');
    Route::post('/inventori', [InventoriController::class, 'store'])->name('inventori.store');
    Route::get('/inventori/{sparepart}/edit', [InventoriController::class, 'edit'])->name('inventori.edit');
    Route::put('/inventori/{sparepart}', [InventoriController::class, 'update'])->name('inventori.update');
    Route::post('/inventori/{sparepart}/toggle-active', [InventoriController::class, 'toggleActive'])->name('inventori.toggle-active');
    Route::post('/inventori/{sparepart}/tambah-stok', [InventoriController::class, 'tambahStok'])->name('inventori.tambah-stok');

    // Kasir & Pembayaran
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir');
    Route::post('/kasir/{serviceOrder}/bayar', [KasirController::class, 'bayar'])->name('kasir.bayar');
    Route::get('/kasir/{serviceOrder}/nota', [KasirController::class, 'nota'])->name('kasir.nota');

    // Laporan & Analitik
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

    // Riwayat Servis
    Route::get('/riwayat', [RiwayatServisController::class, 'index'])->name('riwayat');
});

/*
|--------------------------------------------------------------------------
| Dashboard Mekanik (khusus role mekanik)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:mekanik'])->prefix('mekanik')->name('mekanik.')->group(function () {
    Route::get('/dashboard', [MekanikController::class, 'dashboard'])->name('dashboard');
    Route::post('/tugas/{serviceOrder}/status', [MekanikController::class, 'updateStatus'])->name('tugas.update-status');
    Route::post('/tugas/{serviceOrder}/sparepart', [MekanikController::class, 'tambahSparepart'])->name('tugas.tambah-sparepart');
    Route::delete('/tugas/{serviceOrder}/sparepart/{item}', [MekanikController::class, 'hapusSparepart'])->name('tugas.hapus-sparepart');
    Route::post('/tugas/{serviceOrder}/jasa', [MekanikController::class, 'tambahJasa'])->name('tugas.tambah-jasa');
    Route::post('/tugas/{serviceOrder}/selesai', [MekanikController::class, 'selesaikan'])->name('tugas.selesai');
});