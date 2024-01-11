<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierRequirement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'quote'=>'object'
    ];
    protected $attributes = [
        'quote' => '{}'
    ];

    public function supplier(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requirement(){
        return $this->belongsTo(Requirement::class, 'requirement_id');
    }

    public function qstatus(){
        return array_flip(config('constants.supplierRequirementStatus'))[$this->status];
    }

    public static function getSomeClosure($param) // might be static or instance method
    {
        return function ($query) use ($param) {
            $query->where('id', $param);
        };
    }

    public function squotes(){
        return $this->hasMany(SupplierQuote::class);
    }

}
