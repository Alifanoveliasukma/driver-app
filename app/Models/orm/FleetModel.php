<?php

namespace App\Models\orm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetModel extends Model
{
    use HasFactory;
    protected $table = "mzl.xm_fleet";
    protected $hidden = [

    ];
    public function allFleet(){
        return self::where('isactive', 'Y');
    }
    public static function getAllFleetName(){
        return self::select('xm_fleet_id', 'name as fleet_name')
            ->where('isactive', 'Y')
            ->orderBy('fleet_name', 'asc')
            ->get();
    }
}
