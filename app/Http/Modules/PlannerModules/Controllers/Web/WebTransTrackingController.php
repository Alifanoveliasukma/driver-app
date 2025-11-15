<?php

namespace App\Http\Modules\PlannerModules\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Modules\PlannerModules\Services\TransTrackingService;
use App\Services\TransportStatusApi;
use App\Services\TransTrackingApi;
use Illuminate\Http\Request;

class WebTransTrackingController extends Controller
{
    protected $transportStatus, $transTrackingApi;

    public function __construct(TransportStatusApi $transportStatus, TransTrackingApi $transTrackingApi)
    {
        $this->transportStatus = $transportStatus;
        $this->transTrackingApi = $transTrackingApi;
    }

    /**
     * Show history index page for planner
     */
    public function index(Request $request)
    {
        $res = TransTrackingService::getAllTransTracking($request);
        if ($res['success']) {
            $data = $res['data'];
            return view('planner.history.index', compact('data'));
        }
    }

    /**
     * Show detail page for planner
     */
    public function detail($id)
    {
        $data = TransTrackingService::getTransportStatusDetail($id, $this->transportStatus);

        if (!$data) {
            return redirect()->route('histori.planner')->with('error', 'Detail Transport tidak ditemukan.');
        }

        $trackingHistory = TransTrackingService::getTrackingHistory($id, $this->transTrackingApi);

        return view('planner.history.detail', compact('data', 'trackingHistory'));
    }

    // public function detailPlanner($id)
    // {
    //     $cacheKey = 'transport_status_history_finished';
    //     $cacheTime = 300; 

    //     $mappedStatuses = Cache::remember($cacheKey, $cacheTime, function () {

    //         $response = $this->transportStatus->getAllTransportStatus(); 
    //         $rows = data_get($response, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
    //         return $this->mapSoapResponse($rows);
    //     });

    //     $data = collect($mappedStatuses)->firstWhere('XX_TransOrder_ID', $id);
        
    //     if ($data) {
            
    //         $fleetId   = $data['XM_Fleet_ID'] ?? null;
    //         $driverId  = $data['XM_Driver_ID'] ?? null;
    //         $productId = $data['M_Product_ID'] ?? null; 
    //         $customerId  = $data['Customer_ID'] ?? null;

    //         if ($customerId) {
    //             $customerName = DB::table('mzl.c_bpartner')
    //                 ->where('c_bpartner_id', $customerId) 
    //                 ->value('name');
    //             $data['Customer_Name'] = $customerName;
    //         }

    //         if ($fleetId) {
    //             $fleetName = DB::table('mzl.xm_fleet')
    //                 ->where('xm_fleet_id', $fleetId)
    //                 ->value('name');
    //             $data['Fleet_Name'] = $fleetName;
    //         }
            
    //         if ($driverId) {
    //             $driverName = DB::table('mzl.xm_driver')
    //                 ->where('xm_driver_id', $driverId)
    //                 ->value('name');
    //             $data['Driver_Name'] = $driverName;
    //         }

    //         if ($productId) {
    //             $productName = DB::table('mzl.m_product')
    //                 ->where('m_product_id', $productId)
    //                 ->value('name');
    //             $data['Product_Name'] = $productName;
    //         }
    //     }
    //     if (!$data) {
    //         return redirect()->route('planner.history.index')->with('error', 'Detail Transport tidak ditemukan.');
    //     }
        
    //     try {
    //         $responseTracking = $this->transTrackingApi->getTransportTrackingByOrder($id);
            
    //         $trackingRows = data_get($responseTracking, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
            
    //         $trackingHistory = collect($this->mapSoapResponse($trackingRows))
    //             ->sortByDesc(fn($r) => $r['DocumentDate'] ?? $r['Created'] ?? '0000-01-01 00:00:00') 
    //             ->values();
                
    //     } catch (\Exception $e) {
    //         $trackingHistory = collect([]); 
    //         \Log::error("Gagal mengambil Tracking History untuk ID $id: " . $e->getMessage());
    //     }

    //     // dd($data, $trackingHistory);
    //     // Kirim $data (Detail) dan $trackingHistory (Riwayat) ke view
    //     return view('planner.history.detail', compact('data', 'trackingHistory'));
    // }
}