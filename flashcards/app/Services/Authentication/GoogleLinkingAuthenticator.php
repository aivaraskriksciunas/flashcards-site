<?php 

namespace App\Services\Authentication;

use App\Exceptions\Auth\AccountDoesNotExist;
use App\Exceptions\Auth\IncorrectCredentials;
use Google_Client;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\Auth\InvalidGoogleToken;

class GoogleLinkingAuthenticator extends BaseAuthenticator {
    
    public function __construct( 
        private string $password,
        private string $email,
        private string $credential,
    ) {}

    public function authenticate() : User 
    {
        $client = new Google_Client([
            'client_id' => env( "GOOGLE_CLIENT_ID" )
        ]); 

        $payload = $client->verifyIdToken( $this->credential );
        if ( !$payload ) {
            throw new InvalidGoogleToken();
        }

        $user = User::where( 'email', $this->email )->first();
        if ( $user == null || $payload['email'] != $this->email ) {
            throw new AccountDoesNotExist();   
        }

        if ( !Hash::check( $this->password, $user->password ) ) {
            throw new IncorrectCredentials();
        }

        $user->google_id = $payload['sub'];
        $user->save();
        return $this->authenticateUser( $user );
    }
}