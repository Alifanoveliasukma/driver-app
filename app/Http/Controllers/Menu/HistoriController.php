<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DriverApi;
use App\Services\OrderApi;
use Illuminate\Support\Facades\DB;

class HistoriController extends Controller
{
    protected $driver;

    public function __construct(DriverApi $driver, OrderApi $order)
    {
        $this->driver = $driver;
        $this->order  = $order;
        $this->middleware('checklogin');
    }

    public function histori()
    {
        $c_bpartner_id = session('c_bpartner_id');
        $driver = $this->driver->getDriver($c_bpartner_id);

        $fields = data_get($driver, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow.field', []);
        if (isset($fields['@attributes'])) $fields = [$fields];

        $mappedDriver = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column'], $attr['lval'])) {
                $mappedDriver[$attr['column']] = $attr['lval'];
            }
        }

        $driverId = $mappedDriver['XM_Driver_ID'] ?? null;

        $order = $this->order->getOrderList($driverId);
        $rows = data_get($order, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
        if (isset($rows['field'])) $rows = [$rows];

        $mappedOrders = [];
        foreach ($rows as $row) {
            $fs = $row['field'] ?? [];
            if (isset($fs['@attributes'])) $fs = [$fs];

            $tmp = [];
            foreach ($fs as $f) {
                $attr = $f['@attributes'] ?? [];
                if (isset($attr['column'], $attr['lval'])) {
                    $tmp[$attr['column']] = $attr['lval'];
                }
            }
            if (!empty($tmp)) $mappedOrders[] = $tmp;
        }

        $customerIds = collect($mappedOrders)->pluck('Customer_ID')->filter()->unique();
        $transOrderIds = collect($mappedOrders)->pluck('XX_TransOrder_ID')->filter()->unique();

        $customers = DB::table('mzl.c_bpartner')
            ->whereIn('c_bpartner_id', $customerIds)
            ->pluck('name', 'c_bpartner_id');

        $routes = DB::table('mzl.xx_transorder as t')
            ->select('t.xx_transorder_id', 't.route')
            ->whereIn('t.xx_transorder_id', $transOrderIds)
            ->get()
            ->keyBy('xx_transorder_id');

        $orders = collect($mappedOrders)
            ->filter(function ($r) {
                return isset($r['Status']) && $r['Status'] === 'FINISHED'; // hanya histori
            })
            ->sortByDesc(function ($r) {
                return $r['ETA'] ?? '0000-01-01 00:00:00'; // histori urut dari ETA terbaru
            })
            ->values()
            ->map(function ($r) use ($customers, $routes) {
                $r['Customer_Name'] = $customers[$r['Customer_ID']] ?? '-';
                $route = $routes[$r['XX_TransOrder_ID']] ?? null;
                $r['route'] = $route->route ?? '-';
                return $r;
            })
            ->all();

        // Kalau histori kosong
        if (empty($orders)) {
            return view('menu.histori.no-history');
        }

        return view('menu.histori.index', compact('orders'));
    }

public function historiPlanner()
{
    // Ambil semua order tanpa filter driver
    $order = $this->order->getAllOrderList();
    $rows = data_get($order, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
    if (isset($rows['field'])) $rows = [$rows];

    $mappedOrders = [];
    foreach ($rows as $row) {
        $fs = $row['field'] ?? [];
        if (isset($fs['@attributes'])) $fs = [$fs];

        $tmp = [];
        foreach ($fs as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column'], $attr['lval'])) {
                $tmp[$attr['column']] = $attr['lval'];
            }
        }
        if (!empty($tmp)) $mappedOrders[] = $tmp;
    }

    // Ambil customer & route
    $customerIds = collect($mappedOrders)->pluck('Customer_ID')->filter()->unique();
    $transOrderIds = collect($mappedOrders)->pluck('XX_TransOrder_ID')->filter()->unique();

    $customers = DB::table('mzl.c_bpartner')
        ->whereIn('c_bpartner_id', $customerIds)
        ->pluck('name', 'c_bpartner_id');

    $routes = DB::table('mzl.xx_transorder as t')
        ->select('t.xx_transorder_id', 't.route')
        ->whereIn('t.xx_transorder_id', $transOrderIds)
        ->get()
        ->keyBy('xx_transorder_id');

    // Tampilkan semua histori (tanpa filter Status)
    $orders = collect($mappedOrders)
        ->sortByDesc(function ($r) {
            return $r['ETA'] ?? '0000-01-01 00:00:00';
        })
        ->values()
        ->map(function ($r) use ($customers, $routes) {
            $r['Customer_Name'] = $customers[$r['Customer_ID']] ?? '-';
            $route = $routes[$r['XX_TransOrder_ID']] ?? null;
            $r['route'] = $route->route ?? '-';
            return $r;
        })
        ->all();

    // Jika tidak ada data histori
    if (empty($orders)) {
        return view('planner.history.no-history');
    }

    // View planner, bukan menu driver
    return view('planner.history.index', compact('orders'));
}



}
