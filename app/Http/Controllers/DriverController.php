<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    /**
     * Menampilkan daftar driver.
     */
    public function index()
    {
        $drivers = DB::table('mzl.xm_driver')->limit(100)->get();

        return view('planner.driver.index', compact('drivers'));
    }

    /**
     * Step 1: Form data dasar driver.
     */
    public function createStepOne(Request $request)
    {
        $driver = $request->session()->get('driver');
        return view('planner.driver.create-step-one', compact('driver'));
    }

    /**
     * Step 1 POST: Simpan ke session.
     */
    public function postCreateStepOne(Request $request)
    {
        $validatedData = $request->validate([
            'search_key' => 'nullable|string|max:100',
            'name' => 'required|string|max:150',
            'c_bpartner_id' => 'nullable|string|max:50',
            'driverstatus' => 'required|string|max:50',
        ]);

        $driver = $request->session()->get('driver', []);
        $driver = array_merge($driver, $validatedData);

        $request->session()->put('driver', $driver);

        return redirect()->route('driver.create.step.two');
    }

    /**
     * Step 2: Form kendaraan / krani / rekening.
     */
    public function createStepTwo(Request $request)
    {
        $driver = $request->session()->get('driver');
        return view('planner.driver.create-step-two', compact('driver'));
    }

    /**
     * Step 2 POST: Simpan ke session.
     */
    public function postCreateStepTwo(Request $request)
    {
        $validatedData = $request->validate([
            'krani_id' => 'nullable|string|max:50',
            'account' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:255',
        ]);

        $driver = $request->session()->get('driver', []);
        $driver = array_merge($driver, $validatedData);

        $request->session()->put('driver', $driver);

        return redirect()->route('driver.create.step.three');
    }

    /**
     * Step 3: Form final untuk org & client.
     */
    public function createStepThree(Request $request)
    {
        $driver = $request->session()->get('driver');
        return view('planner.driver.create-step-three', compact('driver'));
    }

    /**
     * Step 3 POST: Simpan ke database.
     */
    public function postCreateStepThree(Request $request)
    {
        $validatedData = $request->validate([
            'ad_client_id' => 'required|string|max:50',
            'ad_org_id' => 'required|string|max:50',
        ]);

        $driverData = array_merge(
            $request->session()->get('driver', []),
            $validatedData
        );

        // Insert ke DB sementara
        DB::table('mzl.xm_driver')->insert([
            'ad_client_id'   => $driverData['ad_client_id'] ?? null,
            'ad_org_id'      => $driverData['ad_org_id'] ?? null,
            'search_key'     => $driverData['search_key'] ?? null,
            'name'           => $driverData['name'] ?? null,
            'c_bpartner_id'  => $driverData['c_bpartner_id'] ?? null,
            'driverstatus'   => $driverData['driverstatus'] ?? null,
            'krani_id'       => $driverData['krani_id'] ?? null,
            'account'        => $driverData['account'] ?? null,
            'note'           => $driverData['note'] ?? null,
        ]);

        // Bersihkan session
        $request->session()->forget('driver');

        return redirect()
            ->route('planner.driver.index')
            ->with('success', 'Driver baru berhasil ditambahkan ke database.');
    }

    public function detail($id)
    {
        $driver = DB::table('mzl.xm_driver')->where('xm_driver_id', $id)->first();
        if (!$driver) {
            abort(404, 'Data driver tidak ditemukan.');
        }

        return view('driver.detail', compact('driver'));
    }

    // EDIT DRIVER

    public function editStepOne($id = null)
{
    $driver = (object)[
        'xm_driver_id' => $id ?? 0,
        'name' => 'Dummy Driver',
        'driverstatus' => 'Active',
        'c_bpartner_id' => 'BP-001',
        'search_key' => 'DRV-001'
    ];

    return view('planner.driver.edit-step-one', compact('driver'));
}

public function updateStepOne(Request $request, $id = null)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'driverstatus' => 'required|string',
    ]);

    // Dummy update
    return redirect()->route('driver.edit.step.two')
                     ->with('success', 'Data dasar driver (dummy) diperbarui.');
}

public function editStepTwo($xm_driver_id = null)
{
    $driver = (object)[
        'xm_driver_id' => $xm_driver_id ?? 0,
        'krani_id' => 'KRN-008',
        'account' => 'BCA - 1234567890 a.n Ahmad Yusuf',
        'note' => 'Driver dummy untuk testing'
    ];

    return view('planner.driver.edit-step-two', compact('driver'));
}

public function updateStepTwo(Request $request, $id = null)
{
    return redirect()->route('driver.edit.step.three')
                     ->with('success', 'Informasi kendaraan & rekening (dummy) diperbarui.');
}

public function editStepThree($id = null)
{
    $driver = (object)[
        'xm_driver_id' => $id ?? 0,
        'ad_client_id' => 'CL-001',
        'ad_org_id' => 'ORG-001',
        'active' => 1
    ];

    return view('planner.driver.edit-step-three', compact('driver'));
}

public function updateStepThree(Request $request, $id = null)
{
    return redirect()->route('planner.driver.index')
                     ->with('success', 'Data driver (dummy) berhasil diperbarui.');
}

}
