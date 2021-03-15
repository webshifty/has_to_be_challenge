<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChargingStation extends Model
{
    use HasFactory;

    protected $table = 'charging_station';

    public static function getAllChargingStations()
    {
        $charging_stations = DB::table('charging_station')->select()->orderByDesc('created_at')->get();

        return $charging_stations;
    }

    public static function findInMaintenance($id)
    {
        $maintenance = DB::table('charging_station_maintenance')->select()->where('charging_station_id', $id)->orderByDesc('created_at')->get();

        return $maintenance;
    }

    public static function getWorkTimeForStaff($id)
    {
        $charging_station = DB::table('charging_station')->select('tenant_id', 'store_id', 'for_staff_working_day', 'for_staff_working_time_from', 'for_staff_working_time_to')->where('id', $id)->first();

        return $charging_station;
    }
}
