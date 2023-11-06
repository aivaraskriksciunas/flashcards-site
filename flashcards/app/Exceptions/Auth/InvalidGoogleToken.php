<?php

namespace App\Exceptions\Auth;

use Exception;

class InvalidGoogleToken extends Exception
{
    public function render( $request )
    {
        return response()->error( 'Your Google token is invalid. Please refresh the page and try again.' );
    }
}
