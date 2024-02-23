<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\Invitation;
use App\Models\User;
use App\Services\AccountLimiter\AccountLimiter;
use App\Services\AccountLimiter\LimiterAction;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class InvitationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(  )
    {

    }

    public function create( User $user )
    {
        // Ensure user is organization admin or organization manager
        if ( $user->account_type != UserType::ORG_ADMIN && 
            $user->account_type != UserType::ORG_MANAGER 
        )
        {
            return Response::deny();
        }

        // Check if user can create another user
        AccountLimiter::limit( $user, LimiterAction::Create, Invitation::class );

        return Response::allow();
    }
}
