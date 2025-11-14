<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\Menu\HistoriController;
use App\Http\Controllers\Menu\ProfilController;
use App\Http\Modules\PlannerModules\Controllers\Web\WebDashboardController;
use App\Http\Modules\PlannerModules\Controllers\Web\WebDriverController;
use App\Http\Modules\PlannerModules\Controllers\Web\WebProfileController;
use App\Http\Modules\PlannerModules\Controllers\Web\WebTransTrackingController;
use Illuminate\Support\Facades\Route;

// ROLE PLANNER
Route::middleware(['checkrole:1000051'])->group(function () {
    Route::get('/dashboard', [WebDashboardController::class, 'index'])->name('dashboard');
    // Driver Create
    Route::get('/driver', [WebDriverController::class, 'index'])->name('driver.index');

    Route::get('/create-user-driver', [WebDriverController::class, 'createForm'])->name('driver.create');

    // Rute POST untuk memproses data formulir (membuat User, Role, dan Driver)
    Route::post('/store-user-driver', [WebDriverController::class, 'store'])->name('driver.store');

    // Rute untuk halaman sukses setelah proses berhasil
    Route::get('/success-user-driver', [WebDriverController::class, 'successPage'])->name('driver.success');

    Route::get('/clear-cache', function () {
        Cache::forget('all_active_drivers');
        return "Cache driver telah dihapus. Silakan refresh halaman utama.";
    });
    // Route::get('/driver/{id}', [DriverController::class, 'detail'])->name('driver.detail');

    Route::get('/profile/planner', [WebProfileController::class, 'index'])
        ->name('menu.profil.planner');

    Route::get('/histori/all', [WebTransTrackingController::class, 'index'])->name('histori.planner');
    Route::get('/history/detail/{id}', [HistoriController::class, 'detailPlanner'])
        ->whereNumber('id') // memastikan ID hanya angka
        ->name('histori.planner.detail');
});