<?php 

namespace App\Services\Authentication;

use App\Exceptions\Auth\AccountDoesNotExist;
use App\Models\User;
use App\Exceptions\Auth\IncorrectCredentials;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountSwitchAuthenticator extends BaseAuthenticator {
    
    public function __construct( 
        private User $currentUser,
        private string $targetUserId,
    ) {}

    public function authenticate() : User 
    {
        $targetUser = User::where( 'id', $this->targetUserId )->first();

        if ( !$targetUser ) {
            throw new AccountDoesNotExist();
        }

        $account = $this->currentUser
            ->getAllAccounts()
            ->first( function( User $account ) use ( $targetUser ) {
                return $account->id == $targetUser->id;
            }
        );

        if ( !$account ) {
            throw new AccountDoesNotExist();
        }

        $this->authenticateUser( $account );
        return $account;
    }
}