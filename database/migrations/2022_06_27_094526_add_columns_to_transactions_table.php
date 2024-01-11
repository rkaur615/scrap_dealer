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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_added_by_foreign');
            $table->renameColumn('added_by', 'payment_by');
            $table->bigInteger('paid_to')->unsigned()->nullable();
            $table->float('payment_amount');
            $table->tinyInteger('payment_type');
            $table->foreign('paid_to')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payment_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_payment_by_foreign');
            $table->renameColumn('payment_by', 'added_by');
            $table->dropColumn('paid_to');
            $table->dropColumn('payment_amount');
            $table->dropColumn('payment_type');
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
