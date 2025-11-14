<?php

namespace App\Http\Modules\PlannerModules\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebDashboardController extends Controller
{
    public function index()
    {
        // Menampilkan view dashboard planner
        return view('planner.dashboard');
    }
}
