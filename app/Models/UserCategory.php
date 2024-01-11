<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_company_id',
        'category_id',
        'subcategory_id'
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function subcategory(){
        return $this->belongsTo(Category::class, 'subcategory_id', 'id');
    }

    public function userCompany()
    {
        return $this->belongsTo(UserCompany::class);
    }
}
