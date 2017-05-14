<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRawEntitiesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cn_raw_entities', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('title')->nullable();
            $table->string('suffix')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_structure')->nullable();
            $table->string('full_name')->nullable();
            $table->integer('entity_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cn_raw_entities');
    }

}
