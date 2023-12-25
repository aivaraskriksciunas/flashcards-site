<?php

namespace App\Exceptions\Account;

use Exception;

class OrganizationAlreadyAssigned extends Exception
{
    public function render( $request )
    {
        return response()->error( 
            'This account already is assigned to an organization. Add a new subaccount if you want to manage another organization.'
        );
    }
}
