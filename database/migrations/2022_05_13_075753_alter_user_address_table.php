<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->string('name')->default(' ')->change();
            $table->string('name')->nullable(false)->change();
            $table->bigInteger('state_id')->unsigned()->change();
            $table->bigInteger('city_id')->unsigned()->change();
            $table->bigInteger('country_id')->unsigned()->change();
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropForeign('state_id');
            $table->dropForeign('city_id');
            $table->dropForeign('country_id');
            
        });
    }
};
