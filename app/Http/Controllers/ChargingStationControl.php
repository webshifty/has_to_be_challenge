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

        return response()->json($data, 200, ['Content-Type' => 'application/json']);
    }

    public function showAllStores()
    {
        $data = Stores::getAllStores();

        return response()->json($data, 200, ['Content-Type' => 'application/json']);
    }

    public function showAllChargingStations()
    {
        $data = ChargingStation::getAllChargingStations();

        return response()->json($data, 200, ['Content-Type' => 'application/json']);
    }

    public function checkIfOpen($access, $id, $time)
    {
        $checkMaintenance = ChargingStation::findInMaintenance($id);
        $requestDayOfWeek = (string)Carbon::createFromTimestamp($time)->dayOfWeek;
        $requestTime = Carbon::createFromTimestamp($time)->format('H:i:s');
        $requestDate = Carbon::createFromTimestamp($time)->format('Y-m-d');

        if (empty($checkMaintenance)) {

            if ($access == 'staff') {
                $workTimeForStaff = ChargingStation::getWorkTimeForStaff($id);

                if (is_null($workTimeForStaff->for_staff_working_day)) {
                    $workTimeForStaffStore = Stores::getWorkTimeForStaff($workTimeForStaff->store_id);

                    if (is_null($workTimeForStaffStore)) {
                        $workTimeForStaffTenant = Tenants::getWorkTimeForStaff($workTimeForStaff->tenant_id);
                        $checkWorkingday = strpos($workTimeForStaffTenant->for_staff_working_day, $requestDayOfWeek);

                        if ($checkWorkingday === false) {
                            return response()->json(false, 200, ['Content-Type' => 'application/json']);
                        } else {

                            if ($requestTime >= $workTimeForStaffTenant->for_staff_working_time_from && $requestTime <= $workTimeForStaffTenant->for_staff_working_time_to) {
                                return response()->json(true, 200, ['Content-Type' => 'application/json']);
                            } else {
                                return response()->json(false, 200, ['Content-Type' => 'application/json']);
                            }
                        }
                    } else {
                        $checkWorkingday = strpos($workTimeForStaffStore->for_staff_working_day, $requestDayOfWeek);

                        if ($checkWorkingday === false) {
                            return response()->json(false, 200, ['Content-Type' => 'application/json']);
                        } else {

                            if ($requestTime >= $workTimeForStaffStore->for_staff_working_time_from && $requestTime <= $workTimeForStaffStore->for_staff_working_time_to) {
                                return response()->json(true, 200, ['Content-Type' => 'application/json']);
                            } else {
                                return response()->json(false, 200, ['Content-Type' => 'application/json']);
                            }
                        }
                    }
                } else {
                    $checkWorkingday = strpos($workTimeForStaff->for_staff_working_day, $requestDayOfWeek);

                    if ($checkWorkingday === false) {
                        return response()->json(false, 200, ['Content-Type' => 'application/json']);
                    } else {

                        if ($requestTime >= $workTimeForStaff->for_staff_working_time_from && $requestTime <= $workTimeForStaff->for_staff_working_time_to) {
                            return response()->json(true, 200, ['Content-Type' => 'application/json']);
                        } else {
                            return response()->json(false, 200, ['Content-Type' => 'application/json']);
                        }
                    }
                }
            } elseif ($access == 'client') {
                $workTimeForClient = ChargingStation::getWorkTimeForClient($id);

                if (is_null($workTimeForClient->for_client_working_day)) {
                    $workTimeForClientStore = Stores::getWorkTimeForClient($workTimeForClient->store_id);

                    if (is_null($workTimeForClientStore)) {
                        $workTimeForClientTenant = Tenants::getWorkTimeForClient($workTimeForClient->tenant_id);
                        $checkWorkingday = strpos($workTimeForClientTenant->for_client_working_day, $requestDayOfWeek);

                        if ($checkWorkingday === false) {
                            return response()->json(false, 200, ['Content-Type' => 'application/json']);
                        } else {

                            if ($requestTime >= $workTimeForClientTenant->for_client_working_time_from && $requestTime <= $workTimeForClientTenant->for_client_working_time_to) {
                                return response()->json(true, 200, ['Content-Type' => 'application/json']);
                            } else {
                                return response()->json(false, 200, ['Content-Type' => 'application/json']);
                            }
                        }
                    } else {
                        $checkWorkingday = strpos($workTimeForClientStore->for_client_working_day, $requestDayOfWeek);

                        if ($checkWorkingday === false) {
                            return response()->json(false, 200, ['Content-Type' => 'application/json']);
                        } else {

                            if ($requestTime >= $workTimeForClientStore->for_client_working_time_from && $requestTime <= $workTimeForClientStore->for_client_working_time_to) {
                                return response()->json(true, 200, ['Content-Type' => 'application/json']);
                            } else {
                                return response()->json(false, 200, ['Content-Type' => 'application/json']);
                            }
                        }
                    }
                } else {
                    $checkWorkingday = strpos($workTimeForClient->for_client_working_day, $requestDayOfWeek);

                    if ($checkWorkingday === false) {
                        return response()->json(false, 200, ['Content-Type' => 'application/json']);
                    } else {

                        if ($requestTime >= $workTimeForClient->for_client_working_time_from && $requestTime <= $workTimeForClient->for_client_working_time_to) {
                            return response()->json(true, 200, ['Content-Type' => 'application/json']);
                        } else {
                            return response()->json(false, 200, ['Content-Type' => 'application/json']);
                        }
                    }
                }
            }
        } else {
            if ($access == 'staff') {

                if ($requestTime >= $checkMaintenance->for_staff_working_time_from && $requestTime <= $checkMaintenance->for_staff_working_time_to) {
                    $message = 'From ' . $requestDate . ' ' . $checkMaintenance->for_staff_working_time_from . ' through ' . $checkMaintenance->for_staff_working_time_to . ', a given single charging station is closed due to a planned ' . $checkMaintenance->comment;

                    return response()->json($message, 200, ['Content-Type' => 'application/json']);
                } else {
                    $workSchedule = self::getWorkTime('staff', $id);
                    $checkWorkingday = strpos($workSchedule->for_staff_working_day, $requestDayOfWeek);

                    if ($checkWorkingday === false) {
                        return response()->json(false, 200, ['Content-Type' => 'application/json']);
                    } else {

                        if ($requestTime >= $workSchedule->for_staff_working_time_from && $requestTime <= $workSchedule->for_staff_working_time_to) {
                            return response()->json(true, 200, ['Content-Type' => 'application/json']);
                        } else {
                            return response()->json(false, 200, ['Content-Type' => 'application/json']);
                        }
                    }
                }
            } elseif ($access == 'client') {

                if ($requestTime >= $checkMaintenance->for_client_working_time_from && $requestTime <= $checkMaintenance->for_client_working_time_to) {
                    $message = 'From ' . $requestDate . ' ' . $checkMaintenance->for_client_working_time_from . ' through ' . $checkMaintenance->for_client_working_time_to . ', a given single charging station is closed due to a planned ' . $checkMaintenance->comment;

                    return response()->json($message, 200, ['Content-Type' => 'application/json']);
                } else {
                    $workSchedule = self::getWorkTime('client', $id);
                    $checkWorkingday = strpos($workSchedule->for_client_working_day, $requestDayOfWeek);

                    if ($checkWorkingday === false) {
                        return response()->json(false, 200, ['Content-Type' => 'application/json']);
                    } else {

                        if ($requestTime >= $workSchedule->for_client_working_time_from && $requestTime <= $workSchedule->for_client_working_time_to) {
                            return response()->json(true, 200, ['Content-Type' => 'application/json']);
                        } else {
                            return response()->json(false, 200, ['Content-Type' => 'application/json']);
                        }
                    }
                }
            }
        }
    }

    public function checkTheWorkSchedule($access, $id, $time)
    {
        $checkMaintenance = ChargingStation::findInMaintenance($id);
        $requestDayOfWeek = (string)Carbon::createFromTimestamp($time)->dayOfWeek;
        $requestTime = Carbon::createFromTimestamp($time)->format('H:i:s');
        $requestDate = Carbon::createFromTimestamp($time)->format('Y-m-d');

        switch ($access) {
            case ('staff'):
                if (empty($checkMaintenance)) {
                    $workSchedule = self::getWorkTime('staff', $id);
                    $checkWorkingday = strpos($workSchedule->for_staff_working_day, $requestDayOfWeek);

                    if ($checkWorkingday === false) {
                        $array = explode(',', $workSchedule->for_staff_working_day);

                        for ($i = 0; $i <= count($array);) {

                            if ($array[$i] >= $requestDayOfWeek) {
                                $day = $array[$i] - $requestDayOfWeek;
                                $timestamp = Carbon::parse($requestDate . $workSchedule->for_staff_working_time_from)->addDays($day)->timestamp;

                                return response()->json($timestamp, 200, ['Content-Type' => 'application/json']);
                            }
                        }
                    } else {
                        $timestamp = '';

                        if ($requestTime <= $workSchedule->for_staff_working_time_from && $requestTime <= $workSchedule->for_staff_working_time_to) {
                            $timestamp = Carbon::parse($requestDate . $workSchedule->for_staff_working_time_from)->timestamp;

                            return response()->json($timestamp, 200, ['Content-Type' => 'application/json']);
                        }

                        if ($requestTime >= $workSchedule->for_staff_working_time_from && $requestTime <= $workSchedule->for_staff_working_time_to) {
                            $timestamp = Carbon::parse($requestDate . $workSchedule->for_staff_working_time_to)->timestamp;

                            return response()->json($timestamp, 200, ['Content-Type' => 'application/json']);
                        }

                        if ($requestTime >= $workSchedule->for_staff_working_time_to) {
                            $array = explode(',', $workSchedule->for_staff_working_day);

                            for ($i = 0; $i <= count($array);) {

                                if ($array[$i] >= $requestDayOfWeek) {
                                    $day = $array[$i + 1] - $requestDayOfWeek;
                                    $timestamp = Carbon::parse($requestDate . $workSchedule->for_staff_working_time_from)->addDays($day)->timestamp;

                                    return response()->json($timestamp, 200, ['Content-Type' => 'application/json']);
                                }
                            }
                        }
                    }
                } else {
                    $timestamp = '';

                    if ($requestTime <= $checkMaintenance->for_staff_working_time_from && $requestTime <= $checkMaintenance->for_staff_working_time_to) {
                        $timestamp = Carbon::parse($requestDate . $checkMaintenance->for_staff_working_time_from)->timestamp;

                        return response()->json($timestamp, 200, ['Content-Type' => 'application/json']);
                    }

                    if ($requestTime >= $checkMaintenance->for_staff_working_time_from && $requestTime <= $checkMaintenance->for_staff_working_time_to) {
                        $timestamp = Carbon::parse($requestDate . $checkMaintenance->for_staff_working_time_to)->timestamp;

                        return response()->json($timestamp, 200, ['Content-Type' => 'application/json']);
                    }

                    if ($requestTime >= $checkMaintenance->for_staff_working_time_to) {
                        $workSchedule = self::getWorkTime('staff', $id);
                        $array = explode(',', $workSchedule->for_staff_working_day);

                        for ($i = 0; $i <= count($array);) {

                            if ($array[$i] >= $requestDayOfWeek) {
                                $day = $array[$i + 1] - $requestDayOfWeek;
                                $timestamp = Carbon::parse($requestDate . $workSchedule->for_staff_working_time_from)->addDays($day)->timestamp;

                                return response()->json($timestamp, 200, ['Content-Type' => 'application/json']);
                            }
                        }
                    }
                }
                break;
            case ('client'):
                if (empty($checkMaintenance)) {
                    $workSchedule = self::getWorkTime('client', $id);
                    $checkWorkingday = strpos($workSchedule->for_client_working_day, $requestDayOfWeek);

                    if ($checkWorkingday === false) {
                        $array = explode(',', $workSchedule->for_client_working_day);

                        for ($i = 0; $i <= count($array);) {

                            if ($array[$i] >= $requestDayOfWeek) {
                                $day = $array[$i] - $requestDayOfWeek;
                                $timestamp = Carbon::parse($requestDate . $workSchedule->for_client_working_time_from)->addDays($day)->timestamp;

                                return response()->json($timestamp, 200, ['Content-Type' => 'application/json']);
                            }
                        }
                    } else {
                        $timestamp = '';

                        if ($requestTime <= $workSchedule->for_client_working_time_from && $requestTime <= $workSchedule->for_client_working_time_to) {
                            $timestamp = Carbon::parse($requestDate . $workSchedule->for_client_working_time_from)->timestamp;

                            return response()->json($timestamp, 200, ['Content-Type' => 'application/json']);
                        }

                        if ($requestTime >= $workSchedule->for_client_working_time_from && $requestTime <= $workSchedule->for_client_working_time_to) {
                            $timestamp = Carbon::parse($requestDate . $workSchedule->for_client_working_time_to)->timestamp;

                            return response()->json($timestamp, 200, ['Content-Type' => 'application/json']);
                        }

                        if ($requestTime >= $workSchedule->for_client_working_time_to) {
                            $array = explode(',', $workSchedule->for_client_working_day);

                            for ($i = 0; $i <= count($array);) {

                                if ($array[$i] >= $requestDayOfWeek) {
                                    $day = $array[$i + 1] - $requestDayOfWeek;
                                    $timestamp = Carbon::parse($requestDate . $workSchedule->for_client_working_time_from)->addDays($day)->timestamp;

                                    return response()->json($timestamp, 200, ['Content-Type' => 'application/json']);
                                }
                            }
                        }
                    }
                } else {
                    $timestamp = '';

                    if ($requestTime <= $checkMaintenance->for_client_working_time_from && $requestTime <= $checkMaintenance->for_client_working_time_to) {
                        $timestamp = Carbon::parse($requestDate . $checkMaintenance->for_client_working_time_from)->timestamp;

                        return response()->json($timestamp, 200, ['Content-Type' => 'application/json']);
                    }

                    if ($requestTime >= $checkMaintenance->for_client_working_time_from && $requestTime <= $checkMaintenance->for_client_working_time_to) {
                        $timestamp = Carbon::parse($requestDate . $checkMaintenance->for_client_working_time_to)->timestamp;

                        return response()->json($timestamp, 200, ['Content-Type' => 'application/json']);
                    }

                    if ($requestTime >= $checkMaintenance->for_client_working_time_to) {
                        $workSchedule = self::getWorkTime('client', $id);
                        $array = explode(',', $workSchedule->for_client_working_day);

                        for ($i = 0; $i <= count($array);) {

                            if ($array[$i] >= $requestDayOfWeek) {
                                $day = $array[$i + 1] - $requestDayOfWeek;
                                $timestamp = Carbon::parse($requestDate . $workSchedule->for_client_working_time_from)->addDays($day)->timestamp;

                                return response()->json($timestamp, 200, ['Content-Type' => 'application/json']);
                            }
                        }
                    }
                }
                break;
        }
    }

    public function getWorkTime($access, $id)
    {
        $data = [];
        if ($access == 'staff') {
            $workTimeForStaff = ChargingStation::getWorkTimeForStaff($id);
            $data = $workTimeForStaff;

            if (is_null($workTimeForStaff->for_staff_working_day)) {
                $workTimeForStaffStore = Stores::getWorkTimeForStaff($workTimeForStaff->store_id);
                $data = $workTimeForStaffStore;

                if (is_null($workTimeForStaffStore)) {
                    $workTimeForStaffTenant = Tenants::getWorkTimeForStaff($workTimeForStaff->tenant_id);
                    $data = $workTimeForStaffTenant;
                }
            }
        }

        if ($access == 'client') {
            $workTimeForClient = ChargingStation::getWorkTimeForClient($id);
            $data = $workTimeForClient;

            if (is_null($workTimeForClient->for_client_working_day)) {
                $workTimeForClientStore = Stores::getWorkTimeForClient($workTimeForClient->store_id);
                $data = $workTimeForClientStore;

                if (is_null($workTimeForClientStore)) {
                    $workTimeForClientTenant = Tenants::getWorkTimeForClient($workTimeForClient->tenant_id);
                    $data = $workTimeForClientTenant;
                }
            }
        }

        return $data;
    }
}
