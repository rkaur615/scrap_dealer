<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'rating_value',
        'description',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id')
        ->select('id', 'name');
    }
}
