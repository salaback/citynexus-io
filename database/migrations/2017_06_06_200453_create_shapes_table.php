<?php

use Illuminate\Support\Facades\Schema;
use Phaza\LaravelPostgis\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShapesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cn_shapes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->polygon('shape');
            $table->point('point')->nullable();
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
        Schema::dropIfExists('cn_shapes');
    }
}
