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
            if (Schema::hasColumn('requirement_items', 'requirement_id'))
            {
                $table->dropColumn('requirement_id');
            }
        });

        Schema::table('requirement_items', function (Blueprint $table) {
            //

            $table->foreignId('requirement_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requirement_items', function (Blueprint $table) {
            //
            $table->dropColumn('requirement_id');
        });
    }
};
