<?php

namespace App\Exceptions\Account;

use Exception;

class StudentAccountAlreadyExists extends Exception
{
    public function render( $request )
    {
        return response()->error( 'A student account for this user already exists.' );
    }
}
