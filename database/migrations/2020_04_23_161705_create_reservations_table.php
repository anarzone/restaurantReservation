<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('res_firstname');
            $table->string('res_lastname');
            $table->string('res_phone');
            $table->bigInteger('res_restaurant_id')->unsigned();
            $table->bigInteger('res_hall_id')->unsigned();
            $table->foreign('res_restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->dateTime('datetime');
            $table->integer('status');
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
        Schema::dropIfExists('reservations');
    }
}
