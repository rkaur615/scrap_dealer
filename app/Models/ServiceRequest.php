<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'check_proximity',
        'distance',
        'old_service_provider_id',
        'date',
        'start_time',
        'end_time',
        'category_id',
        'subcategory_id',
        'type',
        'status',
        'status_reason'
    ], $perPage =5;

    protected $appends = ['statusText'];

    protected function getStatusTextAttribute()
    {
        return array_flip(config('constants.responseStatus'))[$this->status];
    }


    public function formdata(){
        return $this->hasOne(CategoryFormData::class,'type_id')->where('type','service');
    }

    public function uploads()
    {
        return $this->hasMany("App\Models\Upload", 'ref_id')->select(['filename', 'filetype', 'ref_id', 'filepath']);
    }

    public function responses()
    {
        return $this->hasMany("App\Models\Response", 'request_id');
    }


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function subcategory(){
        return $this->belongsTo(Category::class, 'subcategory_id', 'id');
    }

    public function address(){
        return $this->belongsTo(UserAddress::class, 'address_id')
        ->select('id', 'address');
    }
}