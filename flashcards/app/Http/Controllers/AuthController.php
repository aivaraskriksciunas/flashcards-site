<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Exceptions\Auth\IncorrectCredentials;
use App\Models\User;
use App\Services\Authentication\PasswordAuthenticator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function show_login() 
    {
        return view( 'auth.login' );
    }

    public function login( Request $request ) 
    {
        $creds = $request->validate([
            'email' => [ 'required', 'email' ],
            'password' => [ 'required' ]
        ]);

        $authenticator = new PasswordAuthenticator(
            $creds,
            UserType::ADMIN,
        );

        try {
            $authenticator->authenticate();
        }
        catch ( IncorrectCredentials $e ) {
            return response()->view( 'auth.login', [], 400 );
        }

        return redirect( route( 'home' ) );
    }

    public function logout() 
    {
        Auth::logout();
        return redirect( route( 'login' ) );
    }
}
