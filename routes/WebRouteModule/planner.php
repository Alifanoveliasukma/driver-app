<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\Menu\HistoriController;
use App\Http\Controllers\Menu\ProfilController;
use Illuminate\Support\Facades\Route;

// ROLE PLANNER
Route::middleware(['checkrole:1000051'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Driver Create
    Route::get('/driver', [DriverController::class, 'index'])->name('driver.index');

    Route::get('/create-user-driver', [DriverController::class, 'createForm'])->name('driver.create');

    // Rute POST untuk memproses data formulir (membuat User, Role, dan Driver)
    Route::post('/store-user-driver', [DriverController::class, 'store'])->name('driver.store');

    // Rute untuk halaman sukses setelah proses berhasil
    Route::get('/success-user-driver', [DriverController::class, 'successPage'])->name('driver.success');

    Route::get('/clear-cache', function() {
        Cache::forget('all_active_drivers'); // Hapus hanya cache driver
        // Atau hapus semua cache:
        // Artisan::call('cache:clear');

        return "Cache driver telah dihapus. Silakan refresh halaman utama.";
    });
    Route::get('/driver/{id}', [DriverController::class, 'detail'])->name('driver.detail');

    Route::get('/profile/planner', [ProfilController::class, 'profile_planner'])
    ->name('menu.profil.planner')
    ->middleware('checkrole:1000051');


    Route::get('/histori/all', [HistoriController::class, 'historiPlanner'])->name('histori.planner');
    Route::get('/history/detail/{id}', [HistoriController::class, 'detailPlanner'])
    ->whereNumber('id') // memastikan ID hanya angka
    ->name('histori.planner.detail');
}); 