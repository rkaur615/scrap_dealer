<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveOrderRequest extends FormRequest
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
            'address_id' => 'required',
            'bid_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'product_id.required' => 'Product Id is required!',
            'address_id.required' => 'Address is required!',
            'bid_id.required' => 'Bid Id  is required!',
        ];
    }
}
