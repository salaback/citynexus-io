<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCnTaskMorphTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cn_taskables', function (Blueprint $table) {
            $table->integer('task_id');
            $table->integer('cn_taskable_id');
            $table->string('cn_taskable_type');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cn_taskables');
    }
}
