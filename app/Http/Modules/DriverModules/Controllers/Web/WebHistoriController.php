<?php

namespace App\Http\Modules\DriverModules\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Modules\DriverModules\Services\HistoriService;
use App\Services\DriverApi;
use App\Services\OrderApi;

class WebHistoriController extends Controller
{
    protected $driver, $order;

    public function __construct(DriverApi $driver, OrderApi $order)
    {
        $this->driver = $driver;
        $this->order = $order;
        $this->middleware('checklogin');
    }



    public function histori()
    {
        $c_bpartner_id = session('c_bpartner_id');

        // Get driver data
        $mappedDriver = HistoriService::getDriverData($c_bpartner_id, $this->driver);
        $driverId = $mappedDriver['XM_Driver_ID'] ?? null;

        // Get finished orders
        $mappedOrders = HistoriService::getFinishedOrders($driverId, $this->order);

        // Enrich with customer and route data
        $orders = HistoriService::enrichHistoryOrders($mappedOrders);

        if (empty($orders)) {
            return view('menu.histori.no-history');
        }

        return view('menu.histori.index', compact('orders'));
    }
}
