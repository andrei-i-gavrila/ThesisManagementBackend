<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaperMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paper_metrics', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('word_count');
            $table->integer('page_count');
            $table->integer('char_count');

            $table->bigInteger('paper_revision_id');

            $table->foreign('paper_revision_id')->references('id')->on('paper_revisions')->onDelete('cascade');
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
        Schema::dropIfExists('paper_metrics');
    }
}
