<?php

namespace App\Exceptions\Auth;

use Exception;

class EmailAlreadyExists extends Exception
{
    public function render( $request )
    {
        return response()->error( 'Email already taken.', 401 );
    }
}
