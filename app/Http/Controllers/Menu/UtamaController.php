<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Services\DriverApi;
use App\Services\OrderApi;
use App\Services\OrderUpdateApi;
use Illuminate\Http\Request;

class UtamaController extends Controller
{

    protected $order, $driver, $c_bpartner_id;

    public function __construct(OrderApi $order, DriverApi $driver, OrderUpdateApi $orderupdate)
    {
        $this->order = $order;
        $this->driver = $driver;
        $this->orderUpdate = $orderupdate;
    }

    public function index()
    {

        $c_bpartner_id = session('c_bpartner_id');


        $driver = $this->driver->getDriver($c_bpartner_id);
        $fields = $driver['soap:Body']['ns1:queryDataResponse']['WindowTabData']['DataSet']['DataRow']['field'] ?? [];

        $mappedDriver = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column'], $attr['lval'])) {
                $mappedDriver[$attr['column']] = $attr['lval'];
            }
        }

        $driverId = $mappedDriver['XM_Driver_ID'] ?? null;

        $order = $this->order->getOrderList($driverId);

        $rows = $order['soap:Body']['ns1:queryDataResponse']['WindowTabData']['DataSet']['DataRow'] ?? [];

        $mappedOrders = [];


        if (isset($rows['field'])) {
            $rows = [$rows];
        }

        foreach ($rows as $row) {
            $fields = $row['field'] ?? [];
            $mappedRow = [];

            foreach ($fields as $f) {
                $attr = $f['@attributes'] ?? [];
                if (isset($attr['column'], $attr['lval'])) {
                    $mappedRow[$attr['column']] = $attr['lval'];
                }
            }

            if (!empty($mappedRow)) {
                $mappedOrders[] = $mappedRow;
            }
        }

        $orderId = $mappedOrders[0]['XX_TransOrder_ID'] ?? null;
        // dd($orderId);

        $detailOrder = $this->order->getOrderDetail($orderId);
        
        $row = $detailOrder['soap:Body']['ns1:queryDataResponse']['WindowTabData']['DataSet']['DataRow'] ?? [];

        $fields = $row['field'] ?? [];
        if (isset($fields['@attributes'])) {
            $fields = [$fields];
        }

        $mappedDetail = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column'], $attr['lval'])) {
                $mappedDetail[$attr['column']] = $attr['lval'];
            }
        }

        $customerId = $mappedDetail['Customer_ID'] ?? null;
        $customerName = null;
        if ($customerId) {
            $customerName = \DB::table('mzl.c_bpartner')
                ->where('c_bpartner_id', $customerId)
                ->value('name'); 
        }

        $mappedDetail['Customer_Name'] = $customerName ?? '-';

        return view('menu.utama.konfirmasi-berangkat', compact('mappedDetail'));
    }

    public function tibaMuat(Request $request)
    {
        $outLoadDate = $request->input('OutLoadDate'); 
        $orderId     = $request->input('orderId'); 

        $updateResult = $this->orderUpdateApi->updateOrder($orderId, [
            'OutLoadDate' => $outLoadDate,
        ]);

        return response()->json([
            'success' => true,
            'data' => $updateResult
        ]);
        
    }
}
