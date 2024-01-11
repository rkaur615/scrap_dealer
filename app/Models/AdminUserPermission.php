<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUserPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_role_id', 'permissions'
    ];
}
