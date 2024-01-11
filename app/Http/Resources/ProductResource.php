<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            "price" => $this->price,
            "user_id" => $this->user_id,
            "my_id" => auth()->user()->id,
            "description" => $this->description,
            "sale_option_id" => $this->sale_option_id,
            "time_slots" => json_decode($this->time_slots),
           // "address_id" => $this->address_id,
            "is_belongs_to_me" => $this->bids()?->where('added_by',auth()->user()->id)->count()?true:false,
            "ia" => $this->ia()?->get(),
            "da" => $this->da()?->get(),
            "bids" => $this->bids()?->where('added_by',auth()->user()->id)->get(),
            "bids_for_admin" => $this->bids()?->with('user')->get(),
            "category_id" => $this->category_id,
            "saleOptionText" => $this->saleOptionText,
            "category" => $this->category,
            "subcategory" => $this->subcategory()->with('form')->first(),
            "address" => $this->addresses,
            "ratings" => $this->ratings,
            "is_favourite"=> $this->Favourite()->where('product_id',$this->id)->count()?true:false,
            "created_at"=> $this->created_at,
            "updated_at"=> $this->updated_at,
            //"date"=> $this->created_at->format('M, d Y H:i:s A'),
            "user"=> $this->user,
            "status"=> $this->status,
            "statusText"=> $this->statusText,
            "uploads"=> UploadsResource::collection($this->uploads),
            "admin_formdata"=> $this->formdata,
            "formdata" => new CategoryFormDataResource($this->formdata),
        ];
    }
}
