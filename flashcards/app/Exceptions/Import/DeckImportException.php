<?php

namespace App\Exceptions\Import;

use Exception;

class DeckImportException extends Exception
{
    public function __construct( 
        protected $message = 'An error has occurred while parsing your deck.' 
    ) {}

    public function render( $request )
    {
        return response()->error( $this->message );
    }
}
