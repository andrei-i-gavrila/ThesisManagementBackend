<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessorDoiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professor_doi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('professor_id');
            $table->bigInteger('doi_id');

            $table->foreign('professor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doi_id')->references('id')->on('domain_of_interests')->onDelete('cascade');

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
        Schema::dropIfExists('professor_doi');
    }
}
