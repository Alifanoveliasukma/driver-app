<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Menu\UtamaController;
use App\Http\Controllers\Menu\HistoriController;
use App\Http\Controllers\Menu\UjpController;
use App\Http\Controllers\Menu\ProfilController;

Route::get('/cek-ext-web', function () {
    return [
        'php_ini'   => php_ini_loaded_file(),
        'pgsql'     => extension_loaded('pgsql'),
        'pdo_pgsql' => extension_loaded('pdo_pgsql'),
        'php'       => PHP_VERSION,
    ];
});

Route::get('/diag-php', function () {
    return [
        'php_ini'   => php_ini_loaded_file(),
        'scanned'   => php_ini_scanned_files(),         
        'ext_dir'   => ini_get('extension_dir'),        
        'pgsql'     => extension_loaded('pgsql'),
        'pdo_pgsql' => extension_loaded('pdo_pgsql'),
        'version'   => PHP_VERSION,
    ];
});

Route::match(['get','post'], '/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/konfirmasi-tiba-muat', function () {
    return view('menu.utama.konfirmasi-tiba-muat');
})->name('utama.konfirmasi-tiba-muat');

Route::get('/no-order', function () {
    return view('menu.utama.no-order');
});

Route::get('/konfirmasi-selesai-muat', function () {
    return view('menu.utama.konfirmasi-selesai-muat');
})->name('utama.konfirmasi-selesai-muat');

Route::get('/konfirmasi-keluar-muat', function () {
    return view('menu.utama.konfirmasi-keluar-muat');
})->name('utama.konfirmasi-keluar-muat');

Route::get('/konfirmasi-tiba-tujuan', function () {
    return view('menu.utama.konfirmasi-tiba-tujuan');
})->name('utama.konfirmasi-tiba-tujuan');

Route::get('/konfirmasi-mulai-bongkar', function () {
    return view('menu.utama.konfirmasi-mulai-bongkar');
})->name('utama.konfirmasi-mulai-bongkar');

Route::get('/konfirmasi-keluar-bongkar', function () {
    return view('menu.utama.konfirmasi-keluar-bongkar');
})->name('utama.konfirmasi-keluar-bongkar');


Route::get('/konfirmasi-berangkat', [UtamaController::class, 'index'])->name('menu.konfirmasi-berangkat');
Route::get('/ujp', [UjpController::class, 'ujp'])->name('menu.ujp');
Route::get('/histori', [HistoriController::class, 'histori'])->name('menu.histori');
Route::get('/profil', [ProfilController::class, 'profil'])->name('menu.profil');
