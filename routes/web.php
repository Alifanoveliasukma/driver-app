<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Menu\UtamaController;
use App\Http\Controllers\Menu\HistoriController;
use App\Http\Controllers\Menu\UjpController;
use App\Http\Controllers\Menu\ProfilController;


Route::match(['get','post'], '/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::patch('/utama/tiba-muat', [App\Http\Controllers\Menu\UtamaController::class, 'tibaMuat'])
    ->name('utama.konfirmasi-tiba-muat');
    
Route::get('/no-order', function () {
    return view('menu.utama.no-order');
});

Route::get('/konfirmasi-selesai-muat', function () {
    return view('menu.utama.konfirmasi-selesai-muat');
});

Route::get('/konfirmasi-keluar-muat', function () {
    return view('menu.utama.konfirmasi-keluar-muat');
});

Route::get('/konfirmasi-tiba-tujuan', function () {
    return view('menu.utama.konfirmasi-tiba-tujuan');
});

Route::get('/konfirmasi-mulai-bongkar', function () {
    return view('menu.utama.konfirmasi-mulai-bongkar');
});

Route::get('/konfirmasi-keluar-bongkar', function () {
    return view('menu.utama.konfirmasi-keluar-bongkar');
});


Route::get('/konfirmasi-berangkat', [UtamaController::class, 'index'])->name('menu.konfirmasi-berangkat');
Route::get('/ujp', [UjpController::class, 'ujp'])->name('menu.ujp');
Route::get('/histori', [HistoriController::class, 'histori'])->name('menu.histori');
Route::get('/profil', [ProfilController::class, 'profil'])->name('menu.profil');
