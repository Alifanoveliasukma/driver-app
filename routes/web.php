<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Menu\UtamaController;
use App\Http\Controllers\Menu\HistoriController;
use App\Http\Controllers\Menu\UjpController;
use App\Http\Controllers\Menu\ProfilController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TransportTrackingController;
use App\Http\Controllers\DashboardController;

Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login')->middleware('redirectIfLoggedIn');
Route::get('/', function () {
    return redirect()->route('login');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// proses tracking
Route::get('/utama/order', [UtamaController::class, 'getOrder'])
    ->name('menu.list-order');

Route::get('/utama/detail-order/{orderId}', [UtamaController::class, 'detailOrder'])
    ->name('menu.detail-order');

Route::post('/utama/berangkat', [UtamaController::class, 'berangkat'])
    ->name('utama.konfirmasi-berangkat');

Route::get('/utama/tiba-muat/{orderId}', [UtamaController::class, 'tibaMuatPage'])
    ->name('utama.konfirmasi-tiba-muat');

Route::post('/utama/tiba-muat', [UtamaController::class, 'tibaMuat'])
    ->name('utama.konfirmasi-tiba-muat.submit');

Route::get('/utama/mulai-muat/{orderId}', [UtamaController::class, 'mulaiMuatPage'])
    ->name('utama.konfirmasi-mulai-muat');

Route::post('/utama/mulai-muat', [UtamaController::class, 'mulaiMuat'])
    ->name('utama.konfirmasi-mulai-muat.submit');

Route::get('/utama/selesai-muat/{orderId}', [UtamaController::class, 'selesaiMuatPage'])
    ->name('utama.konfirmasi-selesai-muat');

Route::post('/utama/selesai-muat', [UtamaController::class, 'selesaiMuat'])
    ->name('utama.konfirmasi-selesai-muat.submit');

Route::get('/utama/tiba-tujuan/{orderId}', [UtamaController::class, 'tibaTujuanPage'])
    ->name('utama.konfirmasi-tiba-tujuan');

Route::post('/utama/tiba-tujuan', [UtamaController::class, 'tibaTujuan'])
    ->name('utama.konfirmasi-tiba-tujuan.submit');

Route::get('/utama/mulai-bongkar/{orderId}', [UtamaController::class, 'mulaiBongkarPage'])
    ->name('utama.konfirmasi-mulai-bongkar');

Route::post('/utama/mulai-bongkar', [UtamaController::class, 'mulaiBongkar'])
    ->name('utama.konfirmasi-mulai-bongkar.submit');

Route::get('/utama/keluar-bongkar/{orderId}', [UtamaController::class, 'keluarBongkarPage'])
    ->name('utama.konfirmasi-keluar-bongkar');

Route::post('/utama/keluar-bongkar', [UtamaController::class, 'keluarBongkar'])
    ->name('utama.konfirmasi-keluar-bongkar.submit');

Route::get('/cek_status', [UtamaController::class, 'cek_status']);

Route::get('/cek_xml', [UtamaController::class, 'getOrderDetail']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// // Route::get('/konfirmasi-berangkat', [UtamaController::class, 'getOrder'])->name('menu.konfirmasi-berangkat');
// Route::post('/utama/tiba-muat', [UtamaController::class, 'tibaMuat'])->name('utama.konfirmasi-tiba-muat');

Route::get('/ujp', [UjpController::class, 'ujp'])->name('menu.ujp');
Route::get('/histori', [HistoriController::class, 'histori'])->name('menu.histori');
Route::get('/profile', function () {
    $roleid = session('roleid');

    if ($roleid == 1000049) {
        // Driver
        return app(ProfilController::class)->profile_driver();
    } elseif ($roleid == 1000051) {
        // Planner
        return app(ProfilController::class)->profile_planner();
    }

    return redirect()->route('login')->with('error', 'Role tidak dikenali');
})->name('menu.profil');

Route::get('/no-order', function () {
    return view('menu.utama.no-order');
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// ROLE PLANNER// Driver
Route::get('/driver', [DriverController::class, 'index'])->name('driver.index');
// Step 1 - Data Dasar Driver
Route::get('driver/create-step-one', [DriverController::class, 'createStepOne'])->name('driver.create.step.one');
Route::post('driver/create-step-one', [DriverController::class, 'postCreateStepOne'])->name('driver.create.step.one.post');

// Step 2 - Informasi Kendaraan & Akun
Route::get('driver/create-step-two', [DriverController::class, 'createStepTwo'])->name('driver.create.step.two');
Route::post('driver/create-step-two', [DriverController::class, 'postCreateStepTwo'])->name('driver.create.step.two.post');

// Step 3 - Catatan & Status
Route::get('driver/create-step-three', [DriverController::class, 'createStepThree'])->name('driver.create.step.three');
Route::post('driver/create-step-three', [DriverController::class, 'postCreateStepThree'])->name('driver.create.step.three.post');
// Route::get('/driver/create', [DriverController::class, 'create'])->name('driver.create');
Route::get('/driver/{id}/edit', [DriverController::class, 'edit'])->name('driver.edit');
Route::get('/driver/{id}', [DriverController::class, 'detail'])->name('driver.detail');

// Transport Tracking
Route::get('/histori/all', [HistoriController::class, 'historiPlanner'])->name('histori.planner');
