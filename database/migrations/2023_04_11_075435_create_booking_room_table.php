<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_room', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('request_id');
            $table->integer('status');
            $table->timestamps();
        });

        Schema::table('booking_room', function (Blueprint $table) {   
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('request_id')->references('id')->on('requests')->nullable;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_room');
    }
}
