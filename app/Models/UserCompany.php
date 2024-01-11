<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'category_id',
        'subcategory_id',
        'user_type_id',
        'user_id',
        'is_agency'
    ];

    public function references()
    {
        return $this->hasMany("App\Models\Reference")->select(['person_name', 'phone_number', 'user_company_id']);
    }

    public function uploads()
    {
        return $this->hasMany("App\Models\Upload", 'ref_id')->where('filetype',1)->select(['filename', 'filetype', 'ref_id', 'filepath']);
    }

    public function categories()
    {
        return $this->hasMany("App\Models\UserCategory")->select(['category_id', 'subcategory_id', 'user_company_id']);
    }

    public function operationAreas()
    {
        return $this->hasMany("App\Models\OperationalArea")->with('state')
        ->select(['city_id','state_id', 'user_company_id']);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function state(){
        return $this->belongsTo(State::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function userType(){
        return $this->belongsTo(UserType::class,'user_type_id');
    }
}
