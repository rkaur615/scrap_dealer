<?php

namespace App\Observers;

use App\Models\BankDetail;
use App\Services\PaymentService;
use Exception;

class BankDetailObserver
{
    public $contactId;
    public $fundAccountId;
    /**
     * Handle the BankDetail "created" event.
     *
     * @param  \App\Models\BankDetail  $bankDetail
     * @return void
     */
    public function creating(BankDetail $bankDetail)
    {

        $ownerable = $bankDetail->ownerable;
        $paymentObj = resolve('razorpay');
        $contact = resolve('razorpay_contact');
        $contact->name = $ownerable->name;
        $contact->email = $ownerable->email;
        $contact->contact = $ownerable->phone_number;
        $contact->type = "employee";
        $contact->reference_id = "d";
        $contact->notes = [
            "notes_key_1"   =>  "Created By Idea-Dcltr Script"
        ];

        $contactResponse = $paymentObj->createContact($contact)->getData();
        if($contactResponse->type == "success"){
            session()->put('contact_id',$contactResponse->id);
            $this->contactId = $contactResponse->id;
        }

        if($this->contactId){

            $fundAccount = [
                "contact_id"=>$this->contactId,
                "account_type"=>"bank_account",
                "bank_account"=>[
                    "name"=>$bankDetail->account_holder_name,
                    "ifsc"=>$bankDetail->ifsc_code,
                    "account_number"=>$bankDetail->account_number,
                ]
            ];
            $fundAccountResponse = $paymentObj->createFundAccount($fundAccount)->getData();
            if($fundAccountResponse->type == "success"){
                $this->fundAccountId = $fundAccountResponse->id;
                session()->put('fund_account_id',$fundAccountResponse->id);
            }
            else{
                // dd($fundAccountResponse);
                throw new Exception($fundAccountResponse->response->error->description);
                //return response()->json($fundAccountResponse);
            }
        }

        //  dump($this->contactId);
        //  dump($this->fundAccountId);

    }
    /**
     * Handle the BankDetail "created" event.
     *
     * @param  \App\Models\BankDetail  $bankDetail
     * @return void
     */
    public function created(BankDetail $bankDetail)
    {
        //
        $ownerable = $bankDetail->ownerable;
        $paymentObj = resolve('razorpay');
        $contact = resolve('razorpay_contact');
        // dump(session()->get('contact_id'));
        // dd($this->fundAccountId);
        $bankDetail->update(['contact_id'=>session()->get('contact_id'), 'fund_account'=>session()->get('fund_account_id'),]);
        /**
         * Update Bank Details Object
         */


    }

    /**
     * Handle the BankDetail "updated" event.
     *
     * @param  \App\Models\BankDetail  $bankDetail
     * @return void
     */
    public function updated(BankDetail $bankDetail)
    {
        //
    }

    /**
     * Handle the BankDetail "deleted" event.
     *
     * @param  \App\Models\BankDetail  $bankDetail
     * @return void
     */
    public function deleted(BankDetail $bankDetail)
    {
        //
    }

    /**
     * Handle the BankDetail "restored" event.
     *
     * @param  \App\Models\BankDetail  $bankDetail
     * @return void
     */
    public function restored(BankDetail $bankDetail)
    {
        //
    }

    /**
     * Handle the BankDetail "force deleted" event.
     *
     * @param  \App\Models\BankDetail  $bankDetail
     * @return void
     */
    public function forceDeleted(BankDetail $bankDetail)
    {
        //
    }
}
