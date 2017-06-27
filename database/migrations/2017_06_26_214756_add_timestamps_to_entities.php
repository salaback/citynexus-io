<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampsToEntities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cn_entitables', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable();
            $table->softDeletes();
        });

        function up()
        {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cn_entitables` MODIFY `upload_id` INTEGER UNSIGNED NULL;');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cn_entitables', function (Blueprint $table) {
            $table->removeColumn('created_at', 'deleted_at');
        });
    }
}
