<?php

namespace App\Http\Modules\DriverModules\Controllers\Web;

use App\Http\Controllers\Controller;

class WebUjpController extends Controller
{

    public function ujp()
    {
        return view('menu.ujp.index');
    }
}
