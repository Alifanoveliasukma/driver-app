<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Services\AuthService;
use App\Services\DriverService;
use App\Services\OrderService;
use App\Services\OrderReadService;

class UtamaController extends Controller
{
    protected $authService;
    protected $driverService;
    protected $orderService;
    protected $orderReadService;

    public function __construct(
        AuthService $authService,
        DriverService $driverService,
        OrderService $orderService,
        OrderReadService $orderReadService
    ) {
        $this->authService     = $authService;
        $this->driverService   = $driverService;
        $this->orderService    = $orderService;
        $this->orderReadService = $orderReadService;
    }

    public function index()
    {
        $auth = $this->authService->authUser('SuperUser', 'sembilanbahanpokok', 1000037, 1000005, 1000003);

        // --- Tes DriverService ---
        $driver = $this->driverService->getDriver(1004164);

        // --- Tes OrderService ---
        $orders = $this->orderService->getOrders(1001145);

        // --- Tes OrderReadService ---
        $orderDetail = $this->orderReadService->getOrderById(1382751);

        // Untuk sementara dump hasil
        dd([
            'auth'   => $auth,
            'driver' => $driver,
            'orders' => $orders,
            'orderDetail' => $orderDetail,
        ]);
        return view('menu.utama.konfirmasi-berangkat');
    }
}
