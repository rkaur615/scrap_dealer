<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'sender_type',
        'reciever_id',
        'reciever_type',
        'section',
        'section_id',
        'otp',
        'status'
    ];
}
