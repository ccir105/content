<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnDeleteCascade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_profiles', function (Blueprint $table) {
            $table->dropForeign('supplier_profiles_supplier_id_foreign');
        });

        Schema::table('supplier_profiles', function (Blueprint $table) {
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_profiles', function (Blueprint $table) {
            //
        });
    }
}
