<?php

namespace App\Services;

class UserService extends BaseApi
{

    // File: UserService.php

    public function createUser(string $value, string $name, string $password): string
    {
        $fields = [
            'Value' => $value,
            'Name' => $name,
            'Password' => $password,
            // Tambahkan kolom lain seperti C_BPartner_ID, IsLoginUser, dll., jika diperlukan
        ];

        $fieldsXml = '';
        foreach ($fields as $column => $val) {
            $fieldsXml .= '
                        <adin:field column="' . htmlspecialchars($column) . '">
                            <adin:val>' . htmlspecialchars($val) . '</adin:val>
                        </adin:field>';
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
                                <adin:serviceType>API_CreateUser</adin:serviceType>
                                <adin:TableName>AD_User</adin:TableName>
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

        return $this->extractRecordIDFromResponse($soapResponse); 
    }

    public function assignDriverRole(string $ad_user_id, string $ad_role_id = '1000049'): array
    {
        $fields = [
            'AD_User_ID' => $ad_user_id,
            'AD_Role_ID' => $ad_role_id,
        ];

        $fieldsXml = '';
        foreach ($fields as $column => $val) {
            $fieldsXml .= '
                        <adin:field column="' . htmlspecialchars($column) . '">
                            <adin:val>' . htmlspecialchars($val) . '</adin:val>
                        </adin:field>';
        }
        
        $adLoginRequestXml = $this->getCompleteAdLoginRequest(); 

        $request = '
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                xmlns:adin="http://3e.pl/ADInterface">
                <soapenv:Header/>
                <soapenv:Body>
                    <adin:createData>
                        <adin:ModelCRUDRequest>
                            <adin:ModelCRUD>
                                <adin:serviceType>API_AddUserRole</adin:serviceType>
                                <adin:TableName>AD_User_Roles</adin:TableName>
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

        return $this->sendRequest($request);
    }

}