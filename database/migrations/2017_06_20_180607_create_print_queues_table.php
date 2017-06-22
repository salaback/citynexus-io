<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrintQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cn_print_queues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cn_document_id')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->integer('printed_by')->unsigned()->nullable();
            $table->dateTime('printed_at')->nullable();
            $table->json('settings')->nullable();
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
        Schema::dropIfExists('cn_print_queues');
    }
}
