<?php

namespace App\Http\Modules\DriverModules\Services;

use App\Services\DriverApi;
use App\Services\OrderApi;
use Illuminate\Support\Facades\DB;

class HistoriService
{

    public static function getDriverData($c_bpartner_id, DriverApi $driverApi)
    {
        $driver = $driverApi->getDriver($c_bpartner_id);

        $fields = data_get($driver, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow.field', []);
        if (isset($fields['@attributes'])) {
            $fields = [$fields];
        }

        $mappedDriver = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column'], $attr['lval'])) {
                $mappedDriver[$attr['column']] = $attr['lval'];
            }
        }

        return $mappedDriver;
    }


    public static function getFinishedOrders($driverId, OrderApi $orderApi)
    {
        $order = $orderApi->getOrderList($driverId);
        $rows = data_get($order, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);

        if (isset($rows['field'])) {
            $rows = [$rows];
        }

        $mappedOrders = [];
        foreach ($rows as $row) {
            $fs = $row['field'] ?? [];
            if (isset($fs['@attributes'])) {
                $fs = [$fs];
            }

            $tmp = [];
            foreach ($fs as $f) {
                $attr = $f['@attributes'] ?? [];
                if (isset($attr['column'], $attr['lval'])) {
                    $tmp[$attr['column']] = $attr['lval'];
                }
            }
            if (!empty($tmp)) {
                $mappedOrders[] = $tmp;
            }
        }

        return $mappedOrders;
    }


    public static function enrichHistoryOrders(array $mappedOrders)
    {
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

        return collect($mappedOrders)
            ->filter(function ($r) {
                return isset($r['Status']) && $r['Status'] === 'FINISHED'; // Only finished orders
            })
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
    }
}
