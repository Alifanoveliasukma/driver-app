<?php

namespace App\Http\Modules\AuthModules\Services;

use App\Http\Modules\AuthModules\Validator\AuthValidator;
use App\Http\Modules\AuthModules\Validator\LoginValidator;
use App\Models\orm\UserModel;
use App\Models\orm\WarehouseModel;
use App\Models\xml\AuthApi;

class WebAuthServices
{

    public static function loginProcess(LoginValidator $request, AuthApi $api)
    {
        $user = UserModel::findUserIdByUsername($request->input('username'));

        if (!$user) {
            session()->flash(
                'message',
                'Error logging in - no roles or user/pwd invalid for user sales'
            );
            return false;
        }
        session([
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'roles' => $api->getRoles($user->id),
            'orgs' => $api->getOrgs($user->id),
        ]);
        $data = [
            'title' => 'Log in',
            'user' => $user,
        ];
        return $data;
    }
    public static function authProcess(AuthValidator $request, AuthApi $api)
    {
        $username = session('username');
        $password = session('password');
        $roleid = (int) $request->input('role');
        $orgid = (int) $request->input('org');
        $whid = WarehouseModel::getWarehouseIdByOrgId($orgid);

        $user = $api->authUser($username, $password, $roleid, $orgid, $whid);
        if (isset($user['Error'])) {
            return [
                'success' => false,
                'message'=> is_array($user['Error']) ? json_encode($user['Error']) : $user['Error']
            ];
            
        }


        $hasDataSet = isset($user['DataSet']) || isset($user['soap:Body']['ns1:queryDataResponse']['WindowTabData']['DataSet']);

        if (!$hasDataSet) {
            $info = $user['soap:Body']['ns1:queryDataResponse']['WindowTabData']['Log']['Info'] ?? null;
            if ($info && isset($info['@attributes']['msgtext'])) {
                $message= $info['@attributes']['msgtext'];
            } else {
                $message= 'Error logging in - no roles or user/pwd invalid.';
            }
            return [
                'success' => false,
                'message'=> $message
            ];
            
        }

        $dataset = $user['DataSet']
            ?? ($user['soap:Body']['ns1:queryDataResponse']['WindowTabData']['DataSet'] ?? null);
        $success = ($user['Success']
            ?? ($user['soap:Body']['ns1:queryDataResponse']['WindowTabData']['Success'] ?? null));
        $successOk = in_array(strtolower((string) $success), ['true', 'y', '1'], true) || $success === true;
        if (!$successOk) {
            return [
                'success' => false,
                'message'=> 'Login gagal: Success=false.' 
            ];
            
        }

        $dataRow = $dataset['DataRow'] ?? null;
        if (isset($dataRow['field'])) {
            $fields = $dataRow['field'];
        } else {
            return [
                'success' => false,
                'message'=> 'Login gagal: DataRow tidak ditemukan.'
            ];
        }

        if (isset($fields['@attributes'])) {
            $fields = [$fields];
        }

        $user_id = $fields[0]['@attributes']['lval'] ?? null;
        $name = $fields[1]['@attributes']['lval'] ?? null;
        $c_bpartner_id = $fields[2]['@attributes']['lval'] ?? null;
        $value = $fields[3]['@attributes']['lval'] ?? $username;


        session()->forget(['username', 'password', 'roleid', 'orgid', 'c_bpartner_id','roles','orgs']);
        $sessonObj=[
            'user_id' => $user_id,
            'name' => $name,
            'username' => $value,
            'c_bpartner_id' => $c_bpartner_id,
            'roleid' => (int) $roleid,
            'orgid' => (int) $orgid,
            'is_login' => true,
        ];
        session($sessonObj);

        return [
            'success' => true,
            'message'=> 'Login berhasil.',
            'data'=> $sessonObj
        ];
    }
    public static function LogOutService()
    {
        session()->forget(['user_id', 'name', 'username', 'password', 'roleid', 'is_login']);
    }   // Service methods would go here
}