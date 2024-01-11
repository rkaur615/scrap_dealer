<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserBankDetailRequest extends FormRequest
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
            'bank_name'  => 'required',
            'account_number'  => 'required',
            'ifsc_code'  => 'required',
            'address' => 'required',
            'account_holder_name' => 'required',
            'user_id' => 'required|integer'
        ];
    }
}
