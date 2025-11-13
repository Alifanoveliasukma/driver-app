<?php

namespace App\Models\orm;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverModel extends Model
{
    use HasFactory;
    protected $table = 'mzl.xm_driver';
    protected $hidden = [
        
    ];
    public function allDriver(Builder $query){
        return $query->where('isactive', 'Y');
    }
}
