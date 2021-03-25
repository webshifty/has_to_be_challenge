<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use DateTime;

class HolidayControl extends Controller
{
    public function checkDate(Request $request)
    {
        $data = null;
        $requestDate = Carbon::parse($request->date);
        $params = Config::get('holidays.params');

        foreach ($params as $param) {
            $patterns = array();
            $patterns[0] = '/1st of/';
            $patterns[1] = '/7th of/';
            $patterns[2] = '/Monday of the 3rd week of/';
            $patterns[3] = '/Monday of the last week/';
            $patterns[4] = '/Thursday of the 4th week/';
            $replacements = array();
            $replacements[0] = '1st';
            $replacements[1] = '7th';
            $replacements[2] = 'last monday -7 day';
            $replacements[3] = 'last monday';
            $replacements[4] = 'last Thursday';
            $param = preg_replace($patterns, $replacements, $param);
            $holidayDate = new DateTime();
            $holidayDate->modify($param);
            $holidayDateTommorrow = Carbon::parse($holidayDate)->addDays(1);
            $holidayDateAfterTommorrow = Carbon::parse($holidayDate)->addDays(2);

            if ($requestDate->format('d-m') == $holidayDate->format('d-m')) {
                $data = 'It’s holiday on that date!';
            }

            if ($holidayDate->format('l') == 'Saturday') {

                if ($requestDate->format('d-m') == $holidayDateTommorrow->format('d-m') || $requestDate->format('d-m') == $holidayDateAfterTommorrow->format('d-m')) {
                    $data = 'It’s holiday on that date!';
                }
            }

            if ($holidayDate->format('l') == 'Sunday') {

                if ($requestDate->format('d-m') == $holidayDateTommorrow->format('d-m')) {
                    $data = 'It’s holiday on that date!';
                }
            }
        }

        if (is_null($data)) {
            $data = 'No holiday on that date!';
        }

        return response()->json($data, 200, ['Content-Type' => 'application/json']);
    }

}
