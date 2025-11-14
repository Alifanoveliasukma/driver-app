<?php
namespace App\Http\Modules\PlannerModules\Services;

class ProfileService
{
    // Tambahkan metode layanan terkait profil planner di sini

    public static function checkSession()
    {
        if (!session('is_login')) {
            return [
                'success' => false,
            ];
        } else {
            return [
                'success' => true,
                'data' => [
                    'title' => 'Profil Planner',
                    'name' => session('name'),
                    'username' => session('username'),
                    'roleid' => session('roleid'),
                    'orgid' => session('orgid'),
                ]
            ];
        }
    }
}