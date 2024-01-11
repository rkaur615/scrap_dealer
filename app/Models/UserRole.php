<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type_id'
    ];

    protected $hidden = ["created_at", "updated_at"];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(UserType::class, 'user_type_id', 'id');
    }

    public function company(){
        return $this->hasOne(UserCompany::class, 'user_type_id', 'user_type_id');
    }
}
