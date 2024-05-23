<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PasswordReset extends Model
{
    use HasFactory;

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    /**
     * Quickly creates a password reset record for the given user
     *
     * @param User $user
     * @return PasswordReset
     */
    public static function createForUser( User $user )
    {
        $reset = new PasswordReset();
        $reset->user()->associate( $user );
        $reset->code = Str::uuid()->toString();
        $reset->expires_at = Carbon::now()->addMinutes( 30 );
        $reset->save();

        return $reset;
    }

    /**
     * Removes all existing password resets for this user
     *
     * @param User $user
     * @return void
     */
    public static function clearResetsForUser( User $user )
    {
        PasswordReset::where( 'user_id', $user->id )->delete();
    }

    public function user()
    {
        return $this->belongsTo( User::class );
    }

    public function getPublicUrl()
    {
        return str( env( 'FRONTEND_URL' ) )->finish( '/' ) . "reset-password/{$this->code}";
    }
}
