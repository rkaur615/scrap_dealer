<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;
    
    protected $fillable = [
    
        'user_id',
        'request_id',
        'description',
        'status',
        'type',
    ];

    public function services(){
        return $this->hasOne(ServiceRequest::class, 'id', 'request_id')->with('uploads')
        ->select('id','title','description','status');
    }
    public function products(){
        return $this->hasOne(Product::class, 'id', 'request_id')->with('uploads')
        ->select('id','title','description', 'price');
    }
    public function provider_name(){
        return $this->hasMany(User::class , 'id' ,'user_id');
    }

}   

