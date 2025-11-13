<?php

namespace App\Models\xml;


class DriverApi extends BaseApi
{
    public function createDriver($value, $name, $data) 
    {
        $value = htmlspecialchars($value);
        $name = htmlspecialchars($name);
        $driver_status = htmlspecialchars($data['driver_status']);
        $xm_fleet_id = htmlspecialchars($data['xm_fleet_id'] ?? '');
        $krani_id = htmlspecialchars($data['krani_id'] ?? '');
        $account_no = htmlspecialchars($data['account_no'] ?? '');
        $account_name = htmlspecialchars($data['account_name'] ?? '');
        $note = htmlspecialchars($data['note'] ?? '');

        $request = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:adin="http://3e.pl/ADInterface">
        <soapenv:Header/>
        <soapenv:Body>
            <adin:createData>
                <adin:ModelCRUDRequest>
                    <adin:ModelCRUD>
                        <adin:serviceType>API-CreateDriver</adin:serviceType>
                        <adin:TableName>XM_Driver</adin:TableName>
                        <adin:RecordID>0</adin:RecordID>
                        <adin:Action>Create</adin:Action>
                        <adin:DataRow>
                            <adin:field column="Value"><adin:val>'.$value.'</adin:val></adin:field>
                            <adin:field column="Name"><adin:val>'.$name.'</adin:val></adin:field>
                            <adin:field column="DriverStatus"><adin:val>'.$driver_status.'</adin:val></adin:field>
                            <adin:field column="XM_Fleet_ID"><adin:val>'.$xm_fleet_id.'</adin:val></adin:field>
                            <adin:field column="Krani_ID"><adin:val>'.$krani_id.'</adin:val></adin:field>
                            <adin:field column="AccountNo"><adin:val>'.$account_no.'</adin:val></adin:field>
                            <adin:field column="Account"><adin:val>'.$account_name.'</adin:val></adin:field>
                            <adin:field column="Note"><adin:val>'.$note.'</adin:val></adin:field>
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

        return $this->sendRequest($request); // <--- DIPANGGIL DARI DALAM KELAS TURUNAN
    }
}


