<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
     ];

    protected $fillable = [
        'title',
        'price',
        'description',
        'sale_option_id',
        'user_id',
        'status',
        'pickup_address',
        'time_slots',
        'address_id',
        'category_id',
        'subcategory_id'
    ], $perPage=5;
    protected $appends = ['saleOptionText','statusText'];

    protected function getStatusTextAttribute()
    {
        if (isset($this->status)) {
            return array_flip(config('constants.responseStatus'))[$this->status];
        }
    }

    protected function getSaleOptionTextAttribute()
    {
        if (isset($this->sale_option_id)) {
            return array_flip(config('constants.sellOptions'))[$this->sale_option_id];
        }
    }
    public function uploads()
    {
        return $this->hasMany("App\Models\Upload", 'ref_id')->select(['filename', 'filetype', 'ref_id', 'filepath']);
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function bids(){
        return $this->hasMany(ProductBidding::class, "product_id");
    }

    public function owner_name(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function formdata(){
        return $this->hasOne(CategoryFormData::class,'type_id')->where('type','product');
    }

    public function product_address(){
        return $this->hasOne(Useraddress::class,'id','address_id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function subcategory(){
        return $this->belongsTo(Category::class, 'subcategory_id', 'id');
    }

    public function addresses(){
        return $this->belongsTo(UserAddress::class, 'address_id', 'id')->with('city', 'state', 'country')
        ->select('id', 'address', 'latitude', 'longitude');
    }

    public function ratings(){
        return $this->hasMany(ProductRating::class, 'product_id','id')->with('user')
        ->select('id', 'user_id','product_id','rating_value','description');
    }

    public function Favourite(){
        return $this->hasMany(Favourite::class,'product_id');
    }

    /**
     * Inspection Agent
     */
    public function ia(){
        return $this->belongsTo(Admin::class, 'inspection_agent');
    }

    /**
     * Delivery Agent
     */
    public function da(){
        return $this->belongsTo(Admin::class, 'delivery_boy');
    }

    function scopeWhereSaleOption($query, $value) {
        $query->where(\DB::raw('concat(firstname, " ", lastname)'), 'LIKE', "%{$value}%");
    }

}
