<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CommentsResponses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments_responses',function(Blueprint $table){

            $table->increments('id');
            $table->text('content');
            $table->integer('user_id')->unsigned()->default(0);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->integer('comment_id')->unsigned()->default(0);
            $table->foreign('comment_id')
                ->references('id')
                ->on('comments')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('response_rating', function (Blueprint $table){
            $table->increments('id');
            $table->integer('user_id')->unsigned()->default(0);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->integer('response_id')->unsigned()->default(0);
            $table->foreign('response_id')
                ->references('id')
                ->on('comments_responses')
                ->onDelete('cascade');
            $table->integer('likes')->default(0);
            $table->integer('dislikes')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comments_responses');
        Schema::drop('response_rating');
    }
}
