<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketCatRequest extends FormRequest
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
            'name' => 'required|unique:ticket_categories,name,'. $this->ticketCategory->id,
        ];
    }

    public function messages()
    {
        return [
           'name.required' => 'Please enter category title',
           'name.unique' => 'category name already exists',
        ];
    }
}
