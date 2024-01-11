<?php

namespace App\Observers;

use App\Models\ProductBidding;

class BidAcceptedObserver
{
    //
    public function updated(ProductBidding $productBid){
        /**
         * Mark Other Bids As Closed (If Not Recycler).
         * Notify Admin - Give Option to Py Seller & Assign IA/DA If needed.
         * Notify Seller
         *
         */
        // dd($productBid);
    }
}

