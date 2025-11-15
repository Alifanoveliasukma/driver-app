<?php

namespace App\Http\Modules\DriverModules\Services;

use App\Models\orm\DriverModel;
use App\Models\orm\TransOrderModel;
use App\Services\OrderUpdateApi;
use App\Services\TrackingUpdate;

class UtamaService
{

    public static function checkStatus($orderDetail, string $except)
    {
        if (!$orderDetail) {
            return null;
        }

        $status = $orderDetail->status ?? null;

        $map = [
            '' => 'menu.detail-order',
            'EXECUTE' => 'menu.detail-order',
            'LOADOTW' => 'utama.konfirmasi-tiba-muat',
            'LOADWAIT' => 'utama.konfirmasi-mulai-muat',
            'LOAD' => 'utama.konfirmasi-selesai-muat',
            'SHIPMENT' => 'utama.konfirmasi-tiba-tujuan',
            'UNLOADWAIT' => 'utama.konfirmasi-mulai-bongkar',
            'UNLOAD' => 'utama.konfirmasi-keluar-bongkar',
            'FINISHED' => 'menu.list-order',
        ];

        if ($status && isset($map[$status]) && $status !== $except) {
            $orderId = $orderDetail->xx_transorder_id ?? null;
            return redirect()->route($map[$status], ['orderId' => $orderId]);
        }

        return null;
    }


    public static function getDriverIdByBPartnerId($c_bpartner_id)
    {
        return DriverModel::getDriverIdByBPartnerId($c_bpartner_id);
    }


    public static function getOrderList($driverId)
    {
        return TransOrderModel::getOrderListByDriver($driverId, 'N');
    }


    public static function enrichOrdersData($orders)
    {
        return $orders->filter(function ($r) {
            return !isset($r->status) || $r->status !== 'FINISHED';
        })
            ->sortByDesc(function ($r) {
                return $r->etd ?? '9999-12-31 23:59:59';
            })
            ->values();
    }


    public static function getOrderDetail($orderId)
    {
        return TransOrderModel::getOrderDetailById($orderId);
    }


    public static function enrichOrderDetail($orderId)
    {
        return TransOrderModel::getTransOrderWithCustomerAddress($orderId);
    }


