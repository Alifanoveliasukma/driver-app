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
            // Asumsi: getAllTransportStatus() sudah memuat filter Status='FINISHED'
            $response = $this->transportStatus->getAllTransportStatus(); 
            
            $rows = data_get($response, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
            
            return $this->mapSoapResponse($rows);
        });

        $collection = collect($mappedStatuses)
            ->sortByDesc(fn($r) => $r['ETA'] ?? '0000-01-01 00:00:00')
            ->values();

        $search = $request->get('search');
        if ($search) {
            $collection = $collection->filter(function ($item) use ($search) {
                $search = strtolower($search);
                return str_contains(strtolower($item['Value'] ?? ''), $search) || 
                    str_contains(strtolower($item['Route'] ?? ''), $search) ||
                    str_contains(strtolower($item['Customer_ID'] ?? ''), $search) ||
                    str_contains(strtolower($item['XM_Driver_ID'] ?? ''), $search) ||
                    str_contains(strtolower($item['XM_Fleet_ID'] ?? ''), $search) ||
                    str_contains(strtolower($item['PONumber'] ?? ''), $search) ||
                    str_contains(strtolower($item['Status'] ?? ''), $search);
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
        // 1. Ambil Detail Transport (dari getAllTransportStatus)
        $cacheKey = 'transport_status_history_finished';
        $cacheTime = 300; 

        // Mengambil data detail dari cache yang sama dengan historiPlanner
        $mappedStatuses = Cache::remember($cacheKey, $cacheTime, function () {
            // Logika ini sama persis dengan yang ada di historiPlanner
            $response = $this->transportStatus->getAllTransportStatus(); 
            $rows = data_get($response, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
            return $this->mapSoapResponse($rows);
        });

        // Cari detail utama berdasarkan ID
        $data = collect($mappedStatuses)->firstWhere('XX_TransOrder_ID', $id);

        if (!$data) {
            return redirect()->route('planner.history.index')->with('error', 'Detail Transport tidak ditemukan.');
        }
        
        try {
            $responseTracking = $this->transTrackingApi->getTransportTrackingByOrder($id);
            
            $trackingRows = data_get($responseTracking, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
            
            $trackingHistory = collect($this->mapSoapResponse($trackingRows))
                // Urutkan berdasarkan tanggal (misalnya kolom 'DocumentDate' atau 'Created') terbaru
                ->sortByDesc(fn($r) => $r['DocumentDate'] ?? $r['Created'] ?? '0000-01-01 00:00:00') 
                ->values();
                
        } catch (\Exception $e) {
            // Handle jika panggilan API Tracking gagal
            $trackingHistory = collect([]); 
            \Log::error("Gagal mengambil Tracking History untuk ID $id: " . $e->getMessage());
        }

        // dd($data, $trackingHistory);
        // Kirim $data (Detail) dan $trackingHistory (Riwayat) ke view
        return view('planner.history.detail', compact('data', 'trackingHistory'));
    }

}