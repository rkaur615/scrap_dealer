<?php

use Illuminate\Support\Arr;

return array(
    'category' => array(
           'Category' => 1,
           'Service' => 2,
        //    'CharitableCategory' => 2,
        //    'ServiceCategory' => 3,
        //    'RecyclerCategory' => 4,
        //    'ProductCategory' =>5,
        //    'ShareCategory' => 6,
    ),
    'userRoles' => array(
        'supplier' => 1,
        'retailer' => 2,
    ),
    'requirements' => array(
        'open' => 1,
        'closed' => 0,
    ),
    'requirement_item'=>[
        'accepted'=>1,
        'rejected'=>2
    ],
    'supplierRequirementStatus' => array(
        'pending' => 1,
        'quoteSent' => 2,
        'rejected' => 3,
        'approved' => 4,
        'completed' => 5,
    ),
    'userRequirementStatus' => array(
        'pending' => 1,
        'accepted' => 2,
        'rejected' => 3,
    ),
    'fileTypes' => array(
        'user' => 1,
    ),

    'fieldTypes' => array(
        'text' => 1,
        'email' => 2,
        'file' => 3,
        'number' => 4,
        'checkbox' => 5,
        'textarea' => 6
    ),
    'responseStatus' => array(
        'pending' => 1,
        'accepted' => 2,
        'rejected' => 3
    ),
    'sellOptions' => array(
        'toss' => 1,
        'donate' => 2,
        'recycle' => 4,
        'sell' => 5,
        'share' => 6, // change selloptions constant according to catType discuss with anil sir

    ),
    'productStatus' => array(
        'pending' => 1,
        'accepted' => 2,
        'rejected' => 3
    ),
    'bidStatus' => array(
        'pending' => -1,
        'accepted' => 1,
        'rejected' => 0,
        'paid'  =>  2,
        'refundable'   =>   3,
        'paid_plus_assigned'=>6
    ),
    'iaStatus' => array(
        'assigned' => 1,
        'completed' => 2,
        'rejected' => 3,

    ),
    'otpStatus' => array(
        'open' => 0,
    ),
    'otpSection' => array(
        'signup' => 0,
    ),
    'roleSaleMapping' => array(
        '1' => 4,
        '2' => 3,
        '3' => 2,

    ),
    'payment'=>[
        'PAY-BID-AMOUNT'    =>  1,
        'PAY-SELLER'        =>  2,
        'PAY-MARGIN'        =>  3,
        'PAY-DA'            =>  4,
        'PAY-IA'            =>  5,
        'REFUND'            =>  6
    ],
    'ia_payment_status'=>[
        'not-needed'        =>  0,
        'pending'           =>  1,
        'initiated'         =>  2,
        'processed'         =>  3,
        'error'             =>  5,
        'details-needed'    =>  4,
    ],
    'da_payment_status'=>[
        'not-needed'        =>  0,
        'pending'           =>  1,
        'initiated'         =>  2,
        'processed'         =>  3,
        'error'             =>  5,
        'details-needed'    =>  4,
    ],
    'margin_payment_status'=>[
        'not-needed'        =>  0,
        'pending'           =>  1,
        'initiated'         =>  2,
        'processed'         =>  3,
        'error'             =>  5,
        'details-needed'    =>  4,
    ],


);
