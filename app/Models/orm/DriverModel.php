<?php

namespace App\Models\orm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class DriverModel extends Model
{
    use HasFactory;
    protected $table = 'mzl.xm_driver';
    protected $hidden = [

    ];
    public static function getAllDriverName()
    {
        return DB::table('mzl.xm_driver as d')
                    ->select(
                        'd.xm_driver_id as id',
                        'd.value as nip',
                        'd.name as nama_lengkap',
                        'd.driverstatus',
                        'd.xm_fleet_id',
                        'd.accountno',
                        'd.accountname',
                        'bp.c_bpartner_id',
                        'bp.value as bp_value',
                        'bp.name as bp_name',
                        'f.name as fleet_name' 
                    )
                    ->leftJoin('mzl.c_bpartner as bp', 'bp.c_bpartner_id', '=', 'd.c_bpartner_id')
                    ->leftJoin('mzl.xm_fleet as f', 'f.xm_fleet_id', '=', 'd.xm_fleet_id') 
                    ->where('d.isactive', 'Y')
                    ->get();
    }
    public static function getBPartnerIdByDriverId($driverId)
    {
        return self::select('c_bpartner_id')
                ->where('xm_driver_id', $driverId)
                ->value('c_bpartner_id');
    }

    public static function getDriverIdByBPartnerId($bPartnerId)
    {
        return self::select('xm_driver_id')
                ->where('c_bpartner_id', $bPartnerId)
                ->value('xm_driver_id');
    }
}
