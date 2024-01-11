<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequirementItem extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function quotes(){
        return $this->hasMany(SupplierQuote::class, 'requirement_item_id', 'id');
    }
}
