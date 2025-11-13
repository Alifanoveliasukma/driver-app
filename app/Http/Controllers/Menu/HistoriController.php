<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DriverApi;
use App\Services\OrderApi;
use App\Services\TransportStatusApi;
use App\Services\TransTrackingApi;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HistoriController extends Controller
{
    protected $driver;

    public function __construct(DriverApi $driver, OrderApi $order, TransportStatusApi $transportStatus, TransTrackingApi $transTrackingApi)
    {
        $this->driver = $driver;
        $this->order  = $order;
        $this->transportStatus = $transportStatus;
        $this->transTrackingApi = $transTrackingApi; 
        $this->middleware('checklogin');
    }

    private function normalizeValue($value)
    {
        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        if (is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        if ($value === null) return '';

        $value = (string)$value;

        $value = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value);

        return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
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

        // Kalau histori kosong
        if (empty($orders)) {
            return view('menu.histori.no-history');
        }

        return view('menu.histori.index', compact('orders'));
    }

    protected function mapSoapResponse(array $rows): array
    {
        if (isset($rows['field'])) $rows = [$rows];

        $mappedData = [];
        foreach ($rows as $row) {
            $fields = $row['field'] ?? [];
            if (isset($fields['@attributes'])) $fields = [$fields];

            $tmp = [];
            foreach ($fields as $f) {
                $attr = $f['@attributes'] ?? [];
                if (isset($attr['column'], $attr['lval'])) {
                    $tmp[$attr['column']] = $attr['lval'];
                }
            }
            if (!empty($tmp)) $mappedData[] = $tmp;
        }

        return $mappedData;
    }

    public function historiPlanner(Request $request)
    {
        $cacheKey = 'transport_status_history_finished';
        $cacheTime = 300;

        $mappedStatuses = Cache::remember($cacheKey, $cacheTime, function () {
            $response = $this->transportStatus->getAllTransportStatus();

            $rows = data_get($response, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);

            return $this->mapSoapResponse($rows);
        });

        $customerIds = collect($mappedStatuses)->pluck('Customer_ID')->filter()->unique()->all();
        $fleetIds    = collect($mappedStatuses)->pluck('XM_Fleet_ID')->filter()->unique()->all();

        $customerNames = DB::table('mzl.c_bpartner')
            ->whereIn('c_bpartner_id', $customerIds)
            ->pluck('name', 'c_bpartner_id')
            ->all();

        $fleetNames = DB::table('mzl.xm_fleet')
            ->whereIn('xm_fleet_id', $fleetIds)
            ->pluck('name', 'xm_fleet_id')
            ->all();

        $mappedStatuses = collect($mappedStatuses)->map(function ($item) use ($customerNames, $fleetNames) {
            $customerID = $item['Customer_ID'] ?? null;
            $fleetID = $item['XM_Fleet_ID'] ?? null;

            // ✅ Perbaikan keamanan Error 500 & Pemetaan Nama
            $item['Customer_Name'] = (isset($customerNames[$customerID]) && $customerID)
                ? $customerNames[$customerID]
                : null;

            $item['Fleet_Name'] = (isset($fleetNames[$fleetID]) && $fleetID)
                ? $fleetNames[$fleetID]
                : null;

            return $item;
        })->all();

        $collection = collect($mappedStatuses)
            ->sortByDesc(fn($r) => $r['ETA'] ?? '0000-01-01 00:00:00')
            ->values();

        $collection = $collection->map(function ($item) {
            foreach ($item as $key => $val) {
                $item[$key] = $this->normalizeValue($val);
            }
            return $item;
        });

        $search = $request->get('search');
        if ($search) {

            $search = strtolower($this->normalizeValue($search));

            $collection = $collection->filter(function ($item) use ($search) {

                return str_contains(strtolower($item['Value'] ?? ''), $search) ||
                    str_contains(strtolower($item['Customer_Name'] ?? ''), $search) ||
                    str_contains(strtolower($item['Fleet_Name'] ?? ''), $search) ||
                    str_contains(strtolower($item['PONumber'] ?? ''), $search);

            })->values();
        }

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagedData = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $orders = new LengthAwarePaginator(
            $pagedData,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('planner.history.index', compact('orders', 'search'));
    }

    public function detailPlanner($id)
    {
        $cacheKey = 'transport_status_history_finished';
        $cacheTime = 300; 

        $mappedStatuses = Cache::remember($cacheKey, $cacheTime, function () {

            $response = $this->transportStatus->getAllTransportStatus(); 
            $rows = data_get($response, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
            return $this->mapSoapResponse($rows);
        });

        $data = collect($mappedStatuses)->firstWhere('XX_TransOrder_ID', $id);
        
        if ($data) {
            
            $fleetId   = $data['XM_Fleet_ID'] ?? null;
            $driverId  = $data['XM_Driver_ID'] ?? null;
            $productId = $data['M_Product_ID'] ?? null; 
            $customerId  = $data['Customer_ID'] ?? null;

            if ($customerId) {
                $customerName = DB::table('mzl.c_bpartner')
                    ->where('c_bpartner_id', $customerId) 
                    ->value('name');
                $data['Customer_Name'] = $customerName;
            }

            if ($fleetId) {
                $fleetName = DB::table('mzl.xm_fleet')
                    ->where('xm_fleet_id', $fleetId)
                    ->value('name');
                $data['Fleet_Name'] = $fleetName;
            }
            
            if ($driverId) {
                $driverName = DB::table('mzl.xm_driver')
                    ->where('xm_driver_id', $driverId)
                    ->value('name');
                $data['Driver_Name'] = $driverName;
            }

            if ($productId) {
                $productName = DB::table('mzl.m_product')
                    ->where('m_product_id', $productId)
                    ->value('name');
                $data['Product_Name'] = $productName;
            }
        }
        if (!$data) {
            return redirect()->route('planner.history.index')->with('error', 'Detail Transport tidak ditemukan.');
        }
        
        try {
            $responseTracking = $this->transTrackingApi->getTransportTrackingByOrder($id);
            
            $trackingRows = data_get($responseTracking, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
            
            $trackingHistory = collect($this->mapSoapResponse($trackingRows))
                ->sortByDesc(fn($r) => $r['DocumentDate'] ?? $r['Created'] ?? '0000-01-01 00:00:00') 
                ->values();
                
        } catch (\Exception $e) {
            $trackingHistory = collect([]); 
            \Log::error("Gagal mengambil Tracking History untuk ID $id: " . $e->getMessage());
        }

        // dd($data, $trackingHistory);
        // Kirim $data (Detail) dan $trackingHistory (Riwayat) ke view
        return view('planner.history.detail', compact('data', 'trackingHistory'));
    }

}