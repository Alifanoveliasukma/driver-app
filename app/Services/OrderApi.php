<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class OrderApi extends BaseApi
{


    public function getOrderList($driverId)
    {
        $request = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:adin="http://3e.pl/ADInterface">
           <soapenv:Header/>
           <soapenv:Body>
              <adin:queryData>
                 <adin:ModelCRUDRequest>
                    <adin:ModelCRUD>
                       <adin:serviceType>API-GetOrderList</adin:serviceType>
                       <adin:TableName>XX_TransOrder</adin:TableName>
                       <adin:Filter>XM_Driver_ID=' . $driverId . '</adin:Filter>
                       <adin:Action>Read</adin:Action>
                       <adin:DataRow>
                          <adin:field column="IsActive">
                             <adin:val>Y</adin:val>
                          </adin:field>
                       </adin:DataRow>
                    </adin:ModelCRUD>
                    <adin:ADLoginRequest>
                       <adin:user>' . env('ERP_USER') . '</adin:user>
                       <adin:pass>' . env('ERP_PASS') . '</adin:pass>
                       <adin:lang>192</adin:lang>
                       <adin:ClientID>' . env('ERP_CLIENT') . '</adin:ClientID>
                       <adin:RoleID>' . env('ERP_ROLE') . '</adin:RoleID>
                       <adin:OrgID>' . env('ERP_ORG') . '</adin:OrgID>
                       <adin:WarehouseID>' . env('ERP_WH') . '</adin:WarehouseID>
                    </adin:ADLoginRequest>
                 </adin:ModelCRUDRequest>
              </adin:queryData>
           </soapenv:Body>
        </soapenv:Envelope>';

        return $this->sendRequest($request);
    }

    public function getOrderDetail($orderId)
    {
        $request = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:adin="http://3e.pl/ADInterface">
           <soapenv:Header/>
           <soapenv:Body>
              <adin:queryData>
                 <adin:ModelCRUDRequest>
                    <adin:ModelCRUD>
                       <adin:serviceType>API-OrderRead</adin:serviceType>
                       <adin:TableName>XX_TransOrder</adin:TableName>
                       <adin:RecordID>0</adin:RecordID>
                       <adin:Filter>XX_TransOrder_ID=' . $orderId . '</adin:Filter>
                       <adin:Action>Read</adin:Action>
                       <adin:DataRow>
                          <adin:field column="IsActive">
                             <adin:val>Y</adin:val>
                          </adin:field>
                       </adin:DataRow>
                    </adin:ModelCRUD>
                    <adin:ADLoginRequest>
                       <adin:user>' . env('ERP_USER') . '</adin:user>
                       <adin:pass>' . env('ERP_PASS') . '</adin:pass>
                       <adin:lang>192</adin:lang>
                       <adin:ClientID>' . env('ERP_CLIENT') . '</adin:ClientID>
                       <adin:RoleID>' . env('ERP_ROLE') . '</adin:RoleID>
                       <adin:OrgID>' . env('ERP_ORG') . '</adin:OrgID>
                       <adin:WarehouseID>' . env('ERP_WH') . '</adin:WarehouseID>
                    </adin:ADLoginRequest>
                 </adin:ModelCRUDRequest>
              </adin:queryData>
           </soapenv:Body>
        </soapenv:Envelope>';

        return $this->sendRequest($request);
    }

    public function getTransOrderWithCustomerAddress($transOrderId)
    {
        return DB::table('mzl.xx_transorder as t')
            ->select(
                't.xx_transorder_id',
                't.value',
                't.route',
                'bp.name as customer_name',
                'loc.address1 as customer_address',
                'loc.city',
                'loc.postal',
                'from_point.name as pickup_address',
                'to_point.name as delivery_address',
                DB::raw("to_char(t.loaddate, 'DD Mon YYYY HH24:MI') as pickup_time"),
                DB::raw("to_char(t.loaddatestart, 'DD Mon YYYY HH24:MI') as pickup_start"),
                DB::raw("to_char(t.unloaddate, 'DD Mon YYYY HH24:MI') as unload_time"),
                DB::raw("to_char(t.unloaddatestart, 'DD Mon YYYY HH24:MI') as unload_start"),
                't.drivername',
                't.vendorcar'
            )
            ->join('mzl.c_bpartner as bp', 'bp.c_bpartner_id', '=', 't.customer_id')
            ->leftJoin('mzl.c_bpartner_location as bpl', 'bpl.c_bpartner_id', '=', 'bp.c_bpartner_id')
            ->leftJoin('mzl.c_location as loc', 'loc.c_location_id', '=', 'bpl.c_location_id')
            ->leftJoin('mzl.xx_transroute as tr', 'tr.xx_transorder_id', '=', 't.xx_transorder_id') // perbaikan di sini
            ->leftJoin('mzl.xm_point as from_point', 'from_point.xm_point_id', '=', 'tr.from_id')   // perbaikan di sini
            ->leftJoin('mzl.xm_point as to_point', 'to_point.xm_point_id', '=', 'tr.to_id')         // perbaikan di sini
            ->where('t.xx_transorder_id', $transOrderId)
            ->first();
    }
}
