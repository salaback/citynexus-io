<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cn_uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uploader_id')->unsigned();
            $table->foreign('uploader_id')->references('id')->on('cn_uploaders')->onDelete('cascade');
            $table->string('source')->nullable();
            $table->string('size')->nullable();
            $table->string('file_type')->nullable();
            $table->string('note')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('queues')->default(0);
            $table->json('new_property_ids')->nullable();
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
        Schema::drop('cn_uploads');
    }
}
