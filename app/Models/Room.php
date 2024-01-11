<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['messages'];

    protected $casts = [
        'users'=>'array'
    ];


    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function getLastMessage(){
        return $this->messages()->latest()->limit(1);
    }

    public function other(){
        if($this->users && count(array_diff($this->users,[auth()->user()->id]))>0){
            $id = current(array_slice(array_diff($this->users,[auth()->user()->id]), 0, 1));

            return User::find($id);
        }
        else{
            return null;
        }

    }
}
