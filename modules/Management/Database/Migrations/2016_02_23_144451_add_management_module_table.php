<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManagementModuleTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function(Blueprint $table){
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger('status');
            $table->timestamps();
        });

        Schema::create('resources',function (Blueprint $table){
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_type');
            $table->timestamps();
        });

        Schema::create('project_resources',function (Blueprint $table){
            $table->increments('id');
            $table->string('tag');
            $table->integer('resource_id')->unsigned();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('projects_users',function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->timestamps();
        });


        Schema::create('threads',function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->longText('description');
            $table->timestamps();
        });

        Schema::create('assigned_threads',function (Blueprint $table){
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger('priority');
            $table->timestamps();
        });

        Schema::create('thread_comments', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('text');
            $table->integer('thread_id')->unsigned();
            $table->foreign('thread_id')->references('id')->on('threads')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('thread_resources',function ( Blueprint $table ){
            $table->increments('id');
            $table->integer('resource_id')->unsigned();
            $table->integer('thread_id')->unsigned();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
            $table->foreign('thread_id')->references('id')->on('threads')->onDelete('cascade');
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
        Schema::drop('projects_users');
        Schema::drop('projects');
    }
}
