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
        Schema::table('category_form_data', function (Blueprint $table) {
            $table->dropColumn('key');
            $table->unsignedBigInteger('type_id');
            $table->string('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_form_data', function (Blueprint $table) {
            $table->string('key');
            $table->dropColumn('type_id');
            $table->dropColumn('types');
        });
    }
};
