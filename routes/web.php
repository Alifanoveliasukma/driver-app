<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Menu\UtamaController;
use App\Http\Controllers\Menu\HistoriController;
use App\Http\Controllers\Menu\UjpController;
use App\Http\Controllers\Menu\ProfilController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'handleLogin']);

Route::get('/login/roleorg', [AuthController::class, 'showRoleOrg'])->name('login.roleorg');
Route::post('/login/roleorg', [AuthController::class, 'handleRoleOrg']);

Route::get('/login/authenticate', [AuthController::class, 'authenticate'])->name('login.authenticate');

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
