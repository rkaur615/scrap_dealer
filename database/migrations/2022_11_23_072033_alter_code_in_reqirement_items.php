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
        Schema::table('requirement_items', function (Blueprint $table) {
            //
            $table->dropForeign('requirement_items_title_id_foreign');
            $table->dropColumn('title_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reqirement_items', function (Blueprint $table) {
            //
        });
    }
};
