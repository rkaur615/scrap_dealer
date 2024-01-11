<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UserRegisterRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'first_name' => 'required|string',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6'
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
            'email.required' => 'Email is required!',
            'email.unique' => 'Email already Exist!',
            'first_name.required' => 'Name is required!',
            'password.required' => 'Password is required!',
            'password.min' => 'Password should incude atleast 6 characters',
            'password_confirmation.same'=>'Confirm Password should be same as Password'
        ];
    }
}
