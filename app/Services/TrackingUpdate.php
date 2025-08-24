<?php

namespace App\Services;

class TrackingUpdate extends BaseApi
{
    public function UpdateTracking($orderId, array $fields)
    {
        // Mulai susun request SOAP
        $request = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"xmlns:adin="http://3e.pl/ADInterface">
            <soapenv:Header/>
                <soapenv:Body>
                    <adin:createData>
                    <adin:ModelCRUDRequest>
                    <adin:ModelCRUD>
                    <adin:serviceType>API-CPOINT</adin:serviceType>
                    <adin:TableName>XX_TransTracking</adin:TableName>
                    <adin:RecordID>0</adin:RecordID>
                    <adin:Action>Create</adin:Action>
                    <adin:DataRow>
                    <adin:field column="OrderID">
                        <adin:val>' . $orderId . '</adin:val>
                    </adin:field>
                    ';
        foreach ($fields as $column => $value) {
            $request .= '
                    <adin:field column="' . $column . '">
                        <adin:val>' . $value . '</adin:val>
                        <adin:lookup>
                            <adin:lv val="?" key="?"/>
                        </adin:lookup>
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
