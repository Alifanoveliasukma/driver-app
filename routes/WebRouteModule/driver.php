<?php

use App\Http\Controllers\Menu\HistoriController;
use App\Http\Controllers\Menu\ProfilController;
use App\Http\Controllers\Menu\UjpController;
use App\Http\Controllers\Menu\UtamaController;
use Illuminate\Support\Facades\Route;

// proses tracking
Route::middleware(['checkrole:1000049'])->group(function () {
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

    Route::get('/ujp', [UjpController::class, 'ujp'])->name('menu.ujp');
    Route::get('/histori', [HistoriController::class, 'histori'])->name('menu.histori');
    Route::get('/profile', [ProfilController::class, 'profile_driver'])
    ->name('menu.profil');

    Route::get('/no-order', function () {
        return view('menu.utama.no-order');
    });
});