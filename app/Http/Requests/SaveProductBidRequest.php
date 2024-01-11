<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveProductBidRequest extends FormRequest
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
            'product_id' => 'required',
            'bid_amount' => 'required',
            'total_amount' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'product_id.required' => 'Product Id is required!',
            'bid_amount.required' => 'Bid Amount is required!',
            'total_amount.required' => 'Total Amount is required!',           
        ];
    }
}
