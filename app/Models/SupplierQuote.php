<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierQuote extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function supplier_requirement(){
        return $this->belongsTo(SupplierRequirement::class,'supplier_requirement_id');
    }

    public function item(){
        return $this->belongsTo(RequirementItem::class,'requirement_item_id');
    }

}
