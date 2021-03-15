<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tenants extends Model
{
    use HasFactory;

    protected $table = 'tenant';

    public static function getAllTenants()
    {
        $tenants = DB::table('tenant')->select()->orderByDesc('created_at')->get();

        return $tenants;
    }

    public static function getWorkTimeForStaff($id)
    {
        $charging_station = DB::table('tenant')->select('for_staff_working_day', 'for_staff_working_time_from', 'for_staff_working_time_to')->where('id', $id)->first();

        return $charging_station;
    }
}
