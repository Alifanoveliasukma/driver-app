<?php
namespace App\Http\Modules\PlannerModules\Services;

use App\Http\Modules\PlannerModules\Validator\CreateDriverValidator;
use App\Models\orm\DriverModel;
use App\Models\orm\FleetModel;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class DriverService
{
    // Tambahkan metode layanan terkait driver di sini
    public static function getDriver(Request $request, $cacheData, $perPage)
    {
        $search = $request->get('search');
        if ($request->has('clear_cache') && $request->get('clear_cache') === 'true') {
            Cache::forget($cacheData['cacheKey']);
        }
        
        try {
            $allDriverData = Cache::remember($cacheData['cacheKey'], $cacheData['cacheTime'], function () {
                return DriverModel::getAllDriverName();
            });
            // dd($allDriverData);
            $collection = $allDriverData;

            if ($search) {
                $search = strtolower($search);
                $collection = $collection->filter(function ($item) use ($search) {
                    return str_contains(strtolower($item->nip ?? ''), $search) ||
                        str_contains(strtolower($item->nama_lengkap ?? ''), $search) ||
                        str_contains(strtolower($item->driverstatus ?? ''), $search);
                })->values();
            }

            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $pagedData = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $driverData = new LengthAwarePaginator(
                $pagedData,
                $collection->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return [
                'success' => true,
                'data' => compact('driverData', 'search')
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'data' => new LengthAwarePaginator(collect([]), 0, $perPage, 1),
                'message' => 'Gagal memuat data Driver dari Database: ' . $e->getMessage()
            ];
        }
    }

    public static function showFormDriver()
    {
        $fleetData = FleetModel::getAllFleetName();
        return [
            'success' => true,
            'data' => $fleetData
        ];
    }

    public static function createUserDriver(CreateDriverValidator $request, $userApi, $driverApi)
    {
        $user_data = $request->only(['user_value', 'user_name', 'user_password']);
        $user_data['is_full_bp_access'] = 'Y';
        $user_data['is_login_user'] = 'Y';
        $driver_data = $request->only(['driver_status', 'xm_fleet_id', 'note', 'account_name', 'account_no']);

        $debug_data = []; 
        $userId = null; 

        try {
            $driverResponse = $driverApi->createDriver(
                $user_data['user_value'], 
                $user_data['user_name'], 
                $driver_data
            );

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

            $user_data['c_bpartner_id'] = (int) DriverModel::getBPartnerIdByDriverId($driverRecordId);
            
            $userResponse = $userApi->createUser($user_data);
            $recordIdPath = $userResponse['soap:Body']['ns1:createDataResponse']['StandardResponse']['@attributes']['RecordID'] ?? 'NOT_FOUND';
            
            if (!is_array($userResponse) || $recordIdPath === 'NOT_FOUND') {
                return [
                    'success' => false,
                    'message'=> 'Gagal membuat user di sistem ERP atau RecordID tidak ditemukan. Respons: ' . print_r($userResponse, true)
                ];
            }
            
            $userId = $recordIdPath; 

            $debug_data['OUTPUT_API_CALLS']['CREATE_USER'] = [
                'REQUEST_DATA' => $user_data,
                'RESPONSE_RAW' => $userResponse,
                'CONTAINS_ERROR' => false,
                'RECORD_ID_MATCH' => $userId
            ];
            
            $roleId = 1000049; 
            $roleResponse = $userApi->addUserRole($userId, $roleId);

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
                return [
                    'success'=> false,
                    'message'=> 'Gagal menambahkan role driver ke user. Respons API: ' . print_r($roleResponse, true)
                ];
            }

            $orgId = 1000005; 
            $roleResponse = $userApi->addUserOrg($userId, $orgId);

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
                return [
                    'success' => false,
                    'message'=> 'Gagal menambahkan role driver ke user. Respons API: ' . print_r($roleResponse, true)
                ];
            }

            return [
                'success' => true,
                'message' => '✅ User dan Driver berhasil dibuat!'
            ];

        } catch (\Exception $e) {
            \Log::error('Error create driver: ' . $e->getMessage(), ['debug_data' => $debug_data ?? []]);
            return [
                'success' => false,
                'message' => 'Gagal membuat User dan Driver: ' . $e->getMessage()
            ];
        }
    }
}