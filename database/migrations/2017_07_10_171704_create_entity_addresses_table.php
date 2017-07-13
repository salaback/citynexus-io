<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntityAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cn_entity_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->timestamps();
        });

        Schema::create('entity_entity_address', function (Blueprint $table) {
            $table->integer('entity_id')->unsigned();
            $table->integer('entity_address_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cn_entity_addresses');
        Schema::dropIfExists('entity_entity_address');
    }
}
