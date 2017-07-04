<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntityPivotTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cn_entitables', function(Blueprint $table)
        {
            $table->string('entitables_type');
            $table->integer('entitables_id')->unsigned();
            $table->integer('upload_id')->unsigned()->nullable();
            $table->integer('entity_id')->unsigned();
            $table->string('role')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cn_entitables');
    }

}
