<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'transaction_id' => 'required',
            'subscription_plan_id' => 'required',
            'no_of_leads' => 'required', 
            'is_lead_carry_over' => 'required', 
            'amount' => 'required', 
        ];
    }
    public function messages()
    {
        return [
            'transaction_id.required' => 'Transaction Id is required!',          
            'subscription_plan_id.required' => 'Subscription Plan Id is required!',
            'no_of_leads.required' => 'No Of Leads is required!',   
            'is_lead_carry_over.required' => 'Is Lead Carry Over is required!',               
            'amount.required' => 'Amount is required!',
        ];
    }
}
