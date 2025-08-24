<?php

namespace App\Services;

class TrackingUpdate extends BaseApi
{
    public function UpdateTracking($orderId, array $fields)
    {
        // Tambahkan OrderID ke fields
        $fields = array_merge(['OrderID' => $orderId], $fields);

        // Bangun string <adin:field> dari setiap key => value
        $fieldsXml = '';
        foreach ($fields as $column => $value) {
            $fieldsXml .= '
                        <adin:field column="' . htmlspecialchars($column) . '">
                            <adin:val>' . htmlspecialchars($value) . '</adin:val>
                        </adin:field>';
        }

        // Susun SOAP request
        $request = '
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
    xmlns:adin="http://3e.pl/ADInterface">
    <soapenv:Header/>
    <soapenv:Body>
        <adin:createData>
            <adin:ModelCRUDRequest>
                <adin:ModelCRUD>
                    <adin:serviceType>API-CPOINT</adin:serviceType>
                    <adin:TableName>XX_TransTracking</adin:TableName>
                    <adin:RecordID>0</adin:RecordID>
                    <adin:Action>Create</adin:Action>
                    <adin:DataRow>'
            . $fieldsXml . '
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
        </adin:createData>
    </soapenv:Body>
</soapenv:Envelope>';

        // Kirim request SOAP
        return $this->sendRequest($request);
    }
}
