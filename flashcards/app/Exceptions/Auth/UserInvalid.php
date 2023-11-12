<?php

namespace App\Exceptions\Auth;

use Exception;

class UserInvalid extends Exception
{
    public function render( $request )
    {
        return response()->error( 
            'You may not perform this action as your email has not been verified yet.', 
            required_action: 'verify_email' 
        );
    }
}
