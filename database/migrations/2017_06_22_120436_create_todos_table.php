<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('cn_tasks');

        Schema::create('cn_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_list_id')->unsigned();
            $table->string('name');
            $table->text('body')->nullable();
            $table->string('status')->nullable();
            $table->integer('created_by')->unsigned();
            $table->integer('assigned_to')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->dateTime('due_at')->nullable();
            $table->json('triggers')->nullable();
            $table->json('history')->nullable();
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
        Schema::dropIfExists('cn_tasks');
    }
}
