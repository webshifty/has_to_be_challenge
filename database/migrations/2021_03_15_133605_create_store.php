<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('tenant_id');
            $table->string('for_client_working_day');
            $table->time('for_client_working_time_from');
            $table->time('for_client_working_time_to');
            $table->string('for_staff_working_day');
            $table->time('for_staff_working_time_from');
            $table->time('for_staff_working_time_to');
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
        Schema::dropIfExists('store');
    }
}
