<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Services\DriverApi;
use App\Services\OrderApi;

class UtamaController extends Controller
{

    protected $order, $driver, $c_bpartner_id;

    public function __construct(OrderApi $order, DriverApi $driver)
    {
        $this->order = $order;
        $this->driver = $driver;
    }

    public function index()
    {

        $c_bpartner_id = session('c_bpartner_id');


        $driver = $this->driver->getDriver($c_bpartner_id);
        $fields = $driver['soap:Body']['ns1:queryDataResponse']['WindowTabData']['DataSet']['DataRow']['field'] ?? [];

        $mappedDriver = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column'], $attr['lval'])) {
                $mappedDriver[$attr['column']] = $attr['lval'];
            }
        }


        $driverId = $mappedDriver['XM_Driver_ID'] ?? null;


        $order = $this->order->getOrderList($driverId);


        $rows = $order['soap:Body']['ns1:queryDataResponse']['WindowTabData']['DataSet']['DataRow'] ?? [];

        $mappedOrders = [];


        if (isset($rows['field'])) {
            $rows = [$rows];
        }

        foreach ($rows as $row) {
            $fields = $row['field'] ?? [];
            $mappedRow = [];

            foreach ($fields as $f) {
                $attr = $f['@attributes'] ?? [];
                if (isset($attr['column'], $attr['lval'])) {
                    $mappedRow[$attr['column']] = $attr['lval'];
                }
            }

            if (!empty($mappedRow)) {
                $mappedOrders[] = $mappedRow;
            }
        }


        dd($mappedOrders);

        return view('menu.utama.konfirmasi-berangkat');
        // return view('menu.utama.konfirmasi-berangkat', compact('mappedDriver', 'mappedOrders'));
    }
}
