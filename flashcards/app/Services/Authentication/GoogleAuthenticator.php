<?php 

namespace App\Services\Authentication;

use App\Exceptions\Auth\GoogleAccountAlreadyExists;
use App\Exceptions\Auth\InvalidGoogleToken;
use Google_Client;
use App\Models\User;

class GoogleAuthenticator extends BaseAuthenticator {
    
    public function __construct( 
        private string $credential 
    ) {}

    public function authenticate() : User {
        $client = new Google_Client([
            'client_id' => env( "GOOGLE_CLIENT_ID" )
        ]); 

        $payload = $client->verifyIdToken( $this->credential );
        if ( !$payload ) {
            throw new InvalidGoogleToken();
        }

        $google_id = $payload['sub'];
        $email = $payload['email'];

        $user = $this->getUserByGoogleId( $google_id );
        if ( $user ) {
            return $this->authenticateUser( $user );
        }
        
        // Try to find this user by email
        $user = User::where( 'email', $email )->first();
        if ( $user ) {
            throw new GoogleAccountAlreadyExists( $email );
        }

        $user = new User();
        $user->google_id = $google_id;
        $user->name = $payload['name'];
        $user->email = $payload['email'];
        $user->save();

        $this->authenticateUser( $user ); 
        return $user;
    }

    private function getUserByGoogleId( string $id )
    {
        return User::where( 'google_id', $id )->first();
    }
}