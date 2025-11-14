<?php

namespace App\Models\orm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;
    protected $table = 'mzl.ad_user';
    protected $hidden = ['password'];

    public static function findUserIdByUsername($username)
    {
        return self::select('ad_user_id as id')
            ->where('value', $username)
            ->first();
    }
}
