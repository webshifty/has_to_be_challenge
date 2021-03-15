<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChargingStationControl;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('tenants', [ChargingStationControl::class, 'showAllTenants']);
Route::get('stores', [ChargingStationControl::class, 'showAllStores']);
Route::get('charging_stations', [ChargingStationControl::class, 'showAllChargingStations']);
Route::get('check_if_open/{access}/{id}/{time}', [ChargingStationControl::class, 'checkIfOpen']);
Route::get('check_the_work_schedule/{access}/{id}/{time}', [ChargingStationControl::class, 'checkTheWorkSchedule']);
