<?php

namespace App\Http\Modules\PlannerModules\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Modules\PlannerModules\Services\DriverService;
use App\Http\Modules\PlannerModules\Validator\CreateDriverValidator;
use App\Models\xml\UserApi;
use App\Models\xml\DriverApi;
use Illuminate\Http\Request;

class WebDriverController extends Controller
{
    protected $userApi;
    protected $driverApi;

    public function __construct(UserApi $userApi, DriverApi $driverApi)
    {
        $this->userApi = $userApi;
        $this->driverApi = $driverApi;
    }

    public function index(Request $request)
    {
        $perPage = 10;
        $cacheData = [
            'cacheTime' => 60 * 60,
            'cacheKey' => 'all_active_drivers',
        ];
        $res = DriverService::getDriver($request, $cacheData, $perPage);
        if (!$res['success']) {
            return view('planner.driver.index', [
                'driverData' => $res['data'],
                'error' => $res['message']
            ]);
        }
        return view('planner.driver.index', $res['data']);
    }

    public function createForm()
    {
        $fleets = DriverService::showFormDriver();
        if ($fleets['success']) {
            $fleets = $fleets['data'];
            return view('planner.driver.create', compact('fleets'));
        }
    }

    public function store(CreateDriverValidator $request)
    {
        $res = DriverService::createUserDriver($request, $this->userApi, $this->driverApi);
        if (!$res['success']) {
            return back()->with('error', $res['message'])->withInput();
        }
        return redirect()->route('driver.index')->with('success', $res['message']);
    }

}