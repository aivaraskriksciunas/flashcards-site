<?php

namespace App\Exceptions\Auth;

use Exception;

class ConfirmationEmailAlreadySent extends Exception
{
    public function render( $request )
    {
        $email_interval = env( 'CONFIRMATION_EMAIL_INTERVAL_MINS' );
        $email_interval .= $email_interval == 1 ? ' minute' : ' minutes';
        return response()->error( "Confirmation email already sent. Please wait $email_interval before resending.", 401 );
    }
}
