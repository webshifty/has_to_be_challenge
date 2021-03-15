<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargingStation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charging_station', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('tenant_id');
            $table->integer('store_id');
            $table->string('for_client_working_day')->nullable();
            $table->time('for_client_working_time_from')->nullable();
            $table->time('for_client_working_time_to')->nullable();
            $table->string('for_staff_working_day')->nullable();
            $table->time('for_staff_working_time_from')->nullable();
            $table->time('for_staff_working_time_to')->nullable();
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
        Schema::dropIfExists('charging_station');
    }
}
