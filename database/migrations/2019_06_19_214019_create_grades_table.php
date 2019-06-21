<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('value')->nullable();
            $table->bigInteger('paper_id');
            $table->bigInteger('category_id');
            $table->bigInteger('professor_id');

            $table->foreign('paper_id')->references('id')->on('papers')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('grading_categories')->onDelete('cascade');
            $table->foreign('professor_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('grades');
    }
}
