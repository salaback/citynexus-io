<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cn_tagables', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('tagables_type');
            $table->integer('tagables_id')->unsigned();
            $table->integer('tag_id')->unsigned();
            $table->timestamp('created_at');
            $table->integer('created_by')->unsigned()->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cn_tagables');
    }
}
