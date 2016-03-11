<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssigned extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigned_threads', function(Blueprint $table)
        {
            $table->increments('id');

            $table->integer('thread_id')->unsigned();
            $table->foreign('thread_id')->referecnes('id')->on('threads')->onDelete('cascade');

            $table->integer('assigned_by')->unsigned();
            $table->foreign('assigned_by')->referecnes('id')->on('users')->onDelete('cascade');

            $table->integer('assigned_to')->unsigned();
            $table->foreign('assigned_to')->referecnes('id')->on('users')->onDelete('cascade');

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
