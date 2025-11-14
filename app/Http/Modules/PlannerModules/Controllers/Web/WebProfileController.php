<?php

namespace App\Http\Modules\PlannerModules\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Modules\PlannerModules\Services\ProfileService;

class WebProfileController extends Controller
{
    public function index()
    {
        $data = ProfileService::checkSession();
        if (!$data['success']) {
            return redirect()->route('login');
        } else {
            return view('planner.profile.index', $data['data']);
        }
    }
}
