<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryDynamicFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            "id" => $this->id,
            "category_id"=>$this->category_id,
            "form_id"=>$this->form_id,
            "forms"=>new DynamicFormMobileResource($this->forms)
            //"form_fields"=> json_decode($this->forms->form_fields)
        ];

    }
}
