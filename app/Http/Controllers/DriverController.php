<?php

namespace App\Http\Controllers;

use App\Services\UserApi;
use App\Services\DriverApi;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
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

    public function index(Request $request)
    {
        $perPage = 10;
        $cacheKey = 'all_active_drivers';
        $cacheTime = 60 * 60; // 60 menit

        try {
            // 1. Ambil Data dari Database atau Cache (semua data)
            $allDriverData = Cache::remember($cacheKey, $cacheTime, function () {
                // Query hanya akan dieksekusi jika data tidak ada di cache
                return DB::table('mzl.xm_driver as d')
                    ->select(
                        'd.xm_driver_id as id',
                        'd.value as nip',
                        'd.name as nama_lengkap',
                        'd.driverstatus',
                        'd.xm_fleet_id',
                        'bp.c_bpartner_id',
                        'bp.value as bp_value'
                    )
                    ->leftJoin('mzl.c_bpartner as bp', 'bp.c_bpartner_id', '=', 'd.c_bpartner_id')
                    ->where('d.isactive', 'Y')
                    ->get(); // Mengambil data sebagai Collection
            });

            $collection = $allDriverData;
            $search = $request->get('search');

            // 2. Terapkan Filter Pencarian (jika ada) pada Collection
            if ($search) {
                $search = strtolower($search);
                $collection = $collection->filter(function ($item) use ($search) {
                    // Cari kecocokan di NIP, Nama Lengkap, atau Status
                    return str_contains(strtolower($item->nip ?? ''), $search) || 
                           str_contains(strtolower($item->nama_lengkap ?? ''), $search) ||
                           str_contains(strtolower($item->driverstatus ?? ''), $search);
                })->values(); // Reset kunci setelah filtering
            }

            // 3. Pagination Manual pada Collection
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            
            // Hitung data yang akan ditampilkan di halaman ini
            $pagedData = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

            // Buat instance LengthAwarePaginator
            $driverData = new LengthAwarePaginator(
                $pagedData,
                $collection->count(), // Total item setelah filter
                $perPage,
                $currentPage,
                // Pastikan path dan query string (termasuk 'search') diteruskan
                ['path' => $request->url(), 'query' => $request->query()] 
            );

            // 4. Kirim data ke view
            return view('planner.driver.index', compact('driverData', 'search'));

        } catch (\Exception $e) {
            // Jika ada masalah koneksi atau query DB
            return view('planner.driver.index', [
                'driverData' => new LengthAwarePaginator(collect([]), 0, $perPage, 1),
                'error' => 'Gagal memuat data Driver dari Database: ' . $e->getMessage()
            ]);
        }
    }

    // public function index()
    // {
    //     try {
    //         $driverData = DB::table('mzl.xm_driver as d')
    //             ->select(
    //                 'd.xm_driver_id as id',
    //                 'd.value as nip',
    //                 'd.name as nama_lengkap',
    //                 'd.driverstatus',
    //                 'd.xm_fleet_id',
    //                 'bp.c_bpartner_id',
    //                 'bp.value as bp_value'
    //             )
    //             ->leftJoin('mzl.c_bpartner as bp', 'bp.c_bpartner_id', '=', 'd.c_bpartner_id')
    //             ->where('d.isactive', 'Y')
    //             ->get();
            
    //         // dd($driverData);
    //         // Kirim data ke view
    //         return view('planner.driver.index', compact('driverData'));

    //     } catch (\Exception $e) {
    //         // Jika ada masalah koneksi atau query DB
    //         return view('planner.driver.index', [
    //             'driverData' => collect(), // Kirim koleksi kosong
    //             'error' => 'Gagal memuat data Driver dari Database: ' . $e->getMessage()
    //         ]);
    //     }
    // }

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
            'is_full_bp_access' => 'required|in:Y,N',
            'is_login_user' => 'required|in:Y,N',

            'driver_status' => 'required|string|max:25',
            'xm_fleet_id' => 'nullable|integer',
            'krani_id' => 'nullable|integer',
            'account_no' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        $user_data = $request->only(['user_value', 'user_name', 'user_password', 'is_full_bp_access', 'is_login_user']);
        $driver_data = $request->only(['driver_status', 'xm_fleet_id', 'krani_id', 'account_no', 'account_name', 'note']);

        $debug_data = []; // Inisialisasi variabel debug
        $userId = null; // Inisialisasi userId

        try {

             // --- 1. Buat Driver Baru (Menangani Array Response) ---
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

            $user_data['c_bpartner_id'] = DB::table('mzl.xm_driver')->select('c_bpartner_id')
                ->where('xm_driver_id', $driverRecordId)
                ->value('c_bpartner_id');
            
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