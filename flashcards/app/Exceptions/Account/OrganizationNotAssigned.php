<?php

namespace App\Exceptions\Account;

use Exception;

class OrganizationNotAssigned extends Exception
{
    public function render( $request )
    {
        return response()->error( 
            'Account does not have an organization attached.',
            required_action: 'register-organization'
        );
    }
}
