<?php

namespace App\Http\Controllers;

use App\Services\UserApi;
use App\Services\DriverApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DriverController extends Controller
{
    protected $userApi;
    protected $driverApi;

    public function __construct(UserApi $userApi, DriverApi $driverApi)
    {
        $this->userApi = $userApi;
        $this->driverApi = $driverApi;
    }

    public function index()
    {
        return view('planner.driver.index');
    }

    public function createForm()
    {
        return view('planner.driver.create');
    }

    public function store(Request $request)
    {
        // --- Validasi ---
        $request->validate([
            'user_value' => 'required|string|max:255',
            'user_name' => 'required|string|max:255',
            'user_password' => 'required|string|min:6',
            'c_bpartner_id' => 'required|integer',
            'is_full_bp_access' => 'required|in:Y,N',
            'is_login_user' => 'required|in:Y,N',

            'driver_status' => 'required|string|max:25',
            'xm_fleet_id' => 'nullable|integer',
            'krani_id' => 'nullable|integer',
            'account_no' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        $user_data = $request->only(['user_value', 'user_name', 'user_password', 'c_bpartner_id', 'is_full_bp_access', 'is_login_user']);
        $driver_data = $request->only(['driver_status', 'xm_fleet_id', 'krani_id', 'account_no', 'account_name', 'note']);

        $debug_data = []; // Inisialisasi variabel debug
        $userId = null; // Inisialisasi userId

        try {
            
            $userResponse = $this->userApi->createUser($user_data);
    
            $recordIdPath = $userResponse['soap:Body']['ns1:createDataResponse']['StandardResponse']['@attributes']['RecordID'] ?? 'NOT_FOUND';
            
            if (!is_array($userResponse) || $recordIdPath === 'NOT_FOUND') {
    
                throw new \Exception('Gagal membuat user di sistem ERP atau RecordID tidak ditemukan. Respons: ' . print_r($userResponse, true));
            }
            
            $userId = $recordIdPath; 

            $debug_data['OUTPUT_API_CALLS']['CREATE_USER'] = [
                'REQUEST_DATA' => $user_data,
                'RESPONSE_RAW' => $userResponse,
                'CONTAINS_ERROR' => false,
                'RECORD_ID_MATCH' => $userId
            ];
            
            $roleId = 1000049; 
            $roleResponse = $this->userApi->addUserRole($userId, $roleId);

            $isRoleSuccess = isset($roleResponse['soap:Body']['ns1:addDataResponse']['StandardResponse']) || 
                            isset($roleResponse['soap:Body']['ns1:updateDataResponse']['StandardResponse']) || 
                            isset($roleResponse['soap:Body']['ns1:createDataResponse']['StandardResponse']); 

            $debug_data['OUTPUT_API_CALLS']['ADD_USER_ROLE'] = [
                'USER_ID' => $userId,
                'ROLE_ID' => $roleId,
                'RESPONSE_RAW' => $roleResponse,
                'CONTAINS_ERROR' => !$isRoleSuccess
            ];

            if (!$isRoleSuccess) {
                throw new \Exception('Gagal menambahkan role driver ke user. Respons API: ' . print_r($roleResponse, true));
            }

            $orgId = 1000005; 
            $roleResponse = $this->userApi->addUserOrg($userId, $orgId);

            $isRoleSuccess = isset($roleResponse['soap:Body']['ns1:addDataResponse']['StandardResponse']) || 
                            isset($roleResponse['soap:Body']['ns1:updateDataResponse']['StandardResponse']) || 
                            isset($roleResponse['soap:Body']['ns1:createDataResponse']['StandardResponse']); 

            $debug_data['OUTPUT_API_CALLS']['ADD_USER_ORG'] = [
                'USER_ID' => $userId,
                'ORG_ID' => $orgId,
                'RESPONSE_RAW' => $roleResponse,
                'CONTAINS_ERROR' => !$isRoleSuccess
            ];

            if (!$isRoleSuccess) {
                throw new \Exception('Gagal menambahkan role driver ke user. Respons API: ' . print_r($roleResponse, true));
            }

            // --- 3. Buat Driver Baru (Menangani Array Response) ---
            $driverResponse = $this->driverApi->createDriver(
                $user_data['user_value'], 
                $user_data['user_name'], 
                $driver_data
            );

            // Cek Keberhasilan & Ekstraksi RecordID Driver
            $driverRecordId = $driverResponse['soap:Body']['ns1:createDataResponse']['StandardResponse']['@attributes']['RecordID'] ?? 'NOT_FOUND';

            if (!is_array($driverResponse) || $driverRecordId === 'NOT_FOUND') {
                throw new \Exception('Gagal membuat data driver atau RecordID tidak ditemukan. Respons: ' . print_r($driverResponse, true));
            }
            
            $debug_data['OUTPUT_API_CALLS']['CREATE_DRIVER'] = [
                'DRIVER_DATA' => $driver_data,
                'RESPONSE_RAW' => $driverResponse,
                'CONTAINS_ERROR' => false,
                'RECORD_ID_MATCH' => $driverRecordId
            ];

            // Output debug data (jika diperlukan)
            dd($debug_data);
            
            return redirect()->route('driver.success')->with('success', '✅ User dan Driver berhasil dibuat!');

        } catch (\Exception $e) {
            // Jika ada error di salah satu langkah, log error dan kembalikan ke halaman sebelumnya
            \Log::error('Error create driver: ' . $e->getMessage(), ['debug_data' => $debug_data ?? []]);
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

}