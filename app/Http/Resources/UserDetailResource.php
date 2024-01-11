<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(!empty($this->image)) {
            $this->image = '/storage/images/'.$this->image;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'is_block' => $this->is_block,
            'image' => $this->image,
            'is_pro' => $this->is_pro,
            'types' => $this->types,
            'address' => $this->addresses,
            'usersubscription' => $this->usersubscription,
        ];
    }
}
