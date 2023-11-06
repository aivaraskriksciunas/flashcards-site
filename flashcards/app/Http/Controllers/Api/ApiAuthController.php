<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ApiLogin;
use App\Http\Requests\Auth\ApiRegister;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Auth\EmailAlreadyExists;
use App\Http\Requests\Auth\GoogleLogin;
use App\Http\Requests\Auth\LinkGoogleAccount;
use App\Models\User;
use App\Services\Authentication\GoogleAuthenticator;
use App\Services\Authentication\GoogleAuthService;
use App\Services\Authentication\GoogleLinkingAuthenticator;
use App\Services\Authentication\PasswordAuthenticator;

class ApiAuthController extends Controller
{
    public function login( ApiLogin $request ) 
    {
        $auth = new PasswordAuthenticator( $request->validated() );
        $user = $auth->authenticate();

        return response()->json([
            'token' => $user->createToken( User::PASSWORD_LOGIN_TOKEN )->plainTextToken,
        ]);
    }

    public function register( ApiRegister $request )
    {
        $data = $request->validated();

        if ( DB::table( 'users' )->where( 'email', $data['email'] )->count() ) 
        {
            throw new EmailAlreadyExists();
        }

        $user = new User( $data );
        $user->is_admin = false;
        $user->save();

        Auth::login( $user );

        return response()->json([
            'token' => $user->createToken( User::PASSWORD_LOGIN_TOKEN )->plainTextToken,
        ]);
    }

    public function googleLogin( GoogleLogin $request ) 
    {
        $data = $request->validated();
        $authService = new GoogleAuthenticator( $data['credential'] );
        $user = $authService->authenticate();

        return response()->json([
            'token' => $user->createToken( User::GOOGLE_LOGIN_TOKEN )->plainTextToken,
        ]);
    }

    public function linkGoogleAccount( LinkGoogleAccount $request ) 
    {
        $data = $request->validated();
        $authService = new GoogleLinkingAuthenticator(
            $data['password'], 
            $data['email'],
            $data['credential']
        );
        $user = $authService->authenticate();

        return response()->json([
            'token' => $user->createToken( User::GOOGLE_LOGIN_TOKEN )->plainTextToken,
        ]);
    }
}
