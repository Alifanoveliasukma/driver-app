<?php

use App\Http\Modules\AuthModules\Controllers\Web\WebAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/login',[WebAuthController::class,'showLogin'])->name('login')->middleware('redirectIfLoggedIn');
Route::post('/login',[WebAuthController::class,'processLogin'])->name('login.process')->middleware('redirectIfLoggedIn');
Route::get('/login/role',[WebAuthController::class,'showAuth'])->name('auth')->middleware('redirectIfLoggedIn');
Route::post('/login/role',[WebAuthController::class,'processAuth'])->name('auth.process')->middleware('redirectIfLoggedIn');
Route::get('/', function () {
    return redirect()->route('login');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

require __DIR__ . '/WebRouteModule/driver.php';
require __DIR__ . '/WebRouteModule/planner.php';