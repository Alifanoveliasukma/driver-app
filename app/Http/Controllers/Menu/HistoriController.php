<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HistoriController extends Controller
{
    public function histori()
    {
        return view('menu.histori.index');
    }
}
