<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargingStationMaintenance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charging_station_maintenance', function (Blueprint $table) {
            $table->id();
            $table->integer('charging_station_id');
            $table->string('for_client_working_day')->nullable();
            $table->time('for_client_working_time_from')->nullable();
            $table->time('for_client_working_time_to')->nullable();
            $table->string('for_staff_working_day')->nullable();
            $table->time('for_staff_working_time_from')->nullable();
            $table->time('for_staff_working_time_to')->nullable();
            $table->string('comment')->default('maintenance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charging_station_maintenance');
    }
}
