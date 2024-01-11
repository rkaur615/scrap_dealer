<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserCatalog;
use Illuminate\Auth\Access\HandlesAuthorization;

class CatalogPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //

    }

    public function viewCatalog(User $user, UserCatalog $userCatalog){
        return $user->id == $userCatalog->user_id;
    }
}
