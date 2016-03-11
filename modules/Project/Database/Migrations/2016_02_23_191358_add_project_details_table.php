<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectDetailsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('projects', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
            $table->integer('country_id')->unsigned();
            $table->integer('status');
            $table->integer('order');
            $table->text('description');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('project_pages', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->string('title');
            $table->integer('order');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('field_groups', function( Blueprint $table ){
            $table->increments('id');
            $table->string('title');
            $table->integer('page_id')->unsigned();
            $table->foreign('page_id')->references('id')->on('project_pages')->onDelete('cascade');
            $table->text('description');
            $table->integer('order');
            $table->timestamps();
        });

        Schema::create('fields', function(Blueprint $table){
            $table->increments('id');
            $table->string('type');
            $table->string('title');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('field_values', function(Blueprint $table){

            $table->increments('id');

            $table->integer('field_id')->unsigned();

            $table->integer('group_id')->nullable()->unsigned();

            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');

            $table->foreign('group_id')->references('id')->on('field_groups')->onDelete('cascade');

            $table->integer('order');
            $table->text('title');
            $table->text('description');
            $table->mediumText('value'); //as per the field
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
