<?php

namespace App\Exceptions\Account;

use Exception;

class AccountAlreadyInOrganization extends Exception
{
    public function render( $request )
    {
        return response()->error( 
            'This user is already part of your organization.'
        );
    }
}
