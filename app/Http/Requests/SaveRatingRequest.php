<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveRatingRequest extends FormRequest
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
            'rating_value' => 'required',
            'description' => 'required', 
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Product Id is required!',
            'rating_value.required' => 'Rating Value is required!',
            'description.required' => 'Description is required!',

        ];
    }
}
