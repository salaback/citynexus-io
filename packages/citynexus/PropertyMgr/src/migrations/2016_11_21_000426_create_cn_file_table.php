<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCnFileTable extends Migration
{
    /**
     * Run the migrations.
     * d
     * @return void
     */
    public function up()
    {
        Schema::create('cn_files', function (Blueprint $table) {
            $table->increments('id')->unsigned();;
            $table->integer('cn_fileable_id')->unsigned;
            $table->string('cn_fileable_type');
            $table->string('caption')->nullable();
            $table->string('description')->nullable();
            $table->integer('version_id')->nullable();
            $table->softDeletes();
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
        Schema::drop('citynexus_files');
    }
}
