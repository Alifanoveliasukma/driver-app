<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Menu\UtamaController;
use App\Http\Controllers\Menu\HistoriController;
use App\Http\Controllers\Menu\UjpController;
use App\Http\Controllers\Menu\ProfilController;

Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::get('/', function () {
//     return redirect('/login');
// });

Route::get('/no-order', function () {
    return view('menu.utama.no-order');
});

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

Route::get('/utama/keluar-muat/{orderId}', [UtamaController::class, 'keluarMuatPage'])
    ->name('utama.konfirmasi-keluar-muat');

Route::post('/utama/keluar-muat', [UtamaController::class, 'keluarMuat'])
    ->name('utama.konfirmasi-keluar-muat.submit');

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

Route::get('/utama/selesai-bongkar/{orderId}', [UtamaController::class, 'selesaiBongkarPage'])
    ->name('utama.konfirmasi-selesai-bongkar');
Route::post('/utama/selesai-bongkar', [UtamaController::class, 'selesaiBongkar'])
    ->name('utama.konfirmasi-selesai-bongkar.submit');

Route::get('/cek_status', [UtamaController::class, 'cek_status']);

Route::get('/cek_xml', [UtamaController::class, 'getOrderDetail']);


// // Route::get('/konfirmasi-berangkat', [UtamaController::class, 'getOrder'])->name('menu.konfirmasi-berangkat');
// Route::post('/utama/tiba-muat', [UtamaController::class, 'tibaMuat'])->name('utama.konfirmasi-tiba-muat');

Route::get('/ujp', [UjpController::class, 'ujp'])->name('menu.ujp');
Route::get('/histori', [HistoriController::class, 'histori'])->name('menu.histori');
Route::get('/profil', [ProfilController::class, 'profil'])->name('menu.profil');
