<?php

namespace App\Models\orm;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetModel extends Model
{
    use HasFactory;
    protected $table = "mzl.xm_fleet";
    protected $hidden = [

    ];
    public function allFleet(Builder $query){
        return $query->where('isactive', 'Y');
    }
}
