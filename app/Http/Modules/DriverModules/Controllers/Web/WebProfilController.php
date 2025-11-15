<?php

namespace App\Http\Modules\DriverModules\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Modules\DriverModules\Services\ProfilService;
use App\Services\DriverApi;

class WebProfilController extends Controller
{
    protected $driver;

    public function __construct(DriverApi $driver)
    {
        $this->driver = $driver;
        $this->middleware('checklogin');
    }


    public function profile()
    {
        if (!session()->has('username')) {
            return redirect()->route('login');
        }

        $c_bpartner_id = session('c_bpartner_id');

        $data = ProfilService::getDriverProfile($c_bpartner_id, $this->driver);

        return view('menu.profil.index', compact('data'));
    }
}
