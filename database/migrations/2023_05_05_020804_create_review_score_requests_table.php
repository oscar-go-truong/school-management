<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewScoreRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_score_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->integer('new_score')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('review_score_requests', function (Blueprint $table) {
           $table->foreign('exam_id')->references('id')->on('exams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review_score_requests');
    }
}
