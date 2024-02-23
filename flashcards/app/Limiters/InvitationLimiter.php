<?php 

namespace App\Limiters;

use App\Exceptions\Tier\UnsupportedAction;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class InvitationLimiter extends Limiter
{
    public function canCreate( User $user ) : bool
    {
        $organization = $user->organization;

        if ( !$organization )
        {
            throw new UnsupportedAction();
        }

        // Get number of members and invitations in organization
        $members = $organization->users()->count();
        $invitations = $organization->getValidInvitations()->count();

        return $members + $invitations < config( 'tiers.default.organizations.users' );
    }

    public function canUndelete( User $user, Invitation $model ): bool
    {
        return $this->canCreate( $user );
    }
}