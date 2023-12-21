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
use App\Http\Resources\User\UserDetailResource;
use App\Models\EmailConfirmation;
use App\Models\User;
use App\Services\Authentication\AccountSwitchAuthenticator;
use App\Services\Authentication\GoogleAuthenticator;
use App\Services\Authentication\GoogleLinkingAuthenticator;
use App\Services\Authentication\PasswordAuthenticator;
use App\Services\Mail\ConfirmationEmailSender;
use Illuminate\Http\Request;

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

        // Make sure this email is unique
        if ( DB::table( 'users' )->where( 'email', $data['email'] )->count() ) 
        {
            throw new EmailAlreadyExists();
        }

        $user = new User( $data );
        $user->account_type = User::USER_STUDENT;
        $user->is_valid = false;
        $user->save();

        ConfirmationEmailSender::send( $user );

        Auth::login( $user );

        return response()->json([
            'token' => $user->createToken( User::PASSWORD_LOGIN_TOKEN )->plainTextToken,
        ]);
    }

    public function currentUser( Request $request ) 
    {
        return new UserDetailResource( $request->user() );
    }

    public function sendConfirmationEmail( Request $request )
    {
        if ( !$request->user()->is_valid ) 
        {
            ConfirmationEmailSender::send( $request->user() );
        }

        return response()->json([
            'message' => 'Confirmation email sent.'
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

    public function verifyAccount( string $verification_code )
    {
        $confirmation = EmailConfirmation::where( 'verification_code', $verification_code )->firstOrFail();
        $user = $confirmation->user;
        $user->is_valid = true;
        $user->save();

        return response()->json([
            'message' => 'Email has been verified. You may now login normally.'
        ]);
    }

    public function switchAccount( Request $request, string $user )
    {
        $authenticator = new AccountSwitchAuthenticator( $request->user(), $user );
        $user = $authenticator->authenticate();

        return response()->json([
            'token' => $user->createToken( User::SWITCH_ACCOUNT_LOGIN_TOKEN )->plainTextToken,
        ]);
    }
}
