<?php

use App\Http\Modules\DriverModules\Controllers\Web\WebUtamaController;
use App\Http\Modules\DriverModules\Controllers\Web\WebUjpController;
use App\Http\Modules\DriverModules\Controllers\Web\WebHistoriController;
use App\Http\Modules\DriverModules\Controllers\Web\WebProfilController;
use Illuminate\Support\Facades\Route;

// proses tracking
Route::middleware(['checkrole:1000049'])->group(function () {
    Route::get('/utama/order', [WebUtamaController::class, 'getOrder'])
        ->name('menu.list-order');

    Route::get('/utama/detail-order/{orderId}', [WebUtamaController::class, 'detailOrder'])
        ->name('menu.detail-order');

    Route::post('/utama/berangkat', [WebUtamaController::class, 'berangkat'])
        ->name('utama.konfirmasi-berangkat');

    Route::get('/utama/tiba-muat/{orderId}', [WebUtamaController::class, 'tibaMuatPage'])
        ->name('utama.konfirmasi-tiba-muat');

    Route::post('/utama/tiba-muat', [WebUtamaController::class, 'tibaMuat'])
        ->name('utama.konfirmasi-tiba-muat.submit');

    Route::get('/utama/mulai-muat/{orderId}', [WebUtamaController::class, 'mulaiMuatPage'])
        ->name('utama.konfirmasi-mulai-muat');

    Route::post('/utama/mulai-muat', [WebUtamaController::class, 'mulaiMuat'])
        ->name('utama.konfirmasi-mulai-muat.submit');

    Route::get('/utama/selesai-muat/{orderId}', [WebUtamaController::class, 'selesaiMuatPage'])
        ->name('utama.konfirmasi-selesai-muat');

    Route::post('/utama/selesai-muat', [WebUtamaController::class, 'selesaiMuat'])
        ->name('utama.konfirmasi-selesai-muat.submit');

    Route::get('/utama/tiba-tujuan/{orderId}', [WebUtamaController::class, 'tibaTujuanPage'])
        ->name('utama.konfirmasi-tiba-tujuan');

    Route::post('/utama/tiba-tujuan', [WebUtamaController::class, 'tibaTujuan'])
        ->name('utama.konfirmasi-tiba-tujuan.submit');

    Route::get('/utama/mulai-bongkar/{orderId}', [WebUtamaController::class, 'mulaiBongkarPage'])
        ->name('utama.konfirmasi-mulai-bongkar');

    Route::post('/utama/mulai-bongkar', [WebUtamaController::class, 'mulaiBongkar'])
        ->name('utama.konfirmasi-mulai-bongkar.submit');

    Route::get('/utama/keluar-bongkar/{orderId}', [WebUtamaController::class, 'keluarBongkarPage'])
        ->name('utama.konfirmasi-keluar-bongkar');

    Route::post('/utama/keluar-bongkar', [WebUtamaController::class, 'keluarBongkar'])
        ->name('utama.konfirmasi-keluar-bongkar.submit');

    Route::get('/cek_xml', [WebUtamaController::class, 'getOrderDetail']);

    Route::get('/ujp', [WebUjpController::class, 'ujp'])->name('menu.ujp');
    Route::get('/histori', [WebHistoriController::class, 'histori'])->name('menu.histori');
    Route::get('/profile', [WebProfilController::class, 'profile'])->name('menu.profil');

    Route::get('/no-order', function () {
        return view('menu.utama.no-order');
    });
});