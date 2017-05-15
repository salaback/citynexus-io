<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cn_scores', function (Blueprint $table) {
            $table->increments('id');
            $table->json('elements')->nullable();
            $table->string('name')->nullable();
            $table->string('period')->nullable();
            $table->boolean('timeseries')->nullable();
            $table->integer('owned_by')->unsigned();
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
        Schema::drop('cn_scores');
    }

}
