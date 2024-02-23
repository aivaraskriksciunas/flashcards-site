<?php 

namespace App\Services\Authentication;

use App\Enums\UserType;
use App\Models\User;
use App\Exceptions\Auth\IncorrectCredentials;
use Illuminate\Support\Facades\Hash;

class PlainAuthenticator extends BaseAuthenticator {
    
    public function __construct( 
        private User $user
    ) {}

    /**
     * Authenticates given account
     *
     * @return User
     * @throws \App\Exceptions\Auth\IncorrectCredentials
     */
    public function authenticate() : User 
    {
        $this->authenticateUser( $this->user );
        return $this->user;
    }
}