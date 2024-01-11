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
        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->comment('1 for pending - default');
            $table->string('status_reason')->nullable();
            $table->unsignedBigInteger('inspection_agent')->nullable();
            $table->unsignedBigInteger('delivery_boy')->nullable();
            $table->foreign('inspection_agent')->references('id')->on('admins');
            $table->foreign('delivery_boy')->references('id')->on('admins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('status_reason');
            $table->dropForeign('inspection_agent');
            $table->dropForeign('delivery_boy');
            $table->dropColumn('inspection_agent');
            $table->dropColumn('delivery_boy');
        });
    }
};
