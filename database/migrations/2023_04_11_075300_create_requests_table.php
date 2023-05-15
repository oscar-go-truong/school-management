<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_request_id');
            $table->unsignedBigInteger('user_approve_id')->nullable();
            $table->integer('type');
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->foreign('user_request_id')->references('id')->on('users');
            $table->foreign('user_approve_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
