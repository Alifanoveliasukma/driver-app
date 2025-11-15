<?php

namespace App\Http\Modules\DriverModules\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Modules\DriverModules\Services\UtamaService;
use App\Services\DriverApi;
use App\Services\OrderApi;
use App\Services\OrderUpdateApi;
use App\Services\TrackingUpdate;
use Illuminate\Http\Request;

class WebUtamaController extends Controller
{
    protected $order, $driver, $orderUpdate, $TrackingUpdate;

    public function __construct(OrderApi $order, DriverApi $driver, OrderUpdateApi $orderUpdate, TrackingUpdate $TrackingUpdate)
    {
        $this->order = $order;
        $this->driver = $driver;
        $this->orderUpdate = $orderUpdate;
        $this->TrackingUpdate = $TrackingUpdate;
        $this->middleware('checklogin');
    }


    public function getOrder()
    {
        $c_bpartner_id = session('c_bpartner_id');

        // Get driver ID
        $driverId = UtamaService::getDriverIdByBPartnerId($c_bpartner_id);

        if (!$driverId) {
            return view('menu.utama.no-order');
        }

        // Get order list
        $mappedOrders = UtamaService::getOrderList($driverId);

        // Enrich with customer and route data
        $orders = UtamaService::enrichOrdersData($mappedOrders);

        if (empty($orders)) {
            return view('menu.utama.no-order');
        }

        return view('menu.utama.list-order', compact('orders'));
    }


    public function detailOrder($orderId)
    {
        if (empty($orderId)) {
            abort(404);
        }

        // Get order detail
        $mappedDetail = UtamaService::getOrderDetail($orderId);

        if (!$mappedDetail) {
            abort(404);
        }

        // Check status and redirect if needed
        $redirect = UtamaService::checkStatus($mappedDetail, 'EXECUTE');
        if ($redirect) {
            return $redirect;
        }

        // Enrich with customer and addresses
        $enrichedDetail = UtamaService::enrichOrderDetail($orderId);

        return view('menu.utama.konfirmasi-berangkat', [
            'mappedDetail' => $enrichedDetail,
            'orderId' => $orderId
        ]);
    }


