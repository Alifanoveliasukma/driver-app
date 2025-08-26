<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Services\DriverApi;
use App\Services\OrderApi;
use App\Services\OrderUpdateApi;
use App\Services\TrackingUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UtamaController extends Controller
{

    protected $order, $driver, $c_bpartner_id, $orderUpdate, $TrackingUpdate;

    public function __construct(OrderApi $order, DriverApi $driver, OrderUpdateApi $orderUpdate, TrackingUpdate $TrackingUpdate)
    {
        $this->order = $order;
        $this->driver = $driver;
        $this->orderUpdate = $orderUpdate;
        $this->TrackingUpdate = $TrackingUpdate;
    }

    public function checkStatus(array $orderDetail, string $except)
    {
        $status = $orderDetail['Status'] ?? null;

        $map = [
            'EXECUTE'   => 'menu.detail-order',
            'LOADOTW'   => 'utama.konfirmasi-tiba-muat',
            'LOADWAIT'  => 'utama.konfirmasi-mulai-muat',
            'LOAD'      => 'utama.konfirmasi-selesai-muat',
            'SHIPMENT'  => 'utama.konfirmasi-tiba-tujuan',
            'UNLOADWAIT' => 'utama.konfirmasi-mulai-bongkar',
            'UNLOAD'    => 'utama.konfirmasi-selesai-bongkar',
            'FINISHED'  => 'menu.list-order',
        ];


        if ($status && isset($map[$status]) && $status !== $except && $status !== "FINISHED") {
            return redirect()->route($map[$status], [
                'orderId' => $orderDetail['XX_TransOrder_ID'] ?? null,
            ]);
        }
        // return null;
        // kalau gak ada match, bisa fallback ke halaman awal
        // return redirect()->route('utama.berangkat.list');
    }


    public function getOrder()
    {
        $c_bpartner_id = session('c_bpartner_id');
        $driver = $this->driver->getDriver($c_bpartner_id);

        $fields = data_get($driver, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow.field', []);
        if (isset($fields['@attributes']))
            $fields = [$fields];

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
        if (isset($rows['field']))
            $rows = [$rows];

        $mappedOrders = [];
        foreach ($rows as $row) {
            $fs = $row['field'] ?? [];
            if (isset($fs['@attributes']))
                $fs = [$fs];

            $tmp = [];
            foreach ($fs as $f) {
                $attr = $f['@attributes'] ?? [];
                if (isset($attr['column'], $attr['lval'])) {
                    $tmp[$attr['column']] = $attr['lval'];
                }
            }
            if (!empty($tmp))
                $mappedOrders[] = $tmp;
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
                return !isset($r['Status']) || $r['Status'] !== 'FINISHED';
            })
            ->sortByDesc(function ($r) {
                return $r['ETD'] ?? '9999-12-31 23:59:59';
            })

            ->values()
            // ->take(7)
            ->map(function ($r) use ($customers, $routes) {
                $r['Customer_Name'] = $customers[$r['Customer_ID']] ?? '-';
                $route = $routes[$r['XX_TransOrder_ID']] ?? null;

                $r['route'] = $route->route ?? '-';
                return $r;
            })
            ->all();
        // dd($orders);

        return view('menu.utama.list-order', compact('orders'));
    }

    public function detailOrder($orderId)
    {
        if (empty($orderId)) {
            abort(404);
        }

        $detailOrder = $this->order->getOrderDetail($orderId);

        $row = data_get($detailOrder, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);

        $fields = data_get($row, 'field', []);
        if (isset($fields['@attributes'])) {
            $fields = [$fields];
        }

        $mappedDetail = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column'])) {
                $mappedDetail[$attr['column']] = $attr['lval'] ?? null;
            }
        }


        // dd($mappedDetail);




        $redirect = $this->checkStatus($mappedDetail, 'EXECUTE');

        if ($redirect) {
            return $redirect;
        }


        $customerId = $mappedDetail['Customer_ID'] ?? null;
        $mappedDetail['Customer_Name'] = $customerId
            ? DB::table('mzl.c_bpartner')->where('c_bpartner_id', $customerId)->value('name')
            : '-';

        $detailTransOrder = $this->order->getTransOrderWithCustomerAddress($orderId);


        $mappedDetail["pickup_address"] = $detailTransOrder->pickup_address;
        $mappedDetail["delivery_address"] = $detailTransOrder->delivery_address;
        $mappedDetail['route'] = $detailTransOrder->route;


        return view('menu.utama.konfirmasi-berangkat', compact('mappedDetail', 'orderId'));
    }

    public function berangkat(Request $request)
    {

        $orderId = $request->input('orderId');
        $kmTake = $request->input('kmTake');

        if (empty($orderId)) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Order ID tidak ditemukan.'], 422);
            }
            return redirect()->route('utama.berangkat.list')->with('message', 'Order ID tidak ditemukan.');
        }

        $update = $this->TrackingUpdate->UpdateTracking($orderId, [
            'Status' => 'LOADOTW',
            'Note' => 'driver confirmation',
            'Reference' => 'TMS',
            'KMTake' => $kmTake,
            'DateDoc' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            $err = is_array($update['Error']) ? json_encode($update['Error']) : $update['Error'];
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal update: ' . $err], 400);
            }
            return redirect()->route('menu.detail-order', ['orderId' => $orderId])
                ->with('message', 'Gagal update: ' . $err);
        }
        $updateStatus = $this->orderUpdate->updateOrder($orderId, [
            'Status' => ' LOADOTW',
        ]);

        if (is_array($updateStatus) && isset($updateStatus['Error'])) {
            $err = is_array($updateStatus['Error']) ? json_encode($updateStatus['Error']) : $updateStatus['Error'];
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $err,
            ], 400);
        }

        $nextUrl = route('utama.konfirmasi-tiba-muat', ['orderId' => $orderId]);

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $update,
                'success' => true,
                'message' => 'Status diubah ke LOADOTW.',
                'nextUrl' => $nextUrl,
            ]);
        }

        return redirect()->to($nextUrl)->with('success', 'Status diubah ke LOAD.');
    }

    public function tibaMuatPage($orderId)
    {
        if (empty($orderId)) {
            return redirect()->route('utama.berangkat.list')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $detail = $this->order->getOrderDetail($orderId);

        $row = data_get($detail, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
        $fields = data_get($row, 'field', []);
        if (isset($fields['@attributes']))
            $fields = [$fields];

        $mappedDetail = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column']))
                $mappedDetail[$attr['column']] = $attr['lval'] ?? null;
        }

        $redirect = $this->checkStatus($mappedDetail, 'LOADOTW');

        if ($redirect) {
            return $redirect;
        }


        $customerId = $mappedDetail['Customer_ID'] ?? null;
        $mappedDetail['Customer_Name'] = $customerId
            ? DB::table('mzl.c_bpartner')->where('c_bpartner_id', $customerId)->value('name')
            : '-';

        $detailTransOrder = $this->order->getTransOrderWithCustomerAddress($orderId);


        $mappedDetail["pickup_address"] = $detailTransOrder->pickup_address;
        $mappedDetail["delivery_address"] = $detailTransOrder->delivery_address;

        // dd($mappedDetail);
        return view('menu.utama.konfirmasi-tiba-muat', [
            'mappedDetail' => $mappedDetail,
            'orderId' => $orderId,
        ]);
    }

    public function tibaMuat(Request $request)
    {
        $orderId = $request->input('orderId');

        if (empty($orderId)) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID tidak ditemukan.'
            ], 422);
        }

        $update = $this->orderUpdate->updateOrder($orderId, [
            'Status' => ' LOADWAIT',
            'LoadDate' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            $err = is_array($update['Error']) ? json_encode($update['Error']) : $update['Error'];
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $err,
            ], 400);
        }

        $updateTracking = $this->TrackingUpdate->UpdateTracking($orderId, [
            'Status' => 'LOADWAIT',
            'Note' => 'driver confirmation',
            'Reference' => 'TMS',
            'DateDoc' => now()->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status diubah ke LOADWAIT.',
            'nextUrl' => route('utama.konfirmasi-selesai-muat', ['orderId' => $orderId]),
        ]);
    }


    public function mulaiMuatPage($orderId)
    {
        if (empty($orderId)) {
            return redirect()->route('utama.berangkat.list')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $detail = $this->order->getOrderDetail($orderId);

        $row = data_get($detail, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
        $fields = data_get($row, 'field', []);
        if (isset($fields['@attributes']))
            $fields = [$fields];

        $mappedDetail = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column']))
                $mappedDetail[$attr['column']] = $attr['lval'] ?? null;
        }

        $redirect = $this->checkStatus($mappedDetail, 'LOADWAIT');

        if ($redirect) {
            return $redirect;
        }


        $customerId = $mappedDetail['Customer_ID'] ?? null;
        $mappedDetail['Customer_Name'] = $customerId
            ? DB::table('mzl.c_bpartner')->where('c_bpartner_id', $customerId)->value('name')
            : '-';


        $detailTransOrder = $this->order->getTransOrderWithCustomerAddress($orderId);


        $mappedDetail["pickup_address"] = $detailTransOrder->pickup_address;
        $mappedDetail["delivery_address"] = $detailTransOrder->delivery_address;

        return view('menu.utama.konfirmasi-mulai-muat', [
            'mappedDetail' => $mappedDetail,
            'orderId' => $orderId,
        ]);
    }

    public function mulaiMuat(Request $request)
    {
        $orderId = $request->input('orderId');

        if (empty($orderId)) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID tidak ditemukan.'
            ], 422);
        }

        $update = $this->orderUpdate->updateOrder($orderId, [
            'Status' => 'LOAD',
            'LoadDateStart' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            $err = is_array($update['Error']) ? json_encode($update['Error']) : $update['Error'];
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $err,
            ], 400);
        }

        $updateTracking = $this->TrackingUpdate->UpdateTracking($orderId, [
            'Status' => 'LOAD',
            'Note' => 'driver confirmation',
            'Reference' => 'TMS',
            'DateDoc' => now()->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status diubah ke WAIT FOR LOAD.',
            'nextUrl' => route('utama.konfirmasi-selesai-muat', ['orderId' => $orderId]),
        ]);
    }

    public function selesaiMuatPage($orderId)
    {
        if (empty($orderId)) {
            return redirect()->route('utama.berangkat.list')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $detail = $this->order->getOrderDetail($orderId);

        $row = data_get($detail, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
        $fields = data_get($row, 'field', []);
        if (isset($fields['@attributes']))
            $fields = [$fields];

        $mappedDetail = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column']))
                $mappedDetail[$attr['column']] = $attr['lval'] ?? null;
        }

        $redirect = $this->checkStatus($mappedDetail, 'LOAD');

        if ($redirect) {
            return $redirect;
        }


        $customerId = $mappedDetail['Customer_ID'] ?? null;
        $mappedDetail['Customer_Name'] = $customerId
            ? DB::table('mzl.c_bpartner')->where('c_bpartner_id', $customerId)->value('name')
            : '-';

        $detailTransOrder = $this->order->getTransOrderWithCustomerAddress($orderId);

        $mappedDetail["pickup_address"] = $detailTransOrder->pickup_address;
        $mappedDetail["delivery_address"] = $detailTransOrder->delivery_address;

        return view('menu.utama.konfirmasi-selesai-muat', [
            'mappedDetail' => $mappedDetail,
            'orderId' => $orderId,
        ]);
    }

    public function selesaiMuat(Request $request)
    {
        $orderId = $request->input('orderId');

        if (empty($orderId)) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID tidak ditemukan.'
            ], 422);
        }

        $update = $this->orderUpdate->updateOrder($orderId, [
            'Status' => 'SHIPMENT',
            'OutLoadDate' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            $err = is_array($update['Error']) ? json_encode($update['Error']) : $update['Error'];
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $err,
            ], 400);
        }

        $updateTracking = $this->TrackingUpdate->UpdateTracking($orderId, [
            'Status' => 'LOADWAIT',
            'Note' => 'driver confirmation',
            'Reference' => 'TMS',
            'DateDoc' => now()->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status diubah ke WAIT FOR LOAD.',
            'nextUrl' => route('utama.konfirmasi-keluar-muat', ['orderId' => $orderId]),
        ]);
    }

    // public function keluarMuatPage($orderId)
    // {
    //     if (empty($orderId)) {
    //         return redirect()->route('utama.berangkat.list')
    //             ->with('message', 'Order ID tidak ditemukan.');
    //     }
    //     $detail = $this->order->getOrderDetail($orderId);

    //     $row = data_get($detail, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
    //     $fields = data_get($row, 'field', []);
    //     if (isset($fields['@attributes']))
    //         $fields = [$fields];

    //     $mappedDetail = [];
    //     foreach ($fields as $f) {
    //         $attr = $f['@attributes'] ?? [];
    //         if (isset($attr['column']))
    //             $mappedDetail[$attr['column']] = $attr['lval'] ?? null;
    //     }

    //     $customerId = $mappedDetail['Customer_ID'] ?? null;
    //     $mappedDetail['Customer_Name'] = $customerId
    //         ? DB::table('mzl.c_bpartner')->where('c_bpartner_id', $customerId)->value('name')
    //         : '-';
    //     $detailTransOrder = $this->order->getTransOrderWithCustomerAddress($orderId);


    //     $mappedDetail["pickup_address"] = $detailTransOrder->pickup_address;
    //     $mappedDetail["delivery_address"] = $detailTransOrder->delivery_address;

    //     // dd($mappedDetail);
    //     return view('menu.utama.konfirmasi-keluar-muat', [
    //         'mappedDetail' => $mappedDetail,
    //         'orderId' => $orderId,
    //     ]);
    // }

    // public function keluarMuat(Request $request)
    // {
    //     $orderId = $request->input('orderId');

    //     if (empty($orderId)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Order ID tidak ditemukan.'
    //         ], 422);
    //     }

    //     $update = $this->orderUpdate->updateOrder($orderId, [
    //         'Status' => ' SHIPMENT',
    //         'OutLoadDate'=> now()->format('Y-m-d H:i:s')
    //     ]);

    //     if (is_array($update) && isset($update['Error'])) {
    //         $err = is_array($update['Error']) ? json_encode($update['Error']) : $update['Error'];
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Gagal update: ' . $err,
    //         ], 400);
    //     }

    //     $updateTracking = $this->TrackingUpdate->UpdateTracking($orderId, [
    //         'Status' => 'SHIPMENT',
    //         'Note' => 'driver confirmation',
    //         'Reference' => 'TMS',
    //         'DateDoc' => now()->format('Y-m-d H:i:s'),
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Status diubah ke SHIPMENT.',
    //         'nextUrl' => route('utama.konfirmasi-tiba-tujuan', ['orderId' => $orderId]),
    //     ]);
    // }

    public function tibaTujuanPage($orderId)
    {

        if (empty($orderId)) {
            return redirect()->route('utama.berangkat.list')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $detail = $this->order->getOrderDetail($orderId);

        $row = data_get($detail, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
        $fields = data_get($row, 'field', []);
        if (isset($fields['@attributes']))
            $fields = [$fields];

        $mappedDetail = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column']))
                $mappedDetail[$attr['column']] = $attr['lval'] ?? null;
        }

        $redirect = $this->checkStatus($mappedDetail, 'SHIPMENT');

        if ($redirect) {
            return $redirect;
        }

        $customerId = $mappedDetail['Customer_ID'] ?? null;
        $mappedDetail['Customer_Name'] = $customerId
            ? DB::table('mzl.c_bpartner')->where('c_bpartner_id', $customerId)->value('name')
            : '-';

        $detailTransOrder = $this->order->getTransOrderWithCustomerAddress($orderId);


        $mappedDetail["pickup_address"] = $detailTransOrder->pickup_address;
        $mappedDetail["delivery_address"] = $detailTransOrder->delivery_address;

        // dd($mappedDetail);
        return view('menu.utama.konfirmasi-tiba-tujuan', [
            'mappedDetail' => $mappedDetail,
            'orderId' => $orderId,
        ]);
    }

    public function tibaTujuan(Request $request)
    {
        $orderId = $request->input('orderId');

        if (empty($orderId)) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID tidak ditemukan.'
            ], 422);
        }

        $update = $this->orderUpdate->updateOrder($orderId, [
            'Status' => 'UNLOADWAIT',
            'UnloadDate' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            $err = is_array($update['Error']) ? json_encode($update['Error']) : $update['Error'];
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $err,
            ], 400);
        }

        $updateTracking = $this->TrackingUpdate->UpdateTracking($orderId, [
            'Status' => 'UNLOADWAIT',
            'Note' => 'driver confirmation',
            'Reference' => 'TMS',
            'DateDoc' => now()->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status diubah ke UNLOADWAIT',
            'nextUrl' => route('utama.konfirmasi-mulai-bongkar', ['orderId' => $orderId]),
        ]);
    }

    public function mulaiBongkarPage($orderId)
    {
        if (empty($orderId)) {
            return redirect()->route('utama.berangkat.list')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $detail = $this->order->getOrderDetail($orderId);

        $row = data_get($detail, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
        $fields = data_get($row, 'field', []);
        if (isset($fields['@attributes']))
            $fields = [$fields];

        $mappedDetail = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column']))
                $mappedDetail[$attr['column']] = $attr['lval'] ?? null;
        }


        $redirect = $this->checkStatus($mappedDetail, 'UNLOADWAIT');

        if ($redirect) {
            return $redirect;
        }

        $customerId = $mappedDetail['Customer_ID'] ?? null;
        $mappedDetail['Customer_Name'] = $customerId
            ? DB::table('mzl.c_bpartner')->where('c_bpartner_id', $customerId)->value('name')
            : '-';

        $detailTransOrder = $this->order->getTransOrderWithCustomerAddress($orderId);


        $mappedDetail["pickup_address"] = $detailTransOrder->pickup_address;
        $mappedDetail["delivery_address"] = $detailTransOrder->delivery_address;

        // dd($mappedDetail);
        return view('menu.utama.konfirmasi-mulai-bongkar', [
            'mappedDetail' => $mappedDetail,
            'orderId' => $orderId,
        ]);
    }

    public function mulaiBongkar(Request $request)
    {
        $orderId = $request->input('orderId');

        if (empty($orderId)) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID tidak ditemukan.'
            ], 422);
        }

        $update = $this->orderUpdate->updateOrder($orderId, [
            'Status' => 'UNLOAD',
            'UnloadDateStart' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            $err = is_array($update['Error']) ? json_encode($update['Error']) : $update['Error'];
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $err,
            ], 400);
        }

        $updateTracking = $this->TrackingUpdate->UpdateTracking($orderId, [
            'Status' => 'LOAD',
            'Note' => 'driver confirmation',
            'Reference' => 'TMS',
            'DateDoc' => now()->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status diubah ke UNLOAD',
            'nextUrl' => route('utama.konfirmasi-keluar-bongkar', ['orderId' => $orderId]),
        ]);
    }

    public function selesaiBongkarPage($orderId)
    {
        if (empty($orderId)) {
            return redirect()->route('utama.berangkat.list')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $detail = $this->order->getOrderDetail($orderId);

        $row = data_get($detail, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
        $fields = data_get($row, 'field', []);
        if (isset($fields['@attributes']))
            $fields = [$fields];

        $mappedDetail = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column']))
                $mappedDetail[$attr['column']] = $attr['lval'] ?? null;
        }

        $redirect = $this->checkStatus($mappedDetail, 'UNLOAD');

        if ($redirect) {
            return $redirect;
        }

        $customerId = $mappedDetail['Customer_ID'] ?? null;
        $mappedDetail['Customer_Name'] = $customerId
            ? DB::table('mzl.c_bpartner')->where('c_bpartner_id', $customerId)->value('name')
            : '-';

        $detailTransOrder = $this->order->getTransOrderWithCustomerAddress($orderId);


        $mappedDetail["pickup_address"] = $detailTransOrder->pickup_address;
        $mappedDetail["delivery_address"] = $detailTransOrder->delivery_address;

        // dd($mappedDetail);
        return view('menu.utama.konfirmasi-selesai-bongkar', [
            'mappedDetail' => $mappedDetail,
            'orderId' => $orderId,
        ]);
    }

    public function selesaiBongkar(Request $request)
    {
        $orderId = $request->input('orderId');

        if (empty($orderId)) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID tidak ditemukan.'
            ], 422);
        }

        $update = $this->orderUpdate->updateOrder($orderId, [
            'Status' => 'UNLOADWAIT',
            'OutUnloadDate' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            $err = is_array($update['Error']) ? json_encode($update['Error']) : $update['Error'];
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $err,
            ], 400);
        }

        $updateTracking = $this->TrackingUpdate->UpdateTracking($orderId, [
            'Status' => 'UNLOADWAIT',
            'Note' => 'driver confirmation',
            'Reference' => 'TMS',
            'DateDoc' => now()->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status diubah ke UNLOADWAIT',
            'nextUrl' => route('utama.konfirmasi-keluar-bongkar', ['orderId' => $orderId]),
        ]);
    }

    public function keluarBongkarPage($orderId)
    {
        if (empty($orderId)) {
            return redirect()->route('utama.berangkat.list')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $detail = $this->order->getOrderDetail($orderId);

        $row = data_get($detail, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
        $fields = data_get($row, 'field', []);
        if (isset($fields['@attributes']))
            $fields = [$fields];

        $mappedDetail = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column']))
                $mappedDetail[$attr['column']] = $attr['lval'] ?? null;
        }

        $redirect = $this->checkStatus($mappedDetail, 'UNLOADWAIT');

        if ($redirect) {
            return $redirect;
        }

        $customerId = $mappedDetail['Customer_ID'] ?? null;
        $mappedDetail['Customer_Name'] = $customerId
            ? DB::table('mzl.c_bpartner')->where('c_bpartner_id', $customerId)->value('name')
            : '-';

        $detailTransOrder = $this->order->getTransOrderWithCustomerAddress($orderId);


        $mappedDetail["pickup_address"] = $detailTransOrder->pickup_address;
        $mappedDetail["delivery_address"] = $detailTransOrder->delivery_address;

        // dd($mappedDetail);
        return view('menu.utama.konfirmasi-keluar-bongkar', [
            'mappedDetail' => $mappedDetail,
            'orderId' => $orderId,
        ]);
    }

    public function keluarBongkar(Request $request)
    {
        $orderId = $request->input('orderId');

        if (empty($orderId)) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID tidak ditemukan.'
            ], 422);
        }

        $update = $this->orderUpdate->updateOrder($orderId, [
            'Status' => 'FINISHED',
        ]);

        if (is_array($update) && isset($update['Error'])) {
            $err = is_array($update['Error']) ? json_encode($update['Error']) : $update['Error'];
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $err,
            ], 400);
        }

        return response()->json([
            'data' => $update,
            'success' => true,
            'message' => 'Status diubah ke FINISHED',
            'nextUrl' => route('menu.list-order'),
        ]);
    }

    public function cek_status()
    {
        $orderId = '1138674';
        if (empty($orderId)) {
            return redirect()->route('utama.berangkat.list')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $detail = $this->order->getOrderDetail($orderId);

        $row = data_get($detail, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
        $fields = data_get($row, 'field', []);
        if (isset($fields['@attributes']))
            $fields = [$fields];

        $mappedDetail = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column']))
                $mappedDetail[$attr['column']] = $attr['lval'] ?? null;
        }

        $customerId = $mappedDetail['Customer_ID'] ?? null;
        $mappedDetail['Customer_Name'] = $customerId
            ? DB::table('mzl.c_bpartner')->where('c_bpartner_id', $customerId)->value('name')
            : '-';

        $detailTransOrder = $this->order->getTransOrderWithCustomerAddress($orderId);


        $mappedDetail["pickup_address"] = $detailTransOrder->pickup_address;
        $mappedDetail["delivery_address"] = $detailTransOrder->delivery_address;

        dd($mappedDetail);
        // return view('menu.utama.konfirmasi-keluar-bongkar', [
        //     'mappedDetail' => $mappedDetail,
        //     'orderId'      => $orderId,
        // ]);
    }
}
