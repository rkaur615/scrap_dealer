<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $perPage = 5;

    protected $fillable = [
        'service_name',
        'added_by',
    ];


    public function user(){
        return $this->belongsTo(User::class,'added_by');
    }
}
