<?php

namespace App\Http\Controllers;

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
            User::USER_ADMIN,
        );

        $authenticator->authenticate();

        return redirect( route( 'home' ) );
    }

    public function logout() 
    {
        Auth::logout();
        return redirect( route( 'login' ) );
    }
}
