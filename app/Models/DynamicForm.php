<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicForm extends Model
{
    use HasFactory;

    protected $guarded = ['id'], $perPage = 5;


    public function categories(){
        return $this->hasMany(CategoryDynamicForm::class, 'form_id');
    }
}
