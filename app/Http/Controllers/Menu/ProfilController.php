<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Services\DriverApi;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    protected $driver;
    public function __construct(DriverApi $driver)
    {
        $this->driver = $driver;
        $this->middleware('checklogin');
    }
    public function profile_driver()
    {
        if (!session()->has('username')) {
            return redirect()->route('login');
        }
        $c_bpartner_id = session('c_bpartner_id');
        $driver = $this->driver->getDriver($c_bpartner_id);
        $fields = data_get($driver, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow.field', []);
        if (isset($fields['@attributes']))
            $fields = [$fields];
        $mappedDriver = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column'], $attr['lval'])) {
                $mappedDriver[$attr['column']] = $attr['lval'];
            }
        }
        return view('menu.profil.index', [
            'data' => [
                'driverId' => $mappedDriver['XM_Driver_ID'] ?? null,
                'name' => $mappedDriver['Name'] ?? null,
                'accountNo' => $mappedDriver['AccountNo'] ?? null,
                'account' => $mappedDriver['Account'] ?? null,
                'note' => $mappedDriver['Note'] ?? null,
                'value' => $mappedDriver['Value'] ?? null,
                'fleetId' => $mappedDriver['XM_Fleet_ID'] ?? null,
                'kraniId' => $mappedDriver['Krani_ID'] ?? null,
            ]
        ]);
    }

    public function profile_planner()
    {
        if (!session('is_login')) {
            return redirect()->route('login');
        }

        $data = [
            'title' => 'Profil Planner',
            'name' => session('name'),
            'username' => session('username'),
            'roleid' => session('roleid'),
            'orgid' => session('orgid'),
        ];

        return view('planner.profile.index', $data);
    }
}
