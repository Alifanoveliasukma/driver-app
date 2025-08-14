<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Menu utama
class DashboardController extends Controller
{
    public function index()
    {
        return view('menu.utama.konfirmasi-berangkat');
    }
}
