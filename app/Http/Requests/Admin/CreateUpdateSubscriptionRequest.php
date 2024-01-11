<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateSubscriptionRequest extends FormRequest
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
            'name' => 'required|unique:subscription_plans,name,'. $this->subscription->id,
            'amount' => 'required',
            'no_of_leads' => 'required',
            'is_lead_carry_over' => 'required',
            'days' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
           'name.required' => 'Please enter plan title',
           'name.unique' => 'Plan title already exists',
        ];
    }
}
