<?php

namespace App\Http\Controllers;

use App\Models\ChargingStation;
use App\Models\Stores;
use App\Models\Tenants;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChargingStationControl extends Controller
{
    public function showAllTenants()
    {
        $data = Tenants::getAllTenants();

        return response()->json(['data' => $data], 200, ['Content-Type' => 'application/json']);
    }

    public function showAllStores()
    {
        $data = Stores::getAllStores();

        return response()->json(['data' => $data], 200, ['Content-Type' => 'application/json']);
    }

    public function showAllChargingStations()
    {
        $data = ChargingStation::getAllChargingStations();

        return response()->json(['data' => $data], 200, ['Content-Type' => 'application/json']);
    }

    public function checkIfOpen($access, $id, $time)
    {
        $checkMaintenance = ChargingStation::findInMaintenance($id);
        $requestDayOfWeek = (string)Carbon::parse($time)->dayOfWeek;
        $requestTime = Carbon::parse($time)->format('H:m:s');

        if (count($checkMaintenance) == 0) {

            if ($access == 'staff') {
                $workTimeForStaff = ChargingStation::getWorkTimeForStaff($id);

                if (is_null($workTimeForStaff->for_staff_working_day)) {
                    $workTimeForStaffStore = Stores::getWorkTimeForStaff($workTimeForStaff->store_id);

                    if (is_null($workTimeForStaffStore)) {
                        $workTimeForStaffTenant = Tenants::getWorkTimeForStaff($workTimeForStaff->tenant_id);
                        $checkWorkingday = strpos($workTimeForStaffTenant->for_staff_working_day, $requestDayOfWeek);

                        if ($checkWorkingday === false) {
                            return response()->json(['result' => 'close'], 200, ['Content-Type' => 'application/json']);
                        } else {

                            if ($requestTime >= $workTimeForStaffTenant->for_staff_working_time_from && $time <= $workTimeForStaffTenant->for_staff_working_time_to) {
                                return response()->json(['result' => 'open'], 200, ['Content-Type' => 'application/json']);
                            } else {
                                return response()->json(['result' => 'close'], 200, ['Content-Type' => 'application/json']);
                            }
                        }
                    } else {
                        $checkWorkingday = strpos($workTimeForStaffStore->for_staff_working_day, $requestDayOfWeek);

                        if ($checkWorkingday === false) {
                            return response()->json(['result' => 'close'], 200, ['Content-Type' => 'application/json']);
                        } else {

                            if ($requestTime >= $workTimeForStaffStore->for_staff_working_time_from && $time <= $workTimeForStaffStore->for_staff_working_time_to) {
                                return response()->json(['result' => 'open'], 200, ['Content-Type' => 'application/json']);
                            } else {
                                return response()->json(['result' => 'close'], 200, ['Content-Type' => 'application/json']);
                            }
                        }
                    }
                } else {
                    $checkWorkingday = strpos($workTimeForStaff->for_staff_working_day, $requestDayOfWeek);

                    if ($checkWorkingday === false) {
                        return response()->json(['result' => 'close'], 200, ['Content-Type' => 'application/json']);
                    } else {

                        if ($requestTime >= $workTimeForStaff->for_staff_working_time_from && $time <= $workTimeForStaff->for_staff_working_time_to) {
                            return response()->json(['result' => 'open'], 200, ['Content-Type' => 'application/json']);
                        } else {
                            return response()->json(['result' => 'close'], 200, ['Content-Type' => 'application/json']);
                        }
                    }
                }
            } elseif ($access == 'client') {

            }
        } else {

        }

        //return response()->json(['data' => $data], 200, ['Content-Type' => 'application/json']);
    }

    public function checkTheWorkSchedule($id, $time)
    {

    }
}
