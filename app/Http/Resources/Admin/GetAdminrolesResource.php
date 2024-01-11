<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Null_;

class GetAdminrolesResource extends JsonResource
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
            'permission_id' => (isset($this->permission->id)) ? json_decode($this->permission->id) : null,
            'permission' => (isset($this->permission->permissions)) ? json_decode($this->permission->permissions) : null
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }
}
