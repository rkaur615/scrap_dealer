<?php

namespace App\Policies;

use App\Models\Requirement;
use App\Models\SupplierRequirement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuotePolicy
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


    public function view(User $user, SupplierRequirement $supplierRequirement){
        return $user->id===$supplierRequirement->user_id || $user->id===$supplierRequirement->requirement->user_id;
    }
}
