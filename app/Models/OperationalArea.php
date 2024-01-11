<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationalArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_company_id',
        'city_id',
        'state_id'
    ];

    public function city(){
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function state(){
        return $this->belongsTo(State::class, 'state_id', 'id')
        ->select(['id','name']);

    }

}
