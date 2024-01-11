<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavePaymentDetailRequest extends FormRequest
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
            'order_id' => 'required',
            'transaction_id' => 'required',
            'amount' => 'required',
            'payment_method' => 'required',
            'payment_status' => 'required', 
        ];
    }
    public function messages()
    {
        return [
            'order_id.required' => 'Order Id is required!',
            'transaction_id.required' => 'Transaction Id is required!',
            'amount.required' => 'Amount is required!',
            'payment_method.required' => 'Payment Method is required!',
            'payment_status.required' => 'Payment Status is required!',               
        ];
    }
}
