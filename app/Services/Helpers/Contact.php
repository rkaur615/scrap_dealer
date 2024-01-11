<?php

namespace App\Services\Helpers;

use App\Services\Contracts\CreateContact;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class Contact extends EloquentCollection{

    protected $attrs = [];
    public function __set($key, $value){

        $this->attrs[$key] = $value;
    }

    public function toArray(){
        return $this->attrs;
    }
}
