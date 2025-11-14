<?php

namespace App\Http\Modules\PlannerModules\Services;

use App\Models\orm\TransOrderModel;
use App\Models\orm\TransTrackingModel;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TransTrackingService{
    public static function getAllTransTracking(Request $request){
        $search = $request->get('search');
        $transTracking = TransOrderModel::getTransportSalesPaginated(10,$search);
        return [
            'success' => true,
            'data'    => $transTracking
        ];
    }
}