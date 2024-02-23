<?php

namespace App\Exceptions\Tier;

use Exception;

class AccountLimitReached extends Exception
{
    public function render( $request )
    {
        return response()->error( $this->message, 403 );
    }
}
