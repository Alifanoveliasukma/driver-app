<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UjpController extends Controller
{
    public function ujp()
    {
        return view('menu.ujp.index');
    }
}
