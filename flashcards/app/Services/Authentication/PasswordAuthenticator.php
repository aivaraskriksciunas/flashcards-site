<?php 

namespace App\Services\Authentication;

use App\Enums\UserType;
use App\Models\User;
use App\Exceptions\Auth\IncorrectCredentials;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordAuthenticator extends BaseAuthenticator {
    
    public function __construct( 
        private array $credentials,
        private UserType|null $accountType = null,
    ) {}

    /**
     * Authenticates user with the provided credentials
     *
     * @return User
     * @throws \App\Exceptions\Auth\IncorrectCredentials
     */
    public function authenticate() : User 
    {
        // Get parent account
        $user = User::where( 'email', $this->credentials[ 'email' ] )->first();
        $account = $user;
        // If a specific account type was specified, retrieve it
        if ( $this->accountType !== null ) 
        {
            $account = $this->getSubaccountByType( $user );
        }

        if ( 
            !$user || 
            !$account ||
            $user->password == null ||
            !Hash::check( $this->credentials['password'], $user->password ) 
        ) {
            session()->regenerate();

            throw new IncorrectCredentials();
        }

        $this->authenticateUser( $account );
        return $account;
    }

    private function getSubaccountByType( User $user )
    {
        $accounts = $user->getAllAccounts()->filter( function ( $account ) {
            return $account->account_type == $this->accountType;
        });

        return $accounts->first();
    }
}