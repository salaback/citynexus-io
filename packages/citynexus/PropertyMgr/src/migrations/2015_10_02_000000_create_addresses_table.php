<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cn_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_address')->nullable();
            $table->string('building')->nullable();
            $table->string('house_num')->nullable();
            $table->string('predir')->nullable();
            $table->string('qual')->nullable();
            $table->string('pretype')->nullable();
            $table->string('name')->nullable();
            $table->string('suftype')->nullable();
            $table->string('sufdir')->nullable();
            $table->string('ruralroute')->nullable();
            $table->string('extra')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->string('box')->nullable();
            $table->string('country')->nullable();
            $table->string('unit')->nullable();
            $table->string('unparsed')->nullable();
            $table->integer('property_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cn_addresses');
    }
}
