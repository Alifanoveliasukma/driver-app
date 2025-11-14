<?php

use App\Http\Modules\AuthModules\Controllers\Web\WebAuthController;
use Illuminate\Support\Facades\Route;

Route::fallback(fn() => response()->view('errors.404', [], 404));
Route::get('/', fn() => redirect()->route('login'));
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

Route::middleware('redirectIfLoggedIn')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebAuthController::class, 'processLogin'])->name('login.process');
    Route::get('/login/role', [WebAuthController::class, 'showAuth'])->name('auth');
    Route::post('/login/role', [WebAuthController::class, 'processAuth'])->name('auth.process');
});

require __DIR__ . '/WebRouteModule/driver.php';
require __DIR__ . '/WebRouteModule/planner.php';