<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments',function(Blueprint $table)
        {
            $table->increments('id');
            $table->text('content');
            $table->integer('user_id')->unsigned()->default(0);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->integer('post_id')->unsigned()->default(0);
            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
                ->onDelete('cascade');
            $table->bigInteger('noResponses')->unsigned()->default(0);
            $table->bigInteger('votes')->default(0);
            $table->timestamps();

        });

        Schema::create('comment_rating',function(Blueprint $table){
            $table->increments('id');
            $table->integer('comment_id');
            $table->integer('user_id');
            $table->bigInteger('likes')->unsigned()->default(0);
            $table->bigInteger('dislikes')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comments');
        Schema::drop('comment_rating');
    }
}