    public function berangkat(Request $request)
    {
        $orderId = $request->input('orderId');
        $kmTake = $request->input('kmTake');

        if (empty($orderId)) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Order ID tidak ditemukan.'], 422);
            }
            return redirect()->route('menu.list-order')->with('message', 'Order ID tidak ditemukan.');
        }

        $result = UtamaService::updateBerangkat($orderId, $kmTake, $this->TrackingUpdate, $this->orderUpdate);

        if (!$result['success']) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $result['message']], 400);
            }
            return redirect()->route('menu.detail-order', ['orderId' => $orderId])
                ->with('message', $result['message']);
        }

        $nextUrl = route('utama.konfirmasi-tiba-muat', ['orderId' => $orderId]);

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $result['data'],
                'success' => true,
                'message' => $result['message'],
                'nextUrl' => $nextUrl,
            ]);
        }

        return redirect()->to($nextUrl)->with('success', $result['message']);
    }


    public function tibaMuatPage($orderId)
    {
        if (empty($orderId)) {
            return redirect()->route('menu.list-order')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $mappedDetail = UtamaService::getOrderDetail($orderId);

        $redirect = UtamaService::checkStatus($mappedDetail, 'LOADOTW');
        if ($redirect) {
            return $redirect;
        }

        $enrichedDetail = UtamaService::enrichOrderDetail($orderId);

        return view('menu.utama.konfirmasi-tiba-muat', [
            'mappedDetail' => $enrichedDetail,
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

        $result = UtamaService::updateTibaMuat($orderId, $this->orderUpdate, $this->TrackingUpdate);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'nextUrl' => route('utama.konfirmasi-mulai-muat', ['orderId' => $orderId]),
        ]);
    }


    public function mulaiMuatPage($orderId)
    {
        if (empty($orderId)) {
            return redirect()->route('menu.list-order')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $mappedDetail = UtamaService::getOrderDetail($orderId);

        $redirect = UtamaService::checkStatus($mappedDetail, 'LOADWAIT');
        if ($redirect) {
            return $redirect;
        }

        $enrichedDetail = UtamaService::enrichOrderDetail($orderId);

        return view('menu.utama.konfirmasi-mulai-muat', [
            'mappedDetail' => $enrichedDetail,
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

        $result = UtamaService::updateMulaiMuat($orderId, $this->orderUpdate, $this->TrackingUpdate);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'nextUrl' => route('utama.konfirmasi-selesai-muat', ['orderId' => $orderId]),
        ]);
    }


    public function selesaiMuatPage($orderId)
    {
        if (empty($orderId)) {
            return redirect()->route('menu.list-order')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $mappedDetail = UtamaService::getOrderDetail($orderId);

        $redirect = UtamaService::checkStatus($mappedDetail, 'LOAD');
        if ($redirect) {
            return $redirect;
        }

        $enrichedDetail = UtamaService::enrichOrderDetail($orderId);

        return view('menu.utama.konfirmasi-selesai-muat', [
            'mappedDetail' => $enrichedDetail,
            'orderId' => $orderId,
        ]);
    }


    public function selesaiMuat(Request $request)
    {
        $orderId = $request->input('orderId');
        $fotoSupirPath = env('APP_URL') . $request->input("fotoSupirPath");
        $dokumenFilePath = env('APP_URL') . $request->input("dokumenFilePath");

        if (empty($orderId)) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID tidak ditemukan.'
            ], 422);
        }

        $result = UtamaService::updateSelesaiMuat($orderId, $fotoSupirPath, $dokumenFilePath, $this->orderUpdate, $this->TrackingUpdate);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'nextUrl' => route('utama.konfirmasi-tiba-tujuan', ['orderId' => $orderId]),
            'debug_update' => $result['debug_update'],
            'debug_tracking' => $result['debug_tracking'],
        ]);
    }


    public function tibaTujuanPage($orderId)
    {
        if (empty($orderId)) {
            return redirect()->route('menu.list-order')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $mappedDetail = UtamaService::getOrderDetail($orderId);

        $redirect = UtamaService::checkStatus($mappedDetail, 'SHIPMENT');
        if ($redirect) {
            return $redirect;
        }

        $enrichedDetail = UtamaService::enrichOrderDetail($orderId);

        return view('menu.utama.konfirmasi-tiba-tujuan', [
            'mappedDetail' => $enrichedDetail,
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

        $result = UtamaService::updateTibaTujuan($orderId, $this->orderUpdate, $this->TrackingUpdate);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'nextUrl' => route('utama.konfirmasi-mulai-bongkar', ['orderId' => $orderId]),
        ]);
    }


    public function mulaiBongkarPage($orderId)
    {
        if (empty($orderId)) {
            return redirect()->route('menu.list-order')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $mappedDetail = UtamaService::getOrderDetail($orderId);

        $redirect = UtamaService::checkStatus($mappedDetail, 'UNLOADWAIT');
        if ($redirect) {
            return $redirect;
        }

        $enrichedDetail = UtamaService::enrichOrderDetail($orderId);

        return view('menu.utama.konfirmasi-mulai-bongkar', [
            'mappedDetail' => $enrichedDetail,
            'orderId' => $orderId,
        ]);
    }


    public function mulaiBongkar(Request $request)
    {
        $orderId = $request->input('orderId');
        $fotoMuatanPath = env('APP_URL') . $request->input("fotoMuatanPath");

        if (empty($orderId)) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID tidak ditemukan.'
            ], 422);
        }

        $result = UtamaService::updateMulaiBongkar($orderId, $fotoMuatanPath, $this->orderUpdate, $this->TrackingUpdate);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'nextUrl' => route('utama.konfirmasi-keluar-bongkar', ['orderId' => $orderId]),
            'debug_update' => $result['debug_update'],
            'debug_tracking' => $result['debug_tracking'],
        ]);
    }


    public function keluarBongkarPage($orderId)
    {
        if (empty($orderId)) {
            return redirect()->route('menu.list-order')
                ->with('message', 'Order ID tidak ditemukan.');
        }

        $mappedDetail = UtamaService::getOrderDetail($orderId);

        $redirect = UtamaService::checkStatus($mappedDetail, 'UNLOAD');
        if ($redirect) {
            return $redirect;
        }

        $enrichedDetail = UtamaService::enrichOrderDetail($orderId);

        return view('menu.utama.konfirmasi-keluar-bongkar', [
            'mappedDetail' => $enrichedDetail,
            'orderId' => $orderId,
        ]);
    }


    public function keluarBongkar(Request $request)
    {
        $orderId = $request->input('orderId');
        $fotoSuratJalanPath = env('APP_URL') . $request->input("fotoSuratJalanPath");

        if (empty($orderId)) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID tidak ditemukan.'
            ], 422);
        }

        $result = UtamaService::updateKeluarBongkar($orderId, $fotoSuratJalanPath, $this->orderUpdate, $this->TrackingUpdate);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }

        return response()->json([
            'data' => $result['data'],
            'success' => true,
            'message' => $result['message'],
            'nextUrl' => route('menu.list-order'),
        ]);
    }

    /**
     * Development helper - Get order detail (for debugging)
     */
    public function getOrderDetail()
    {
        $orderId = '1455576';
        $detail = $this->order->getOrderDetail($orderId);
        return $detail;
    }
}
