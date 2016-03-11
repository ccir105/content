<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThreadModuleTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->tinyInteger('status');
            $table->string('type'); //bugs, information
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->text('description');
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('comments',function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('text');
            $table->string('commentable_type');
            $table->integer('commentable_id')->unsigned();
        });

        Schema::create('resources',function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('type');
            $table->string('path');
            $table->integer('imagable_id')->unsigned();
            $table->string('imagable_type');
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
        Schema::table('', function(Blueprint $table)
        {

        });
    }

}
