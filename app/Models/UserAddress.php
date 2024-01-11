<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'city_id',
        'state_id',
        'country_id',
        'pin',
        'user_id',
        'time_slots',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'time_slots'=>'object'
    ];

    public function city(){
        return $this->belongsTo(City::class, "city_id");
    }


    public function state(){
        return $this->belongsTo(State::class, "state_id");
    }


    public function country(){
        return $this->belongsTo(Country::class, "country_id");
    }


}
