<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Services\DriverApi;
use App\Services\OrderApi;
use App\Services\OrderUpdateApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UtamaController extends Controller
{

    protected $order, $driver, $c_bpartner_id, $orderUpdate;

    public function __construct(OrderApi $order, DriverApi $driver, OrderUpdateApi $orderupdate)
    {
        $this->order = $order;
        $this->driver = $driver;
        $this->orderUpdate = $orderupdate;
    }

    public function getOrder()
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

        $orders = collect($mappedOrders)
            ->filter(fn($r) => !isset($r['Status']) || $r['Status'] !== 'FINISHED')
            ->sortBy(fn($r) => $r['ETD'] ?? '9999-12-31 23:59:59')
            ->values()
            ->take(6)
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

        $customerId = $mappedDetail['Customer_ID'] ?? null;
        $mappedDetail['Customer_Name'] = $customerId
            ? DB::table('mzl.c_bpartner')->where('c_bpartner_id', $customerId)->value('name')
            : '-';


        $route = $this->order->getTransOrderWithCustomerAddress($orderId)->route;

        $parts = explode('-', $route);


        $alamat_pengiriman = trim($parts[0] ?? '');
        $alamat_pengambilan = trim($parts[1] ?? '');

        $mappedDetail["alamat_pengiriman"] = $alamat_pengiriman;
        $mappedDetail["alamat_pengambilan"] = $alamat_pengambilan;



        return view('menu.utama.konfirmasi-berangkat', compact('mappedDetail', 'orderId'));
    }

    public function berangkat(Request $request)
    {
        $orderId = $request->input('orderId');

        if (empty($orderId)) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Order ID tidak ditemukan.'], 422);
            }
            return redirect()->route('utama.berangkat.list')->with('message', 'Order ID tidak ditemukan.');
        }

        $update = $this->orderUpdate->updateOrder($orderId, [
            'Status'      => 'LOAD',
            'OutLoadDate' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            $err = is_array($update['Error']) ? json_encode($update['Error']) : $update['Error'];
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal update: ' . $err], 400);
            }
            return redirect()->route('menu.detail-order', ['orderId' => $orderId])
                ->with('message', 'Gagal update: ' . $err);
        }

        $nextUrl = route('utama.konfirmasi-tiba-muat', ['orderId' => $orderId]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status diubah ke LOAD.',
                'nextUrl' => $nextUrl,
            ]);
        }

        return redirect()->to($nextUrl)->with('success', 'Status diubah ke LOAD.');
    }


    // public function berangkat(Request $request)
    // {
    //     $orderId = $request->input('orderId');

    //     if (empty($orderId)) {
    //         return redirect()
    //             ->route('utama.berangkat.list')
    //             ->with('message', 'Order ID tidak ditemukan.');
    //     }

    //     $update = $this->orderUpdate->updateOrder($orderId, [
    //         'Status'      => 'LOAD',
    //         'OutLoadDate' => now()->format('Y-m-d H:i:s'),
    //     ]);

    //     if (is_array($update) && isset($update['Error'])) {
    //     $err = is_array($update['Error']) ? json_encode($update['Error']) : $update['Error'];
    //     return redirect()
    //         ->route('menu.detail-order', ['orderId' => $orderId])
    //         ->with('message', 'Gagal update: '.$err);
    // }

    // return redirect()
    //     ->route('utama.konfirmasi-tiba-muat', ['orderId' => $orderId])
    //     ->with('success', 'Status diubah ke LOAD.');

    // }

    public function tibaMuatPage($orderId)
    {
        if (empty($orderId)) {
            return redirect()->route('utama.berangkat.list')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $detail = $this->order->getOrderDetail($orderId);

        $row    = data_get($detail, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
        $fields = data_get($row, 'field', []);
        if (isset($fields['@attributes'])) $fields = [$fields];

        $mappedDetail = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column'])) $mappedDetail[$attr['column']] = $attr['lval'] ?? null;
        }

        $customerId = $mappedDetail['Customer_ID'] ?? null;
        $mappedDetail['Customer_Name'] = $customerId
            ? DB::table('mzl.c_bpartner')->where('c_bpartner_id', $customerId)->value('name')
            : '-';



        $route = $this->order->getTransOrderWithCustomerAddress($orderId)->route;

        $parts = explode('-', $route);


        $alamat_pengiriman = trim($parts[0] ?? '');
        $alamat_pengambilan = trim($parts[1] ?? '');

        $mappedDetail["alamat_pengiriman"] = $alamat_pengiriman;
        $mappedDetail["alamat_pengambilan"] = $alamat_pengambilan;
        // dd($mappedDetail);
        return view('menu.utama.konfirmasi-tiba-muat', [
            'mappedDetail' => $mappedDetail,
            'orderId'      => $orderId,
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
            'Status'        => 'UNLOAD',
            'LoadDateStart' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            $err = is_array($update['Error']) ? json_encode($update['Error']) : $update['Error'];
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $err,
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status diubah ke UNLOAD.',
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

        $row    = data_get($detail, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
        $fields = data_get($row, 'field', []);
        if (isset($fields['@attributes'])) $fields = [$fields];

        $mappedDetail = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column'])) $mappedDetail[$attr['column']] = $attr['lval'] ?? null;
        }

        $customerId = $mappedDetail['Customer_ID'] ?? null;
        $mappedDetail['Customer_Name'] = $customerId
            ? DB::table('mzl.c_bpartner')->where('c_bpartner_id', $customerId)->value('name')
            : '-';

        return view('menu.utama.konfirmasi-selesai-muat', [
            'mappedDetail' => $mappedDetail,
            'orderId'      => $orderId,
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
            'Status'        => 'SHIPMENT',
            'LoadDate' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            $err = is_array($update['Error']) ? json_encode($update['Error']) : $update['Error'];
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $err,
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status diubah ke WAIT FOR LOAD.',
            'nextUrl' => route('utama.konfirmasi-keluar-muat', ['orderId' => $orderId]),
        ]);
    }

    public function keluarMuatPage($orderId)
    {
        if (empty($orderId)) {
            return redirect()->route('utama.berangkat.list')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $detail = $this->order->getOrderDetail($orderId);

        $row    = data_get($detail, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
        $fields = data_get($row, 'field', []);
        if (isset($fields['@attributes'])) $fields = [$fields];

        $mappedDetail = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column'])) $mappedDetail[$attr['column']] = $attr['lval'] ?? null;
        }

        $customerId = $mappedDetail['Customer_ID'] ?? null;
        $mappedDetail['Customer_Name'] = $customerId
            ?  DB::table('mzl.c_bpartner')->where('c_bpartner_id', $customerId)->value('name')
            : '-';
        // dd($mappedDetail);
        return view('menu.utama.konfirmasi-keluar-muat', [
            'mappedDetail' => $mappedDetail,
            'orderId'      => $orderId,
        ]);
    }

    public function keluarMuat(Request $request)
    {
        $orderId = $request->input('orderId');

        if (empty($orderId)) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID tidak ditemukan.'
            ], 422);
        }

        $update = $this->orderUpdate->updateOrder($orderId, [
            'Status'        => 'SHIPMENT',
            'LoadDateStart' => now()->format('Y-m-d H:i:s'),
        ]);

        if (is_array($update) && isset($update['Error'])) {
            $err = is_array($update['Error']) ? json_encode($update['Error']) : $update['Error'];
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $err,
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status diubah ke WAIT FOR LOAD.',
            'nextUrl' => route('utama.konfirmasi-tiba-tujuan', ['orderId' => $orderId]),
        ]);
    }

    public function tibaTujuanPage($orderId)
    {


        if (empty($orderId)) {
            return redirect()->route('utama.berangkat.list')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $detail = $this->order->getOrderDetail($orderId);

        $row    = data_get($detail, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow', []);
        $fields = data_get($row, 'field', []);
        if (isset($fields['@attributes'])) $fields = [$fields];

        $mappedDetail = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column'])) $mappedDetail[$attr['column']] = $attr['lval'] ?? null;
        }

        $customerId = $mappedDetail['Customer_ID'] ?? null;
        $mappedDetail['Customer_Name'] = $customerId
            ?  DB::table('mzl.c_bpartner')->where('c_bpartner_id', $customerId)->value('name')
            : '-';

        // dd($mappedDetail);

        return view('menu.utama.konfirmasi-tiba-tujuan', [
            'mappedDetail' => $mappedDetail,
            'orderId'      => $orderId,
        ]);
    }
}
