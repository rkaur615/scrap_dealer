<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveTransactionRequest extends FormRequest
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
                'product_id' => 'required',
                'transaction_status' => 'required',                   
            ];
        }
        public function messages()
        {
            return [
                'transaction_id.required' => 'Transaction Id is required!',          
                'product_id.required' => 'Product Id is required!',
                'transaction_status.required' => 'Transaction Status is required!',               
            ];
        }
    
}
