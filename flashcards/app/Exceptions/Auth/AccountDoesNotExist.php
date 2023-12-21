<?php

namespace App\Exceptions\Auth;

use Exception;

class AccountDoesNotExist extends Exception
{    
    public function render( $request )
    {
        return response()->error( 'Account does not exist.', 404 );
    }
}
