<?php

namespace App\Models\orm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class TransOrderModel extends Model
{
    use HasFactory;
    protected $table = 'mzl.xx_transorder';
    protected $hidden = [

    ];

    public function fleet(): HasOne
    {
        return $this->hasOne(FleetModel::class, "xm_fleet_id", "xm_fleet_id");
    }
    public function bPartner(): BelongsTo
    {
        return $this->belongsTo(BPartnerModel::class, 'customer_id', 'c_bpartner_id');
    }

    public static function getTransportSalesPaginated($perPage = 10,$search = null)
    {
        $transportSales= self::with([
            'fleet:xm_fleet_id,value,name',

            'bPartner:c_bpartner_id,name'
        ])->where([
                    ['isactive', '=', 'Y'],
                    ['isvoid', '=', 'N']
                ])->select(
                "xx_transorder_id",
                'ponumber',
                'status',
                'route',
                'eta',
                'etd',
                'xm_fleet_id',
                'customer_id',
                'areatype',
                'value'
            )->orderBy('created', 'desc');

            if ($search) {
                $transportSales = $transportSales->where([
                    ['ponumber','LIKE','%'.$search.'%'],
                    ['status','LIKE','%'.$search.'%'],
                    ['value','LIKE','%'.$search.'%'],
                    ['areatype','LIKE','%'.$search.'%'],
                ],'or')->orWhereHas('fleet', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })->orWhereHas('bPartner', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                });
            }
            return $transportSales->paginate($perPage);
    }

    public static function getTransOrderWithCustomerAddress($transOrderId)
    {
        return DB::table('mzl.xx_transorder as t')
            ->select(
                't.xx_transorder_id',
                't.value',
                't.route',
                't.status',
                't.drivername',
                't.vendorcar',
                't.loaddate',
                't.loaddatestart',
                't.unloaddate',
                't.unloaddatestart',
                't.outloaddate',
                't.outunloaddate',
                'bp.name as customer_name',
                'loc.address1 as customer_address',
                'loc.city',
                'loc.postal',
                'from_point.name as pickup_address',
                'to_point.name as delivery_address',
                DB::raw("to_char(t.loaddate, 'DD Mon YYYY HH24:MI') as pickup_time"),
                DB::raw("to_char(t.loaddatestart, 'DD Mon YYYY HH24:MI') as pickup_start"),
                DB::raw("to_char(t.unloaddate, 'DD Mon YYYY HH24:MI') as unload_time"),
                DB::raw("to_char(t.unloaddatestart, 'DD Mon YYYY HH24:MI') as unload_start")
            )
            ->join('mzl.c_bpartner as bp', 'bp.c_bpartner_id', '=', 't.customer_id')
            ->leftJoin('mzl.c_bpartner_location as bpl', 'bpl.c_bpartner_id', '=', 'bp.c_bpartner_id')
            ->leftJoin('mzl.c_location as loc', 'loc.c_location_id', '=', 'bpl.c_location_id')
            ->leftJoin('mzl.xx_transroute as tr', 'tr.xx_transorder_id', '=', 't.xx_transorder_id')
            ->leftJoin('mzl.xm_point as from_point', 'from_point.xm_point_id', '=', 'tr.from_id')
            ->leftJoin('mzl.xm_point as to_point', 'to_point.xm_point_id', '=', 'tr.to_id')
            ->where('t.xx_transorder_id', $transOrderId)
            ->first();
    }

    /**
     * Get order list for driver (replace SOAP getOrderList)
     */
    public static function getOrderListByDriver($driverId, $isComplete = 'N')
    {
        return DB::table('mzl.xx_transorder as t')
            ->select(
                't.xx_transorder_id',
                't.value',
                't.ponumber',
                't.status',
                't.route',
                't.customer_id',
                't.xm_driver_id',
                't.xm_fleet_id',
                't.eta',
                't.etd',
                't.iscomplete',
                't.isvoid',
                't.issend',
                'bp.name as customer_name'
            )
            ->leftJoin('mzl.c_bpartner as bp', 'bp.c_bpartner_id', '=', 't.customer_id')
            ->where('t.xm_driver_id', $driverId)
            ->where('t.iscomplete', $isComplete)
            ->where('t.isactive', 'Y')
            ->orderBy('t.etd', 'desc')
            ->get();
    }

    /**
     * Get order detail by ID (replace SOAP getOrderDetail)
     */
    public static function getOrderDetailById($orderId)
    {
        return DB::table('mzl.xx_transorder as t')
            ->select(
                't.xx_transorder_id',
                't.status',
                't.customer_id',
                't.xm_driver_id',
                't.xm_fleet_id',
                'bp.name as customer_name',
                'd.name as driver_name',
                'f.name as fleet_name'
            )
            ->leftJoin('mzl.c_bpartner as bp', 'bp.c_bpartner_id', '=', 't.customer_id')
            ->leftJoin('mzl.xm_driver as d', 'd.xm_driver_id', '=', 't.xm_driver_id')
            ->leftJoin('mzl.xm_fleet as f', 'f.xm_fleet_id', '=', 't.xm_fleet_id')
            ->where('t.xx_transorder_id', $orderId)
            ->first();
    }
}
