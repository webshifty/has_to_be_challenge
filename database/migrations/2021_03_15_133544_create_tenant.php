<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant', function (Blueprint $table) {
            $table->id();
            $table->string('name');
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
        Schema::dropIfExists('tenant');
    }
}
