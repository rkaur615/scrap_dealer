<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryDynamicForm extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function forms(){
        return $this->hasOne(DynamicForm::class, 'id', 'form_id')->select(['id', 'title', 'form_fields']);
    }
}
