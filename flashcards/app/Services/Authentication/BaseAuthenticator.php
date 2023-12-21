<?php

namespace App\Services\Authentication;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

abstract class BaseAuthenticator {

    abstract public function authenticate() : User;

    protected function authenticateUser( User $user ) : User
    {
        // Only use Auth for web stateful routes
        if ( request()->route()->getPrefix() !== 'api' ) {
            Auth::login( $user );
        }

        $user->last_login = Carbon::now();
        $user->save();
        
        return $user;
    }

}