<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordReset\Api\CreateReset;
use App\Mail\PasswordResetEmail;
use App\Models\PasswordReset;
use App\Models\User;
use App\Http\Requests\PasswordReset\Api\ResetPassword as ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    public function createResetLink( CreateReset $request )
    {
        $response = [ 'success' => true, 'message' => 'A password reset has been sent to the specified email.' ];

        $email = $request->input( 'email' );
        $user = User::where( 'email', $email )->first();
        if ( !$user ) 
        {
            return $response;
        }

        $user = $user->getParentAccount();
        $reset = PasswordReset::createForUser( $user );
        Mail::to( $user->email )->send( new PasswordResetEmail( $reset ) );

        return $response;
    }

    public function resetPassword( ResetPasswordRequest $request, PasswordReset $password_reset )
    {
        $password_reset->user->update([ 'password' => $request->input( 'password' )]);
        PasswordReset::clearResetsForUser( $password_reset->user );

        return [
            'success' => true,
            'message' => 'Your password has been reset. You may now login normally.'
        ];
    }
}
