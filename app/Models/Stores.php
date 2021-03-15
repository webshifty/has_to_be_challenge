<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stores extends Model
{
    use HasFactory;

    protected $table = 'store';

    public static function getAllStores()
    {
        $stores = DB::table('store')->select()->orderByDesc('created_at')->get();

        return $stores;
    }

    public static function getWorkTimeForStaff($id)
    {
        $charging_station = DB::table('store')->select('for_staff_working_day', 'for_staff_working_time_from', 'for_staff_working_time_to')->where('id', $id)->first();

        return $charging_station;
    }
}
