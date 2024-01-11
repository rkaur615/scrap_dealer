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
        //
        Schema::table('user_categories', function (Blueprint $table) {
            //
            $table->dropForeign('user_categories_user_company_id_foreign');
            $table->dropColumn('user_company_id');

            $table->dropForeign('user_categories_subcategory_id_foreign');
            $table->dropColumn('subcategory_id');

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
