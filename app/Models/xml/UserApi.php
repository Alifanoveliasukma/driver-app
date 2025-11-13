<?php

namespace App\Models\xml;

class UserApi extends BaseApi
{

    // MODIFIKASI: Sekarang menerima semua data user
    public function createUser($data) 
    {
        // Ambil data dari array $data yang dilewatkan dari Controller
        $value = htmlspecialchars($data['user_value']);
        $name = htmlspecialchars($data['user_name']);
        $password = htmlspecialchars($data['user_password']);
        $c_bpartner_id = htmlspecialchars($data['c_bpartner_id']);
        $is_full_bp_access = htmlspecialchars($data['is_full_bp_access']);
        $is_login_user = htmlspecialchars($data['is_login_user']);
        
        $request = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:adin="http://3e.pl/ADInterface">
        <soapenv:Header/>
        <soapenv:Body>
            <adin:createData>
                <adin:ModelCRUDRequest>
                    <adin:ModelCRUD>
                    <adin:serviceType>API_CreateUser</adin:serviceType>
                    <adin:TableName>AD_User</adin:TableName>
                    <adin:RecordID>0</adin:RecordID>
                    <adin:Action>Create</adin:Action>
                    <adin:DataRow>
                        <adin:field column="Value"><adin:val>'.$value.'</adin:val></adin:field>
                        <adin:field column="Name"><adin:val>'.$name.'</adin:val></adin:field>
                        <adin:field column="Password"><adin:val>'.$password.'</adin:val></adin:field>
                        <adin:field column="C_BPartner_ID"><adin:val>'.$c_bpartner_id.'</adin:val></adin:field>
                        <adin:field column="IsFullBPAccess"><adin:val>'.$is_full_bp_access.'</adin:val></adin:field>
                        <adin:field column="IsLoginUser"><adin:val>'.$is_login_user.'</adin:val></adin:field>
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

        return $this->sendRequest($request); 
    }

    // MODIFIKASI: Hapus $roleId dan pakai konstanta
    public function addUserRole($userId, $roleId)
    {
        $request = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:adin="http://3e.pl/ADInterface">
        <soapenv:Header/>
        <soapenv:Body>
            <adin:createData>
                <adin:ModelCRUDRequest>
                    <adin:ModelCRUD>
                    <adin:serviceType>API_AddUserRole</adin:serviceType>
                    <adin:TableName>AD_User_Roles</adin:TableName>
                    <adin:RecordID>0</adin:RecordID>
                    <adin:Action>Create</adin:Action>
                    <adin:DataRow>
                        <adin:field column="AD_User_ID">
                            <adin:val>' . htmlspecialchars($userId) . '</adin:val>
                        </adin:field>
                        <adin:field column="AD_Role_ID">
                            <adin:val>' . htmlspecialchars($roleId) . '</adin:val>
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
            </adin:createData>
        </soapenv:Body>
        </soapenv:Envelope>';

        return $this->sendRequest($request);
    }
    public function addUserOrg($userId, $orgId)
    {
        $request = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:adin="http://3e.pl/ADInterface">
        <soapenv:Header/>
        <soapenv:Body>
            <adin:createData>
                <adin:ModelCRUDRequest>
                    <adin:ModelCRUD>
                    <adin:serviceType>API-AddOrgAccess</adin:serviceType>
                    <adin:TableName>AD_User_OrgAccess</adin:TableName>
                    <adin:RecordID>0</adin:RecordID>
                    <adin:Action>Create</adin:Action>
                    <adin:DataRow>
                        <adin:field column="AD_User_ID">
                            <adin:val>' . htmlspecialchars($userId) . '</adin:val>
                        </adin:field>
                        <adin:field column="AD_Org_ID">
                            <adin:val>' . htmlspecialchars($orgId) . '</adin:val>
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
            </adin:createData>
        </soapenv:Body>
        </soapenv:Envelope>';

        return $this->sendRequest($request);
    }
    
}