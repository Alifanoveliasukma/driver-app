<?php

namespace App\Services;

class OrderNew extends BaseApi
{

public function OrderNew($driverId)
    {
        $request = '
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
            xmlns:adin="http://3e.pl/ADInterface"> 
            <soapenv:Header/> 
            <soapenv:Body> 
                <adin:queryData> 
                    <adin:ModelCRUDRequest> 
                        <adin:ModelCRUD> 
                        <adin:serviceType>API-GetOrderList</adin:serviceType> 
                        <adin:TableName>XX_TransOrder</adin:TableName> 
                         <adin:Filter>XM_Driver_ID=' . $driverId . '</adin:Filter>
                        <adin:Action>Read</adin:Action> 
                        <!--Optional:--> 
                        <adin:DataRow> 
                            <!--Zero or more repetitions:--> 
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
        </soapenv:Envelope> ';
        return $this->sendRequest($request);

    }
}