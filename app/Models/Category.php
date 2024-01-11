<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'parent_id',
        'category_type',
        'image_path',
        'image_title',
    ], $perPage = 5;

    // protected function getCategoryTypeAttribute($value){
    //     $types =  config('constants.category');
    //     $exsisted = json_decode($value);
    //     if(is_array($exsisted)){
    //         return array_intersect($types, $exsisted) ;
    //     }
    //     else if($value){
    //         return isset(array_flip($types)[$value])?[array_flip($types)[$value]=>$value]:[];
    //     }
    //     else{
    //         return array_flip($types);
    //     }

    // }

    public function parent()
    {
        return $this->belongsTo('static::class','parent_id')->where('parent_id',null);
    }
    //each category might have multiple children
    public function subcategories() {
        return $this->hasMany(static::class, 'parent_id')->selectRaw('id, title, parent_id, category_type');
    }

    public function childrenCat() {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function form(){
        return $this->hasOneThrough(DynamicForm::class, CategoryDynamicForm::class, 'category_id', 'id', 'id', 'form_id');
    }

}
