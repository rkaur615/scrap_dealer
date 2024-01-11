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
            $table->renameColumn('product_name', 'title');
            $table->dropForeign('products_added_by_foreign');
            $table->renameColumn('added_by', 'user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
            $table->renameColumn('title', 'product_name');
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->renameColumn('user_id', 'added_by');
            $table->dropForeign('user_id');
        });
    }
};
