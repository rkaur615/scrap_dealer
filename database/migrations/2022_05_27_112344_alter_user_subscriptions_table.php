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
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->bigInteger('transaction_id')->after('subscription_plan_id');
            $table->string('name')->nullable();
            $table->bigInteger('amount');
            $table->boolean('isSuccessfulTransaction')->default(true);
            $table->bigInteger('no_of_leads'); 
            $table->string('is_lead_carry_over'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {  
            $table->dropColumn('isSuccessfulTransaction');
            $table->dropColumn('transaction_id');
            $table->dropColumn('amount');
            $table->dropColumn('name');
            $table->dropColumn('no_of_leads');
            $table->dropColumn('is_lead_carry_over');

        });
    }
};
