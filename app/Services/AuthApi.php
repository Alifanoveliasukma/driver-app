<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AuthApi extends BaseApi
{
    public function authUser($username, $password, $roleid, $orgid, $whid)
    {
        $request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:adin="http://3e.pl/ADInterface">
            <soapenv:Header/>
            <soapenv:Body>
                <adin:queryData>
                    <adin:ModelCRUDRequest>
                        <adin:ModelCRUD>
                            <adin:serviceType>API-LOGIN</adin:serviceType>
                            <adin:Filter>value = \'' . $username . '\'</adin:Filter>
                            <adin:DataRow>
                                <adin:field column="IsActive">
                                    <adin:val>Y</adin:val>
                                </adin:field>
                            </adin:DataRow>
                        </adin:ModelCRUD>
                        <adin:ADLoginRequest>
                            <adin:user>' . $username . '</adin:user>
                            <adin:pass>' . $password . '</adin:pass>
                            <adin:lang>192</adin:lang>
                            <adin:ClientID>1000002</adin:ClientID>
                            <adin:RoleID>' . $roleid . '</adin:RoleID>
                            <adin:OrgID>' . $orgid . '</adin:OrgID>
                            <adin:WarehouseID>' . $whid . '</adin:WarehouseID>
                        </adin:ADLoginRequest>
                    </adin:ModelCRUDRequest>
                </adin:queryData>
            </soapenv:Body>
        </soapenv:Envelope>';

        return $this->sendRequest($request);
    }

    public function getRoles($userId)
    {
        return DB::table('mzl.ad_user_roles as ur')
            ->select('ur.ad_role_id as id', 'r.name')
            ->leftJoin('mzl.ad_role as r', 'r.ad_role_id', '=', 'ur.ad_role_id')
            ->where('ur.ad_user_id', $userId)
            ->whereIn('r.name', [ 'INVOICE ADMIN', 'Driver', 'PLANNER ADMIN'])
            ->get();
    }

    public function getOrgs($userId)
    {
        return DB::table('mzl.ad_user_orgaccess as uo')
            ->select('uo.ad_org_id as id', 'o.name')
            ->leftJoin('mzl.ad_org as o', 'o.ad_org_id', '=', 'uo.ad_org_id')
            ->where('uo.ad_user_id', $userId)
            ->whereNotIn('o.name', ['*'])
            ->get();
    }
}
