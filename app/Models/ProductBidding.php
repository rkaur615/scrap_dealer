<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBidding extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'bid_amount',
        'total_amount',
        'charges',
        'added_by',
    ];
    protected $appends = ['statusText'];

    protected function getStatusTextAttribute()
    {
        return array_flip(config('constants.bidStatus'))[$this->status];
    }

    public function user(){
        return $this->belongsTo(User::class,"added_by");
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function products(){
        return $this->hasOne(Product::class, 'id', 'product_id')->with('uploads')
        ->select('id','title','description', 'price');
    }
}
