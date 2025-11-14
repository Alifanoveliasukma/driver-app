<?php

namespace App\Models\orm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseModel extends Model
{
    use HasFactory;
    protected $table = "mzl.m_warehouse";
    protected $hidden = [];

    public static function getWarehouseIdByOrgId($orgId)
    {
        return self::select('m_warehouse_id')
            ->where(['isactive' => 'Y', 'ad_org_id' => $orgId])
            ->first();
    }
}
