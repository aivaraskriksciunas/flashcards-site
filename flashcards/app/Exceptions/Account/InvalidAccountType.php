<?php

namespace App\Exceptions\Account;

use Exception;

class InvalidAccountType extends Exception
{
    public function render( $request )
    {
        return response()->error( 
            'Account has unknown type',
            401,
            required_action: 'select-account-type'
        );
    }
}
