<?php

namespace App\Http\Controllers;

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

        if ( !Auth::attempt( $creds ) )
        {
            $request->session()->regenerate();

            return view( 'auth.login' );
        }

        // Update last login time
        $user = Auth::user();
        $user->last_login = Carbon::now();
        $user->save();

        return redirect( route( 'home' ) );
    }

    public function logout() 
    {
        Auth::logout();
        return redirect( route( 'login' ) );
    }
}
