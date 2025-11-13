<?php

namespace App\Models\orm;

use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransOrderModel extends Model
{
    use HasFactory;
    protected $table='mzl.xx_transorder';
    protected $hidden = [
        
    ];
    public function getTransportSales(Builder $query){
        return $query->where([["IsActive",'=','Y'],["IsVoid",'=','N']]);
    }
    public function getTransOrderWithCustomerAddress($transOrderId)
    {
        return DB::table('mzl.xx_transorder as t')
            ->select(
                't.xx_transorder_id',
                't.value',
                't.route',
                'bp.name as customer_name',
                'loc.address1 as customer_address',
                'loc.city',
                'loc.postal',
                'from_point.name as pickup_address',
                'to_point.name as delivery_address',
                DB::raw("to_char(t.loaddate, 'DD Mon YYYY HH24:MI') as pickup_time"),
                DB::raw("to_char(t.loaddatestart, 'DD Mon YYYY HH24:MI') as pickup_start"),
                DB::raw("to_char(t.unloaddate, 'DD Mon YYYY HH24:MI') as unload_time"),
                DB::raw("to_char(t.unloaddatestart, 'DD Mon YYYY HH24:MI') as unload_start"),
                't.drivername',
                't.vendorcar'
            )
            ->join('mzl.c_bpartner as bp', 'bp.c_bpartner_id', '=', 't.customer_id')
            ->leftJoin('mzl.c_bpartner_location as bpl', 'bpl.c_bpartner_id', '=', 'bp.c_bpartner_id')
            ->leftJoin('mzl.c_location as loc', 'loc.c_location_id', '=', 'bpl.c_location_id')
            ->leftJoin('mzl.xx_transroute as tr', 'tr.xx_transorder_id', '=', 't.xx_transorder_id') // perbaikan di sini
            ->leftJoin('mzl.xm_point as from_point', 'from_point.xm_point_id', '=', 'tr.from_id')   // perbaikan di sini
            ->leftJoin('mzl.xm_point as to_point', 'to_point.xm_point_id', '=', 'tr.to_id')         // perbaikan di sini
            ->where('t.xx_transorder_id', $transOrderId)
            ->first();
    }
}
