<?php

namespace App\Exceptions\Auth;

use Exception;

class InvalidCaptcha extends Exception
{
    public function __construct( 
        private string $reason = '',
    ) {}

    public function render( $request )
    {
        $error = 'Captcha validation failed. ';
        if ( $this->reason ) {
            $error .= "Reason: " . $this->reason;
        }
        else {
            $error .= "Please refresh the page and try again.";
        }
        return response()->error( $error );
    }
}
