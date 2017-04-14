<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cn_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cn_commentable_id')->unsigned();
            $table->string('cn_commentable_type');
            $table->string('title')->nullable();
            $table->text('comment');
            $table->integer('posted_by');
            $table->integer('reply_to')->nullable();
            $table->json('edits')->nullable();
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
        Schema::drop('cn_comments');
    }
}
