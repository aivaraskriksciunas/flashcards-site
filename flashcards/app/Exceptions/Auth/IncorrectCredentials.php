<?php

namespace App\Exceptions\Auth;

use Exception;

class IncorrectCredentials extends Exception
{
    public function render( $request )
    {
        return response()->error( 'Incorrect email or password.' );
    }
}
