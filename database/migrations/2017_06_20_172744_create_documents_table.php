<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cn_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cn_document_template_id')->unsigned();
            $table->integer('documented_id')->unsigned();
            $table->string('documented_type')->unsigned();
            $table->text('body')->nullable();
            $table->json('history')->nullable();
            $table->integer('created_by')->unsigned();
            $table->integer('sender_id')->nullable();
            $table->softDeletes();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('cn_documents');
    }
}
