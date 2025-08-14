<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UtamaController extends Controller
{
     public function index()
    {
        return view('menu.utama.konfirmasi-berangkat');
    }
}
