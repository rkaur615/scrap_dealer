<?php

namespace App\Http\Helpers;

use App\Models\User;

class Helper
{
    public static function myRole(int $userid)
    {
        return $role = User::find($userid)->role();
    }
    public static function myRoleVerb(int $userid)
    {
        return $role = ucwords(array_flip(config('constants.userRoles'))[User::find($userid)->role()->user_type_id]);
    }
    public static function isSupplier(int $userid)
    {
        return User::find($userid)->role()->user_type_id==1;
    }
    public static function isRetailer(int $userid)
    {
        return User::find($userid)->role()->user_type_id==2;
    }
}
