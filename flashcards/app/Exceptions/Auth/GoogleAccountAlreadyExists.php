<?php

namespace App\Exceptions\Auth;

use Exception;

class GoogleAccountAlreadyExists extends Exception
{
    public function __construct( 
        private string $email,
    ) {}
    
    public function render( $request )
    {
        return response()->json([
            'message' => "A user with this email ({$this->email})) already exists.",
            'email' => $this->email,
            'required_action' => 'link_google_account',
        ], 400 );
    }
}
