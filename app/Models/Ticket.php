<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'priority',
        'message',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
