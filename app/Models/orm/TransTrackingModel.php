<?php

namespace App\Models\orm;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TransTrackingModel extends Model
{
    use HasFactory;
    protected $table = "mzl.xx_transtracking";
    protected $hidden = [
        
    ];

    public function fleet():HasOne{
        return $this->hasOne(FleetModel::class,"xm_fleet_id","xm_fleet_id");
    }
    public function bPartner():HasOne{
        return $this->hasOne(BPartnerModel::class,"c_bpartner_id","c_bpartner_id");
    }
}
