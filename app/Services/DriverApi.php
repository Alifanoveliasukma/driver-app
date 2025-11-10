<?php

namespace App\Services;



class DriverApi extends BaseApi
{
    public function getDriver($bpartnerId)
    {

        $request = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:adin="http://3e.pl/ADInterface">
           <soapenv:Header/>
           <soapenv:Body>
              <adin:queryData>
                 <adin:ModelCRUDRequest>
                    <adin:ModelCRUD>
                       <adin:serviceType>API-ReadDriver</adin:serviceType>
                       <adin:TableName>XM_Driver</adin:TableName>
                       <adin:Filter>C_BPartner_ID=' . $bpartnerId . '</adin:Filter>
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

    public function createDriver(array $driverFields): string
    {
        if (empty($driverFields['Value']) || empty($driverFields['Name'])) {
            throw new \Exception("Value and Name are required for Driver creation.");
        }

        $fields = array_merge([
            'Value' => '', 
            'Name' => '', 
            'DriverStatus' => 'Stand by', 
            'XM_Fleet_ID' => null, 
            'Krani_ID' => null, 
            'AccountNo' => null,
            'Account' => null,
            'Note' => null,
        ], $driverFields);

        $fieldsXml = '';
        foreach ($fields as $column => $val) {
            if (!empty($val) || (is_string($val) && strlen($val) > 0)) {
                $fieldsXml .= '
                        <adin:field column="' . htmlspecialchars($column) . '">
                            <adin:val>' . htmlspecialchars($val) . '</adin:val>
                        </adin:field>';
            }
        }

        $adLoginRequestXml = $this->getAdLoginRequest();

        $request = '
         <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
            xmlns:adin="http://3e.pl/ADInterface">
            <soapenv:Header/>
            <soapenv:Body>
               <adin:createData>
                     <adin:ModelCRUDRequest>
                        <adin:ModelCRUD>
                           <adin:serviceType>API-CreateDriver</adin:serviceType>
                           <adin:TableName>XM_Driver</adin:TableName>
                           <adin:RecordID>0</adin:RecordID>
                           <adin:Action>Create</adin:Action>
                           <adin:DataRow>'
                           . $fieldsXml . '
                           </adin:DataRow>
                        </adin:ModelCRUD>
                        <adin:ADLoginRequest>
                           ' . $adLoginRequestXml . '
                        </adin:ADLoginRequest>
                     </adin:ModelCRUDRequest>
               </adin:createData>
            </soapenv:Body>
         </soapenv:Envelope>';

        $soapResponse = $this->sendRequest($request);

        // PENTING: Mengekstrak RecordID (XM_Driver_ID) dari respons SOAP
        return $this->extractRecordIDFromResponse($soapResponse);
    }

}
