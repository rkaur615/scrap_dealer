<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\CreateContact;
use App\Services\Helpers\Contact;
use Illuminate\Support\Facades\Http;

class PaymentService
{

    public $baseRazorUrl = "https://api.razorpay.com/v1/";
    public function __construct(public $token, public $secret){}

    protected function setUp()
    {

    }

    protected function sendPostRequest($endPoint, $data){
        $url = $this->baseRazorUrl . $endPoint;
        return Http::withBasicAuth($this->token, $this->secret)->post($url,$data);
    }

    protected function sendPatchRequest($endPoint, $data){
        $url = $this->baseRazorUrl . $endPoint;
        return Http::withBasicAuth($this->token, $this->secret)->patch($url,$data);
    }

    public function createContact(Contact $contact)
    {
        $endPoint = "contacts";
        $contactData =  $contact->toArray();
        $contactDetails = $this->sendPostRequest($endPoint,$contactData);

        $response = $contactDetails->json();
        if(array_key_exists("error", $response)){
            return response()->json(["type"=>"error","response"=>$response]);
        };
        $contactId = $contactDetails->json()['id'];
        $endPoint .= "/".$contactId;
        $this->sendPatchRequest($endPoint,["active"=>true]);
        return response()->json(["type"=>"success","id"=>$response['id']]);


    }

    public function createFundAccount($data)
    {
        $endPoint = "fund_accounts";
        $fundAccountData =  $data;
        $fundAccountDetails = $this->sendPostRequest($endPoint,$fundAccountData);
        $response = $fundAccountDetails->json();
        if(array_key_exists("error", $response)){
            return response()->json(["type"=>"error","response"=>$response]);
        };

        $fundAccId = $fundAccountDetails->json()['id'];
        $endPoint .= "/".$fundAccId;
        $this->sendPatchRequest($endPoint,["active"=>true]);
        return response()->json(["type"=>"success","id"=>$response['id']]);
    }

    public function makeBankPayment()
    {
        $op = Http::withBasicAuth($this->token, $this->secret)->post("https://api.razorpay.com/v1/payouts",[

            "account_number"=> "2323230021640185",
            "fund_account_id"=> "fa_JkWyVoR7O7W5oG",
            "amount"=> 200,
            "currency"=> "INR",
            "mode"=> "IMPS",
            "purpose"=> "refund",
            "queue_if_low_balance"=> true,
            "reference_id"=> "Acme Transaction ID 12345",
            "narration"=> "Acme Corp Fund Transfer",
            "notes"=> [
              "notes_key_1"=>"Tea, Earl Grey, Hot",
            ]
    ]);
    }

    public function makePayment()
    {

    }

    public function saveTransaction(){

    }


}
