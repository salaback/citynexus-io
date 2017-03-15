<?php

use Illuminate\Support\Facades\Schema;
use Phaza\LaravelPostgis\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cn_properties', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('building_id')->nullable()->unsigned();
            $table->integer('lot_id')->nullable()->unsigned();
            $table->point('location')->nullable();
            $table->polygon('polygon')->nullable();
            $table->string('address')->nullable();
            $table->string('unit')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postcode')->nullable();
            $table->boolean('is_building')->default(false);
            $table->boolean('is_unit')->default(false);
            $table->boolean('is_lot')->default(false);
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
        Schema::drop('cn_properties');
    }

}
