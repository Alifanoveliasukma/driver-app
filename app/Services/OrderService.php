<?php

namespace App\Services;

use Mtownsend\XmlToArray\XmlToArray;

class OrderService
{
    public function getOrders($driverId)
    {
        $request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:adin="http://3e.pl/ADInterface">
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
                            <adin:user>SuperUser</adin:user>
                            <adin:pass>sembilanbahanpokok</adin:pass>
                            <adin:lang>192</adin:lang>
                            <adin:ClientID>1000002</adin:ClientID>
                            <adin:RoleID>1000037</adin:RoleID>
                            <adin:OrgID>1000005</adin:OrgID>
                            <adin:WarehouseID>1000003</adin:WarehouseID>
                        </adin:ADLoginRequest>
                    </adin:ModelCRUDRequest>
                </adin:queryData>
            </soapenv:Body>
        </soapenv:Envelope>';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('API_URL')); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: text/xml; charset=utf-8",
            "Content-Length: " . strlen($request)
        ]);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode != 200) {
            return ['error' => 'Service unavailable'];
        }

        return XmlToArray::convert($response);
    }
}
