<?php

namespace App\Http\Modules\AuthModules\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Modules\AuthModules\Services\WebAuthServices;
use App\Http\Modules\AuthModules\Validator\AuthValidator;
use App\Http\Modules\AuthModules\Validator\LoginValidator;
use App\Models\xml\AuthApi;

class WebAuthController extends Controller
{
    protected $auth;

    public function __construct(AuthApi $auth)
    {
        $this->auth = $auth;
    }

    public function showLogin(){
        return view("auth.login",['title' => 'Log in',]);
    }
    public function showAuth(){
        return view("auth.role");
    }

    public function processLogin(LoginValidator $request,AuthApi $api)
    {
        $data = WebAuthServices::loginProcess($request,$api);
        if(!$data){
            return redirect()->route('login');
        }
        return redirect()->route('auth')->with($data);
    }

    public function processAuth(AuthValidator $request,AuthApi $api)
    {
        $res = WebAuthServices::authProcess($request,$api);
        if(!$res['success']){
            return redirect()->route('login')->with('message',$res['message']);
        }
        if ((int) $res['data']['roleid'] === 1000049) {
            // Driver
            return redirect()->route('menu.list-order');
        } elseif ((int) $res['data']['roleid'] === 1000051) {
            // Planner Admin
            return redirect()->route('dashboard');
        }

        return redirect()->route('login')->with('error', 'Role tidak dikenali');
    }

  

    public function logout()
    {
        WebAuthServices::LogOutService();
        return redirect()->route('login');
    }
}
