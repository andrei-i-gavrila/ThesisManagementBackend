<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('exam_session_id');
            $table->integer('overall');
            $table->integer('grade_recommendation');
            $table->integer('structure');
            $table->integer('originality');
            $table->integer('literature_results');
            $table->integer('references');
            $table->integer('form');

            $table->integer('result_analysis');
            $table->integer('result_presentation');

            $table->integer('app_complexity');
            $table->integer('app_quality');

            $table->text('observations')->nullable();


            $table->bigInteger('professor_id');
            $table->bigInteger('student_id');

            $table->foreign('professor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('final_reviews');
    }
}
