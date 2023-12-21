<?php 

namespace App\Services\Authentication;

use App\Models\User;
use App\Exceptions\Auth\IncorrectCredentials;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordAuthenticator extends BaseAuthenticator {
    
    public function __construct( 
        private array $credentials,
        private string $accountType = '',
    ) {}

    public function authenticate() : User 
    {
        // Get parent account
        $user = User::where( 'email', $this->credentials[ 'email' ] )->first();
        $account = $user;
        // If a specific account type was specified, retrieve it
        if ( $this->accountType !== '' ) 
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