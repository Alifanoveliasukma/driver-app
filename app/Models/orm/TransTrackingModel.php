<?php

namespace App\Models\orm;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransTrackingModel extends Model
{
    use HasFactory;
    protected $table = "mzl.xx_transtracking ";
    protected $hidden = [
        
    ];
    public function allTransTracking(Builder $query){
        return $query->where('isactive', 'Y');
    }
}
