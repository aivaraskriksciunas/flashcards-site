<?php

namespace App\Exceptions\Tier;

use Exception;

class UnsupportedAction extends Exception
{
    public function render( $request )
    {
        return response()->error( 'Your account does not support this action.', 403 );
    }
}