    public static function updateBerangkat($orderId, $kmTake, TrackingUpdate $trackingUpdate, OrderUpdateApi $orderUpdate)
    {
        $updateTracking = $trackingUpdate->UpdateTracking($orderId, [
            'Status' => 'LOADOTW',
            'Note' => 'driver confirmation',
            'Reference' => 'AUD',
            'KMTake' => $kmTake,
            'DateDoc' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($updateTracking) && isset($updateTracking['Error'])) {
            return [
                'success' => false,
                'message' => is_array($updateTracking['Error'])
                    ? json_encode($updateTracking['Error'])
                    : $updateTracking['Error']
            ];
        }

        $updateStatus = $orderUpdate->updateOrder($orderId, [
            'Status' => 'LOADOTW',
        ]);

        if (is_array($updateStatus) && isset($updateStatus['Error'])) {
            return [
                'success' => false,
                'message' => is_array($updateStatus['Error'])
                    ? json_encode($updateStatus['Error'])
                    : $updateStatus['Error']
            ];
        }

        return [
            'success' => true,
            'data' => $updateTracking,
            'message' => 'Status diubah ke LOADOTW.'
        ];
    }


    public static function updateTibaMuat($orderId, OrderUpdateApi $orderUpdate, TrackingUpdate $trackingUpdate)
    {
        $update = $orderUpdate->updateOrder($orderId, [
            'Status' => 'LOADWAIT',
            'LoadDate' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            return [
                'success' => false,
                'message' => is_array($update['Error'])
                    ? json_encode($update['Error'])
                    : $update['Error']
            ];
        }

        $updateTracking = $trackingUpdate->UpdateTracking($orderId, [
            'Status' => 'LOADWAIT',
            'Note' => 'driver confirmation',
            'Reference' => 'AUD',
            'DateDoc' => now()->format('Y-m-d H:i:s'),
        ]);

        return [
            'success' => true,
            'message' => 'Status diubah ke LOADWAIT.'
        ];
    }

    public static function updateMulaiMuat($orderId, OrderUpdateApi $orderUpdate, TrackingUpdate $trackingUpdate)
    {
        $update = $orderUpdate->updateOrder($orderId, [
            'Status' => 'LOAD',
            'LoadDateStart' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            return [
                'success' => false,
                'message' => is_array($update['Error'])
                    ? json_encode($update['Error'])
                    : $update['Error']
            ];
        }

        $updateTracking = $trackingUpdate->UpdateTracking($orderId, [
            'Status' => 'LOAD',
            'Note' => 'driver confirmation',
            'Reference' => 'AUD',
            'DateDoc' => now()->format('Y-m-d H:i:s'),
        ]);

        return [
            'success' => true,
            'message' => 'Status diubah ke LOAD.'
        ];
    }


    public static function updateSelesaiMuat($orderId, $fotoSupirPath, $dokumenFilePath, OrderUpdateApi $orderUpdate, TrackingUpdate $trackingUpdate)
    {
        $update = $orderUpdate->updateOrder($orderId, [
            'Status' => 'SHIPMENT',
            'OutLoadDate' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            return [
                'success' => false,
                'message' => is_array($update['Error'])
                    ? json_encode($update['Error'])
                    : $update['Error']
            ];
        }

        $updateTracking = $trackingUpdate->UpdateTracking($orderId, [
            'Status' => 'SHIPMENT',
            'Note' => 'driver confirmation',
            'Reference' => 'AUD',
            'DateDoc' => now()->format('Y-m-d H:i:s'),
            'DocumentDir' => $fotoSupirPath,
            'DocumentDir2' => $dokumenFilePath
        ]);

        return [
            'success' => true,
            'message' => 'Status diubah ke SHIPMENT.',
            'debug_update' => $update,
            'debug_tracking' => $updateTracking
        ];
    }


    public static function updateTibaTujuan($orderId, OrderUpdateApi $orderUpdate, TrackingUpdate $trackingUpdate)
    {
        $update = $orderUpdate->updateOrder($orderId, [
            'Status' => 'UNLOADWAIT',
            'UnloadDate' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            return [
                'success' => false,
                'message' => is_array($update['Error'])
                    ? json_encode($update['Error'])
                    : $update['Error']
            ];
        }

        $updateTracking = $trackingUpdate->UpdateTracking($orderId, [
            'Status' => 'UNLOADWAIT',
            'Note' => 'driver confirmation',
            'Reference' => 'AUD',
            'DateDoc' => now()->format('Y-m-d H:i:s'),
        ]);

        return [
            'success' => true,
            'message' => 'Status diubah ke UNLOADWAIT'
        ];
    }


    public static function updateMulaiBongkar($orderId, $fotoMuatanPath, OrderUpdateApi $orderUpdate, TrackingUpdate $trackingUpdate)
    {
        $update = $orderUpdate->updateOrder($orderId, [
            'Status' => 'UNLOAD',
            'UnloadDateStart' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            return [
                'success' => false,
                'message' => is_array($update['Error'])
                    ? json_encode($update['Error'])
                    : $update['Error']
            ];
        }

        $updateTracking = $trackingUpdate->UpdateTracking($orderId, [
            'Status' => 'UNLOAD',
            'Note' => 'driver confirmation',
            'Reference' => 'AUD',
            'DateDoc' => now()->format('Y-m-d H:i:s'),
            'DocumentDir' => $fotoMuatanPath
        ]);

        return [
            'success' => true,
            'message' => 'Status diubah ke UNLOAD',
            'debug_update' => $update,
            'debug_tracking' => $updateTracking
        ];
    }


    public static function updateKeluarBongkar($orderId, $fotoSuratJalanPath, OrderUpdateApi $orderUpdate, TrackingUpdate $trackingUpdate)
    {
        $updateTracking = $trackingUpdate->UpdateTracking($orderId, [
            'Status' => 'FINISHED',
            'Note' => 'driver confirmation',
            'Reference' => 'AUD',
            'DateDoc' => now()->format('Y-m-d H:i:s'),
            'DocumentDir' => $fotoSuratJalanPath,
        ]);

        if (is_array($updateTracking) && isset($updateTracking['error'])) {
            return [
                'success' => false,
                'message' => is_array($updateTracking['error'])
                    ? json_encode($updateTracking['error'])
                    : $updateTracking['error']
            ];
        }

        $update = $orderUpdate->updateOrder($orderId, [
            'Status' => 'FINISHED',
            'OutUnloadDate' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['error'])) {
            return [
                'success' => false,
                'message' => is_array($update['error'])
                    ? json_encode($update['error'])
                    : $update['error']
            ];
        }

        return [
            'success' => true,
            'data' => $update,
            'message' => 'Status diubah ke FINISHED'
        ];
    }
}
