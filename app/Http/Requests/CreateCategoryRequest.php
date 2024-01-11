<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
            'title' => ['required', Rule::unique('categories')],
            'image' => ['required'],
            'category_type' => 'required_without:parent_id',
            'parent_id' => 'required_without:category_type',
        ];
    }

    public function validator($factory)
    {
        return $factory->make(
            $this->sanitize(), $this->container->call([$this, 'rules']), $this->messages()
        );
    }

    public function sanitize()
    {
        // $this->merge([
        //     'category_type' => json_encode(array_map('intval', explode(',',$this->input('category_type'))))
        // ]);
        return $this->all();
    }

    public function messages()
    {
        return [
            'title.required' => 'Please enter category title',
            'title.unique' => 'Category already exists',
        ];
    }
}
