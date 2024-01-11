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
        Schema::table('order_payments', function (Blueprint $table) {
            $table->dropForeign('order_payments_user_id_foreign');
            $table->renameColumn('user_id', 'payment_by');
            $table->bigInteger('paid_to')->unsigned()->nullable();
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
        Schema::table('order_payments', function (Blueprint $table) {
            $table->dropForeign('order_payments_payment_by_foreign');
            $table->renameColumn('payment_by', 'user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->dropForeign('order_payments_paid_to_foreign');
            $table->dropColumn('paid_to');
            $table->dropColumn('payment_type');
        });
    }
};
