<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommitteesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committees', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('exam_session_id');
            $table->bigInteger('leader_id')->nullable();
            $table->bigInteger('member1_id')->nullable();
            $table->bigInteger('member2_id')->nullable();
            $table->bigInteger('secretary_id')->nullable();

            $table->foreign('leader_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('member1_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('member2_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('secretary_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('exam_session_id')->references('id')->on('exam_sessions')->onDelete('cascade');

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
        Schema::dropIfExists('committees');
    }
}
