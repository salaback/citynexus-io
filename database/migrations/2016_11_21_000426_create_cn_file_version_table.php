<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCnFileVersionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cn_file_versions', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('added_by')->unsigned();
            $table->integer('file_id')->unsigned();
            $table->integer('size')->unsigned();
            $table->string('type');
            $table->string('source');
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
        Schema::drop('citynexus_file_versions');
    }
}
