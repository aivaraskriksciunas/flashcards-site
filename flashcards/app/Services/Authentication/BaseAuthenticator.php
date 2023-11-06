<?php

namespace App\Services\Authentication;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

abstract class BaseAuthenticator {

    abstract public function authenticate() : User;

    public function authenticateUser( User $user ) : User
    {
        if ( Auth::user() != $user ) {
            Auth::login( $user );
        }

        $user->last_login = Carbon::now();
        $user->save();
        
        return $user;
    }

}