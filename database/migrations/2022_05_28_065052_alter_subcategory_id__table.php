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
        Schema::enableForeignKeyConstraints();
        Schema::table('user_categories', function (Blueprint $table) {
            $table->foreignId('subcategory_id')->nullable()->change();
        });
        Schema::disableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::enableForeignKeyConstraints();
        Schema::table('user_categories', function (Blueprint $table) {
            $table->foreignId('subcategory_id')->change();
        });
        Schema::disableForeignKeyConstraints();
    }
};
