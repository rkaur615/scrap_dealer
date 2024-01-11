<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserVerifyRequest extends FormRequest
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
            'phone_number' => 'required|numeric|digits:10',
            'verification_code' => 'required|digits:4'
        ];
    }
    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'phone_number.required' => 'Phone number is required!',
            'verification_code.required' => 'Verification code is required!',
            'verification_code.digits' => 'Only 4 digits are allowed in verification code',
        ];
    }
}
