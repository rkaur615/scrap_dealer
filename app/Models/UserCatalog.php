<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCatalog extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function title(){
        return $this->belongsTo(ProductTitle::class);
    }

    public function cat(){
        return $this->belongsTo(Category::class, 'category');
    }

    public function images(){
        return $this->hasMany("App\Models\Upload", 'ref_id')->where('filetype',2)->select(['filename', 'filetype', 'ref_id', 'filepath']);
    }
}
