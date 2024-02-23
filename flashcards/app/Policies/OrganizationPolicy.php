<?php

namespace App\Policies;

use App\Exceptions\Tier\UnsupportedAction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrganizationPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewMembers( User $user )
    {
        if ( $user->organization == null ) {
            throw new UnsupportedAction();
        }

        if ( !$user->isOrgManager() ) {
            return Response::deny();
        }

        return Response::allow();
    }
}
