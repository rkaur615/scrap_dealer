<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryFormData extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_dynamic_form_id',
        'data',
        'type_id',
        'type'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
