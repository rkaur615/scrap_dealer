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
            //
            $table->unsignedBigInteger('worker_id')->nullable();
            $table->unsignedBigInteger('ia_id')->nullable();
            $table->unsignedBigInteger('da_id')->nullable();
            $table->text('ia_comment', 100)->nullable();
            $table->text('da_comment', 100)->nullable();
            $table->double('worker_amount', 15, 8)->nullable()->default(0.00);
            $table->double('ia_amount', 15, 8)->nullable()->default(0.00);
            $table->double('da_amount', 15, 8)->nullable()->default(0.00);
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
            //
            $table->dropColumn('ia_id');
            $table->dropColumn('da_id');
            $table->dropColumn('worker_id');
            $table->dropColumn('ia_comment');
            $table->dropColumn('da_comment');
            $table->dropColumn('worker_amount');
            $table->dropColumn('ia_amount');
            $table->dropColumn('da_amount');
        });
    }
};
