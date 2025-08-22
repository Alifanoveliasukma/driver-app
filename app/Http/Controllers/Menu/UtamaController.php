<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Services\DriverApi;

use App\Services\AuthService;
use App\Services\DriverService;
use App\Services\OrderService;
use App\Services\OrderReadService;

class UtamaController extends Controller
{

    protected $driver;

    public function __construct(DriverApi $driver)
    {
        $this->driver = $driver;
    }

    public function index()
    {

        $driver = $this->driver->getDriver(1004164);
        dd($driver);

        return view('menu.utama.konfirmasi-berangkat');
    }
}
