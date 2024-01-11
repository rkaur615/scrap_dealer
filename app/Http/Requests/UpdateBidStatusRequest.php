<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBidStatusRequest extends FormRequest
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
            'bid_id' => 'required',
            'status' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'bid_id.required' => 'Bid Id is required!',
            'status.required' => 'Status is required!',
        ];
    }
}
