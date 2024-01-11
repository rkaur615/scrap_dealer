<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceRequestMobileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            "id" => $this->id,
            "title" => $this->title,
            "user_id" => $this->user_id,
            "description" => $this->description,
            "time_slots" => json_decode($this->time_slots),
            "category_id" => $this->category_id,
            "subcategory_id" => $this->subcategory_id,
            "category" => $this->category,
            "subcategory" => $this->subcategory,
            "address" => $this->address,
            "user"=> $this->user,
            "status"=> $this->status,
            "statusText"=> $this->statusText,
            "status_reason"=> $this->status_reason,
            "uploads"=> UploadsResource::collection($this->uploads),
            "formdata" => new CategoryFormDataResource($this->formdata),
        ];
    }
}
