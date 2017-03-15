<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cn_units', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unit')->nullable();
            $table->string('unit_type')->nullable();
            $table->integer('address_id')->unsigned();
            $table->foreign('address_id')->references('id')->on('cn_addresses')->onDelete('cascade');
            $table->integer('property_id')->unsigned()->nullable();
            $table->integer('lot_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cn_units');
    }
}
