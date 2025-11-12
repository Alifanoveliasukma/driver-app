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


    // // Route::get('/konfirmasi-berangkat', [UtamaController::class, 'getOrder'])->name('menu.konfirmasi-berangkat');
    // Route::post('/utama/tiba-muat', [UtamaController::class, 'tibaMuat'])->name('utama.konfirmasi-tiba-muat');

    Route::get('/ujp', [UjpController::class, 'ujp'])->name('menu.ujp');
    Route::get('/histori', [HistoriController::class, 'histori'])->name('menu.histori');
    Route::get('/profile', [ProfilController::class, 'profile_driver'])
    ->name('menu.profil');

    Route::get('/no-order', function () {
        return view('menu.utama.no-order');
    });
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// ROLE PLANNER
Route::middleware(['checkrole:1000051'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Driver Create
    Route::get('/driver', [DriverController::class, 'index'])->name('driver.index');

    Route::get('/create-user-driver', [DriverController::class, 'createForm'])->name('driver.create');

    // Rute POST untuk memproses data formulir (membuat User, Role, dan Driver)
    Route::post('/store-user-driver', [DriverController::class, 'store'])->name('driver.store');

    // Rute untuk halaman sukses setelah proses berhasil
    Route::get('/success-user-driver', [DriverController::class, 'successPage'])->name('driver.success');

    Route::get('/clear-cache', function() {
        Cache::forget('all_active_drivers'); // Hapus hanya cache driver
        // Atau hapus semua cache:
        // Artisan::call('cache:clear');

        return "Cache driver telah dihapus. Silakan refresh halaman utama.";
    });

    // Step 1 - Data Dasar Driver
    // Route::get('/create/step-one', [DriverController::class, 'createStepOne'])->name('driver.create.step.one');
    // Route::post('/create/step-one', [DriverController::class, 'createStepOnePost'])->name('driver.create.step.one.post');
    
    // Step 2
    // Route::get('/create/step-two', [DriverController::class, 'createStepTwo'])->name('driver.create.step.two');
    // Route::post('/create/step-two', [DriverController::class, 'createStepTwoPost'])->name('driver.create.step.two.post');
    
    // // Step 3
    // Route::get('/create/step-three', [DriverController::class, 'createStepThree'])->name('driver.create.step.three');
    // Route::post('/create/step-three', [DriverController::class, 'createStepThreePost'])->name('driver.create.step.three.post');
    
    // // Step 4
    // Route::get('/create/step-four', [DriverController::class, 'createStepFour'])->name('driver.create.step.four');
    // Route::post('/create/step-four', [DriverController::class, 'createStepFourPost'])->name('driver.create.step.four.post');
    
    // // Step 5
    // Route::get('/create/step-five', [DriverController::class, 'createStepFive'])->name('driver.create.step.five');
    // Route::post('/create/step-five', [DriverController::class, 'createStepFivePost'])->name('driver.create.step.five.post');
    
    // // Step 6
    // Route::get('/create/step-six', [DriverController::class, 'createStepSix'])->name('driver.create.step.six');
    // Route::post('/create/step-six', [DriverController::class, 'createStepSixPost'])->name('driver.create.step.six.post');
    
    // Step 7
    // Route::get('/create/step-seven', [DriverController::class, 'createStepSeven'])->name('driver.create.step.seven');
    // Route::post('/create/step-seven', [DriverController::class, 'createStepSevenPost'])->name('driver.create.step.seven.post');
    
    // // Step 8
    // Route::get('/create/step-eight', [DriverController::class, 'createStepEight'])->name('driver.create.step.eight');
    // Route::post('/create/step-eight', [DriverController::class, 'createStepEightPost'])->name('driver.create.step.eight.post');

    // // EDIT DRIVER (Step)
    // // === EDIT DRIVER TANPA ID (DUMMY UNTUK TESTING) ===

    // Route::get('driver/edit-step-one', [DriverController::class, 'editStepOne'])
    //     ->name('driver.edit.step.one');
    // Route::post('driver/edit-step-one', [DriverController::class, 'updateStepOne'])
    //     ->name('driver.edit.step.one.post');

    // Route::get('driver/edit-step-two', [DriverController::class, 'editStepTwo'])
    //     ->name('driver.edit.step.two');
    // Route::post('driver/edit-step-two', [DriverController::class, 'updateStepTwo'])
    //     ->name('driver.edit.step.two.post');

    // Route::get('driver/edit-step-three', [DriverController::class, 'editStepThree'])
    //     ->name('driver.edit.step.three');
    // Route::post('driver/edit-step-three', [DriverController::class, 'updateStepThree'])
    //     ->name('driver.edit.step.three.post');

    // Route::get('/driver/{id}/edit', [DriverController::class, 'edit'])->name('driver.edit');
    Route::get('/driver/{id}', [DriverController::class, 'detail'])->name('driver.detail');

    Route::get('/profile/planner', [ProfilController::class, 'profile_planner'])
    ->name('menu.profil.planner')
    ->middleware('checkrole:1000051');


    Route::get('/histori/all', [HistoriController::class, 'historiPlanner'])->name('histori.planner');
    Route::get('/history/detail/{id}', [HistoriController::class, 'detailPlanner'])
    ->whereNumber('id') // memastikan ID hanya angka
    ->name('histori.planner.detail');
}); 
