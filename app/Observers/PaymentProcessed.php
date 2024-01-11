<?php

namespace App\Observers;

use App\Models\OrderPayment;

class PaymentProcessed
{
    //

    public function creating(){

    }

    public function created(OrderPayment $op){
        /**
         * Payment Made By User
         */
        if($op->payment_type == config('constants.payment.PAY-BID-AMOUNT')){
            /**
             * Notify Admin
             */
            /**
             * Make Payment To Seller
             */
        }
    }
}
