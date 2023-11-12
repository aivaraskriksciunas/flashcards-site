<?php

namespace App\Services\Mail;

use App\Exceptions\Auth\ConfirmationEmailAlreadySent;
use App\Mail\ConfirmEmail;
use App\Models\User;
use App\Models\EmailConfirmation;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ConfirmationEmailSender 
{

    public static function send( User $user )
    {
        $confirmation = ConfirmationEmailSender::createConfirmationForUser( $user );
        $email = new ConfirmEmail( $confirmation );
        Mail::to( $user->email )->send( $email );
    }

    private static function createConfirmationForUser( User $user ) : EmailConfirmation
    {
        $last_email = $user->emailConfirmations()->where( 
            'created_at', '>=', Carbon::now()->subMinutes( 
                env( 'CONFIRMATION_EMAIL_INTERVAL_MINS' )
            )
        )->first();

        if ( $last_email )
        {
            throw new ConfirmationEmailAlreadySent();
        }

        $confirmation = new EmailConfirmation();
        $confirmation->user()->associate( $user );
        $confirmation->verification_code = Str::orderedUuid();
        $confirmation->valid_until = Carbon::now()->addMinutes(
            env( 'CONFIRMATION_CODE_VALIDITY_MINS', 15 )
        );
        $confirmation->save();
        return $confirmation;
    }

}