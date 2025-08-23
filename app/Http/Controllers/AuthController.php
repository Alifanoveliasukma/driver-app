<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\AuthApi;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(AuthApi $auth)
    {
        $this->auth = $auth;
    }

    private function isLoginRedirect()
    {
        if (session('is_login') === true) {
            return redirect()->route('menu.konfirmasi-berangkat')->send();
        }
    }

    public function login(Request $request, AuthApi $api)
    {
        $this->isLoginRedirect();

        $step = $request->query('step');

        $data = [
            'title' => 'Log in',
        ];

        switch ($step) {
            case 'roleorg':
                if (!session()->has('username')) {
                    return redirect()->route('login');
                }

                session(['roleid' => null, 'orgid' => null]);

                $user = DB::table('mzl.ad_user')
                    ->select('ad_user_id as id')
                    ->where('value', session('username'))
                    ->first();

                if (!$user) {

                    session()->flash(
                        'message',
                        'Error logging in - no roles or user/pwd invalid for user sales'
                    );
                    return redirect()->route('login');
                }

                $data['user']  = $user;
                $data['roles'] = $api->getRoles($user->id);
                $data['orgs']  = $api->getOrgs($user->id);

                if (!$request->isMethod('post')) {
                    return view('auth.role', $data);
                }

                $request->validate([
                    'role' => 'required',
                    'org'  => 'required',
                ]);

                session([
                    'roleid' => $request->input('role'),
                    'orgid'  => $request->input('org'),
                ]);

                return redirect()->route('login', ['step' => 'authenticate']);

            case 'authenticate':
                return $this->_auth($api);

            default:

                session([
                    'username' => null,
                    'password' => null,
                    'roleid'   => null,
                    'orgid'    => null,
                ]);


                if (!$request->isMethod('post')) {
                    return view('auth.login', $data);
                }

                $request->validate([
                    'username' => 'required|string',
                    'password' => 'required|string',
                ]);


                session([
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                ]);

                return redirect()->route('login', ['step' => 'roleorg']);
        }
    }

    private function _auth(AuthApi $api)
    {
        $username = session('username');
        $password = session('password');
        $roleid   = (int) session('roleid');
        $orgid    = (int) session('orgid');
        $whid = DB::table('mzl.m_warehouse')
            ->where(['isactive' => 'Y', 'ad_org_id' => $orgid])
            ->value('m_warehouse_id');

        $user = $this->auth->authUser($username, $password, $roleid, $orgid, $whid);
        if (isset($user['Error'])) {
            session()->flash('message', is_array($user['Error']) ? json_encode($user['Error']) : $user['Error']);
            return redirect()->route('login');
        }


        $hasDataSet = isset($user['DataSet']) || isset($user['soap:Body']['ns1:queryDataResponse']['WindowTabData']['DataSet']);

        if (!$hasDataSet) {
            $info = $user['soap:Body']['ns1:queryDataResponse']['WindowTabData']['Log']['Info'] ?? null;
            if ($info && isset($info['@attributes']['msgtext'])) {
                session()->flash('message', $info['@attributes']['msgtext']);
            } else {
                session()->flash('message', 'Error logging in - no roles or user/pwd invalid.');
            }
            return redirect()->route('login');
        }

        $dataset = $user['DataSet']
            ?? ($user['soap:Body']['ns1:queryDataResponse']['WindowTabData']['DataSet'] ?? null);
        $success = ($user['Success']
            ?? ($user['soap:Body']['ns1:queryDataResponse']['WindowTabData']['Success'] ?? null));
        $successOk = in_array(strtolower((string)$success), ['true', 'y', '1'], true) || $success === true;
        if (!$successOk) {
            session()->flash('message', 'Login gagal: Success=false.');
            return redirect()->route('login');
        }

        $dataRow = $dataset['DataRow'] ?? null;
        if (isset($dataRow['field'])) {
            $fields = $dataRow['field'];
        } else {
            session()->flash('message', 'Login gagal: DataRow tidak ditemukan.');
            return redirect()->route('login');
        }

        if (isset($fields['@attributes'])) {
            $fields = [$fields];
        }
        
        $user_id = $fields[0]['@attributes']['lval'] ?? null;
        $name    = $fields[1]['@attributes']['lval'] ?? null;
        $c_bpartner_id   = $fields[2]['@attributes']['lval'] ?? null;
        $value   = $fields[3]['@attributes']['lval'] ?? $username;


        session()->forget(['username', 'password', 'roleid', 'orgid', 'c_bpartner_id']);

        session([
            'user_id'  => $user_id,
            'name'     => $name,
            'username' => $value,
            'c_bpartner_id' => $c_bpartner_id,
            'roleid'   => (int) $roleid,
            'orgid'    => (int) $orgid,
            'is_login' => true,
        ]);

        if ((int)$roleid === 1000049) {
            return redirect()->route('menu.konfirmasi-berangkat');
        }
        return redirect()->route('login')->with('error', 'Role tidak dikenali');
    }

    public function logout()
    {

        session()->forget(['user_id', 'name', 'username', 'password', 'roleid', 'is_login']);
        return redirect()->route('login');
    }
}
