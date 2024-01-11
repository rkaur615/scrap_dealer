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
        Schema::create('operational_areas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_company_id')->unsigned();
            $table->bigInteger('city_id')->unsigned();            
            $table->timestamps();
            $table->foreign('user_company_id')->references('id')->on('user_companies')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operational_areas');
    }
};
