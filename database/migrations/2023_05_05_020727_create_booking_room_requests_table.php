<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingRoomRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_room_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->timestamp('booking_date_start')->nullable();
            $table->timestamp('booking_date_finish')->nullable();
            $table->unsignedBigInteger('course_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('booking_room_requests', function (Blueprint $table) {   
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('course_id')->references('id')->on('courses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_room_requests');
    }
}
