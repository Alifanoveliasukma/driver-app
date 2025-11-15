<?php

namespace App\Http\Modules\PlannerModules\Services;

use App\Models\orm\TransOrderModel;
use App\Services\TransportStatusApi;
use App\Services\TransTrackingApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransTrackingService
{
    /**
     * Get all transport tracking with pagination
     */
    public static function getAllTransTracking(Request $request)
    {
        $search = $request->get('search');
        $transTracking = TransOrderModel::getTransportSalesPaginated(10, $search);
        return [
            'success' => true,
            'data'    => $transTracking
        ];
    }

    /**
     * Map SOAP response to array
     */
    public static function mapSoapResponse(array $rows): array
    {
        if (isset($rows['field'])) {
            $rows = [$rows];
        }

        $mappedData = [];
        foreach ($rows as $row) {
            $fields = $row['field'] ?? [];
            if (isset($fields['@attributes'])) {
                $fields = [$fields];
            }

            $tmp = [];
            foreach ($fields as $f) {
                $attr = $f['@attributes'] ?? [];
                if (isset($attr['column'], $attr['lval'])) {
                    $tmp[$attr['column']] = $attr['lval'];
                }
            }
            if (!empty($tmp)) {
                $mappedData[] = $tmp;
            }
        }

        return $mappedData;
    }

    /**
     * Get transport status detail by ID
     */
    public static function getTransportStatusDetail($id, TransportStatusApi $transportStatusApi)
    {
        $cacheKey = 'transport_status_history_finished';
        $cacheTime = 300;

        $mappedStatuses = Cache::remember($cacheKey, $cacheTime, function () use ($transportStatusApi) {
            $response = $transportStatusApi->getAllTransportStatus();
            $rows = data_get($response, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
            return self::mapSoapResponse($rows);
        });

        $data = collect($mappedStatuses)->firstWhere('XX_TransOrder_ID', $id);

        if (!$data) {
            return null;
        }

        // Enrich with related data
        $fleetId = $data['XM_Fleet_ID'] ?? null;
        $driverId = $data['XM_Driver_ID'] ?? null;
        $productId = $data['M_Product_ID'] ?? null;
        $customerId = $data['Customer_ID'] ?? null;

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

        return $data;
    }

    /**
     * Get tracking history for transport order
     */
    public static function getTrackingHistory($id, TransTrackingApi $transTrackingApi)
    {
        try {
            $responseTracking = $transTrackingApi->getTransportTrackingByOrder($id);

            $trackingRows = data_get($responseTracking, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);

            $trackingHistory = collect(self::mapSoapResponse($trackingRows))
                ->sortByDesc(fn($r) => $r['DocumentDate'] ?? $r['Created'] ?? '0000-01-01 00:00:00')
                ->values();

            return $trackingHistory;
        } catch (\Exception $e) {
            Log::error("Gagal mengambil Tracking History untuk ID $id: " . $e->getMessage());
            return collect([]);
        }
    }
}