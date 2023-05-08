<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSwitchCourseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('switch_course_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_course_id');
            $table->unsignedBigInteger('new_course_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('switch_course_requests', function (Blueprint $table) {
           $table->foreign('old_course_id')->references('id')->on('courses');
           $table->foreign('new_course_id')->references('id')->on('courses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('switch_course_requests');
    }
}
