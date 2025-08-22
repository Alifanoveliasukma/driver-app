<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Mtownsend\XmlToArray\XmlToArray;

class AuthService
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
            return ['error' => 'The service is temporarily unavailable. Please try again later.'];
        }

        return XmlToArray::convert($response);
    }

    public function getRoles($userId)
    {
        return DB::table('mzl.ad_user_roles as ur')
            ->select('ur.ad_role_id as id', 'r.name')
            ->leftJoin('mzl.ad_role as r', 'r.ad_role_id', '=', 'ur.ad_role_id')
            ->where('ur.ad_user_id', $userId)
            ->whereIn('r.name', ['OPS MRO', 'INVOICE ADMIN', 'Driver'])
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
