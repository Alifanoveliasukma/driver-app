<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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
    public function createStepOne()
    {
        return view('planner.driver.create-step-one');
    }

    /**
     * Step 1 POST: Simpan ke session.
     */
    public function createStepOnePost(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'search_key' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        // Simpan data ke session
        $driverData = Session::get('driver', []);
        $driverData = array_merge($driverData, $validated);
        Session::put('driver', $driverData);

        return redirect()->route('driver.create.step.two');
    }

    /**
     * Step 2: Form kendaraan / krani / rekening.
     */
    public function createStepTwo()
    {
        return view('planner.driver.create-step-two');
    }

    /**
     * Step 2 POST: Simpan ke session.
     */
    public function createStepTwoPost(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|string',
        ]);

        $driverData = Session::get('driver', []);
        $driverData = array_merge($driverData, $validated);
        Session::put('driver', $driverData);

        return redirect()->route('driver.create.step.three');
    }

    /**
     * Step 3: Form final untuk org & client.
     */
    public function createStepThree()
    {
        return view('planner.driver.create-step-three');
    }

    /**
     * Step 3 POST: Simpan ke database.
     */
    public function createStepThreePost(Request $request)
    {
        $validated = $request->validate([
            'org_id' => 'required|string',
        ]);

        $driverData = Session::get('driver', []);
        $driverData = array_merge($driverData, $validated);
        Session::put('driver', $driverData);

        return redirect()->route('driver.create.step.four');
    }

    public function createStepFour()
    {
        return view('planner.driver.create-step-four');
    }

    public function createStepFourPost(Request $request)
    {
        $validated = $request->validate([
            'cashbox_id' => 'required|string',
        ]);

        $driverData = Session::get('driver', []);
        $driverData = array_merge($driverData, $validated);
        Session::put('driver', $driverData);

        return redirect()->route('driver.create.step.five');
    }

    public function createStepFive()
    {
        return view('planner.driver.create-step-five');
    }

    public function createStepFivePost(Request $request)
    {
        $validated = $request->validate([ // BENAR! menggunakan ->
            'bank_account_id' => 'required|string',
        ]);

        $driverData = Session::get('driver', []);
        $driverData = array_merge($driverData, $validated);
        Session::put('driver', $driverData);

        return redirect()->route('driver.create.step.six');
    }

    public function createStepSix()
    {
        return view('planner.driver.create-step-six');
    }

    public function createStepSixPost(Request $request)
    {
        $validated = $request->validate([
            'bp_search_key' => 'nullable|string|max:255',
            'bp_name' => 'required|string|max:255',
            'credit_status' => 'required|string',
            'business_partner_group' => 'required|string',
        ]);

        $driverData = Session::get('driver', []);
        $driverData = array_merge($driverData, $validated);
        Session::put('driver', $driverData);

        return redirect()->route('driver.create.step.seven');
    }

    public function createStepSeven()
    {
        // Data dummy users
        $users = [
            (object)['id' => 1, 'name' => 'Budi Santoso', 'email' => 'budi@example.com', 'phone' => '081234567890'],
            (object)['id' => 2, 'name' => 'Ahmad Wijaya', 'email' => 'ahmad@example.com', 'phone' => '081234567891'],
            (object)['id' => 3, 'name' => 'Siti Rahayu', 'email' => 'siti@example.com', 'phone' => '081234567892'],
            (object)['id' => 4, 'name' => 'Joko Prasetyo', 'email' => 'joko@example.com', 'phone' => '081234567893'],
            (object)['id' => 5, 'name' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'phone' => '081234567894'],
        ];

        return view('planner.driver.create-step-seven', compact('users'));
    }

    public function createStepSevenPost(Request $request)
    {
        $validated = $request->validate([
            'user_contact_id' => 'required|string',
            'customer_only' => 'nullable|boolean',
        ]);

        $driverData = Session::get('driver', []);
        $driverData = array_merge($driverData, [
            'user_contact_id' => $validated['user_contact_id'],
            'customer_only' => $request->has('customer_only') ? true : false
        ]);
        Session::put('driver', $driverData);

        return redirect()->route('driver.create.step.eight');
    }

    // Step 8: Detail Informasi Driver (Final Step)
    public function createStepEight()
    {
        return view('planner.driver.create-step-eight');
    }

    public function createStepEightPost(Request $request)
    {
        $validated = $request->validate([
            'driver_name' => 'required|string|max:255',
            'driver_status' => 'required|string',
            'fleet_id' => 'required|string',
            'krani' => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:255',
            'account' => 'nullable|string|max:255',
        ]);

        // Ambil semua data dari session
        $driverData = Session::get('driver', []);
        $finalData = array_merge($driverData, $validated);

        // Di sini biasanya akan menyimpan ke database
        // Untuk dummy, kita tampilkan saja datanya
        
        // Clear session setelah proses selesai
        Session::forget('driver');

        // Redirect ke halaman sukses atau summary
        return redirect()->route('driver.index')
                         ->with('success', 'Driver berhasil dibuat!')
                         ->with('driver_data', $finalData);
    }

    public function resetForm()
    {
        Session::forget('driver');
        return redirect()->route('driver.create.step.one');
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
