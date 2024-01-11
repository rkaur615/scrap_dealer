<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'filepath',
        'filetype',
        'ref_id',
    ];
    // protected $appends = ['resourceUrl'];
//    protected $appends = ['fileTypeText'];


    // protected function getFileTypeTextAttribute()
    // {
    //     if (isset($this->filetype)) {
    //         return array_flip(config('constants.fileTypes'))[$this->filetype];
    //     }
    // }


    protected function getResourceUrlAttribute()
    {
        // $folder = collect(config('constants.fileTypes'))->filter(function($v, $k){ return $v == $this->filetype; })->keys()->first();
        return $this->filepath;
        // return Attribute::make(
        //     get: fn($value) =>  $folder.'/'.$value
        // );
    }
}
