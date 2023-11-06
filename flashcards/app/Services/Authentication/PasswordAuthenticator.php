<?php 

namespace App\Services\Authentication;

use App\Models\User;
use App\Exceptions\Auth\IncorrectCredentials;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordAuthenticator extends BaseAuthenticator {
    
    public function __construct( 
        private array $credentials 
    ) {}

    public function authenticate() : User 
    {
        $user = User::where( 'email', $this->credentials[ 'email' ] )->first();
        if ( 
            !$user || 
            $user->password == null ||
            !Hash::check( $this->credentials['password'], $user->password ) 
        ) {
            session()->regenerate();

            throw new IncorrectCredentials();
        }

        $this->authenticateUser( $user );
        return $user;
    }
}