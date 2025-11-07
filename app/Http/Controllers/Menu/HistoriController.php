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

    public function historiPlanner(Request $request)
    {
        $cacheKey = 'transport_status_history';
        $cacheTime = 300; 

        $mappedStatuses = Cache::remember($cacheKey, $cacheTime, function () {
            $response = $this->transportStatus->getAllTransportStatus();
            
            $rows = data_get($response, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
            if (isset($rows['field'])) $rows = [$rows];

            $mappedStatuses = [];
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
                if (!empty($tmp)) $mappedStatuses[] = $tmp;
            }

            return $mappedStatuses;
        });

        $collection = collect($mappedStatuses)
            ->sortByDesc(fn($r) => $r['ETA'] ?? '0000-01-01 00:00:00')
            ->values();

        $search = $request->get('search');
        if ($search) {
            $collection = $collection->filter(function ($item) use ($search) {
                
                return str_contains(strtolower($item['Value'] ?? ''), strtolower($search)) || 
                    str_contains(strtolower($item['Route'] ?? ''), strtolower($search)) ||
                    str_contains(strtolower($item['Customer_ID'] ?? ''), strtolower($search)) ||
                    str_contains(strtolower($item['XM_Driver_ID'] ?? ''), strtolower($search)) ||
                    str_contains(strtolower($item['XM_Fleet_ID'] ?? ''), strtolower($search)) ||
                    str_contains(strtolower($item['PONumber'] ?? ''), strtolower($search)) ||
                    str_contains(strtolower($item['Status'] ?? ''), strtolower($search));
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

        // dd($orders);
        return view('planner.history.index', compact('orders', 'search'));
    }

    public function detailPlanner($id)
    {
        $cacheKey = 'transport_status_history';
        $cacheTime = 300; // 5 menit

        // Ambil data cache (reuse dari historiPlanner)
        $mappedStatuses = Cache::remember($cacheKey, $cacheTime, function () {
            $response = $this->transportStatus->getAllTransportStatus();
            
            $rows = data_get($response, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
            if (isset($rows['field'])) $rows = [$rows];

            $mappedStatuses = [];
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
                if (!empty($tmp)) $mappedStatuses[] = $tmp;
            }

            return $mappedStatuses;
        });

        $data = collect($mappedStatuses)->firstWhere('XX_TransOrder_ID', $id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // dd($data);
        return view('planner.history.detail', compact('data'));
    }

    // public function detailPlanner($id)
    // {
    //     \Log::info('Opening transport planner detail', ['XX_TransOrder_ID' => $id]);

    //     $response = $this->transportStatus->getDetailByTransportStatusId($id);

    //     $fields = data_get($response, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow.field', []);

    //     if (isset($fields['@attributes'])) {
    //         $fields = [$fields];
    //     }

    //     $data = [];
    //     foreach ($fields as $field) {
    //         $attr = $field['@attributes'] ?? [];
    //         if (isset($attr['column'], $attr['lval'])) {
    //             $data[$attr['column']] = $attr['lval'];
    //         }
    //     }

    //     dd($data);
    //     return view('planner.history.detail', compact('data'));
    // }

    //  public function detailPlanner($id)
    // {
    //     \Log::info('Transport Status ID Received:', ['id' => $id]);

    //     if (!is_numeric($id)) {
    //         \Log::warning('Invalid ID format for Transport Status', ['id' => $id]);
    //         return redirect()->back()->with('error', 'Invalid ID format');
    //     }

    //     $response = $this->transTrackingApi->getDetailByTransportStatusId($id);

    //     if (isset($response['error'])) {
    //         \Log::error('SOAP Error Response:', $response);
    //         return redirect()->back()->with('error', 'Gagal mengambil data dari server.');
    //     }

    //     $data = $response['data'] ?? $response;
    //     // dd($data);
    //     return view('planner.history.detail', compact('data'));
    // }

}