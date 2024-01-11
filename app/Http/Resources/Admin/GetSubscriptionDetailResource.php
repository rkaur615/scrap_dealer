<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GetSubscriptionDetailResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'amount' => $this->amount,
            'no_of_leads' => $this->no_of_leads,
            'is_lead_carry_over' => $this->is_lead_carry_over,
            'days' => $this->days,
            'users' => $this->whenNotNull(UserResource::collection($this->whenNotNull($this->users)))
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }
}
