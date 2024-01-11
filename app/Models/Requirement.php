<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'items'=>'object',
        'categories'=>'object',

    ];

    // protected $appends = ['categories'];

    protected $dates = ['expected_date'];


    // public function title(){
    //     return $this->belongsTo(ProductTitle::class);
    // }

    function getExpectedDateAttribute()
    {
        return Carbon::parse($this->attributes['expected_date'])->format('d/m/Y');
        //return ->format();
    }

    public function retailer(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products(){
        return $this->belongsTo(ProductTitle::class, 'items->code', 'id');
    }

    public function cat(){
        return $this->belongsTo(Category::class, 'categories');
    }

    public function cats(){
        return Category::whereIn('id',$this->categories);
    }

    public function images(){
        return $this->hasMany("App\Models\Upload", 'ref_id')->where('filetype',2)->select(['filename', 'filetype', 'ref_id', 'filepath']);
    }

    public function myquote(){
        return $this->hasMany(SupplierRequirement::class, 'requirement_id');
    }

    public function quotes(){
        return $this->hasMany(SupplierRequirement::class, 'requirement_id');
    }

    public function qitems(){
        return $this->hasMany(RequirementItem::class, 'requirement_id');
    }

}
