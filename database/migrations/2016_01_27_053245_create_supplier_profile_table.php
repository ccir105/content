<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_profiles', function (Blueprint $table) {
            $table->integer('supplier_id')->unsigned();
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->string('company_name');
            $table->string('website');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('road');
            $table->string('address');
            $table->string('zip_code');
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_profile', function (Blueprint $table) {
            //
        });
    }
}
