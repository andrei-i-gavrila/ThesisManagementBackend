<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaperRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paper_revisions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->string('filepath');
            $table->bigInteger('paper_id');

            $table->foreign('paper_id')->references('id')->on('papers')->onDelete('cascade');
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
        Schema::dropIfExists('paper_revisions');
    }
}
