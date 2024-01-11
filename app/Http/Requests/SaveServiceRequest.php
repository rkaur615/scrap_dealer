<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveServiceRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
         ];
    }
    public function messages()
    {
        return [
           'title.required' => 'Title is required!',
           'description.required' => 'Description is required!',           
           'category_id.required' => 'Cateory Id is required!',
           'subcategory_id.required' => 'SubCateory Id is required!',
        ];
    }
}
