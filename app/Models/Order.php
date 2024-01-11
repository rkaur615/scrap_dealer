<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'address_id',
        'bid_id',
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id')->with(['addresses', 'uploads', 'bids' => function($q) {
            $q->select('product_id','bid_amount','status', 'added_by')
            ->where('added_by', auth()->user()->id);
        }])->select('id', 'title', 'description', 'price', 'address_id');
    }

    public function address(){
        return $this->belongsTo(UserAddress::class, 'address_id', 'id')->with('city', 'state', 'country')
        ->select('id', 'address', 'latitude', 'longitude', 'city_id', 'state_id', 'country_id');
    }
}


