<?php

namespace App\Services;

class OrderUpdateApi extends BaseApi
{
    /**
     * Update order di ERP lewat SOAP API.
     *
     * @param int   $orderId  ID order (XX_TransOrder_ID)
     * @param array $fields   Kolom yang akan diupdate => nilai baru
     *                        contoh:
     *                        [
     *                          'LoadDate'        => '2028-08-22 08:00:00',
     *                          'LoadDateStart'   => '2028-08-22 07:30:00',
     *                          'OutLoadDate'     => '2028-08-22 09:00:00',
     *                          'UnloadDate'      => '2028-08-23 15:00:00',
     *                          'UnloadDateStart' => '2028-08-23 14:30:00',
     *                          'OutUnloadDate'   => '2028-08-23 16:00:00',
     *                          'LoadStd'         => 'Y',
     *                        ]
     */
    public function updateOrder($orderId, array $fields)
    {
        // Mulai susun request SOAP
        $request = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:adin="http://3e.pl/ADInterface">
           <soapenv:Header/>
           <soapenv:Body>
              <adin:updateData>
                 <adin:ModelCRUDRequest>
                    <adin:ModelCRUD>
                       <adin:serviceType>API-OrderPatch</adin:serviceType>
                       <adin:TableName>XX_TransOrder</adin:TableName>
                       <adin:RecordID>' . $orderId . '</adin:RecordID>
                       <adin:Action>Update</adin:Action>
                       <adin:DataRow>';
        foreach ($fields as $column => $value) {
            $request .= '
                                        <adin:field column="' . $column . '">
                                            <adin:val>' . $value . '</adin:val>
                                        </adin:field>';
        }
        $request .= '
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
              </adin:updateData>
           </soapenv:Body>
        </soapenv:Envelope>';

        // Kirim request ke ERP pakai BaseApi
        return $this->sendRequest($request);
    }
}
