<?php

namespace App\Models;

use App\Enums\UserType;
use App\Mail\InvitationReceivedEmail;
use App\Models\Utils\HasActivityLogging;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PDO;

class Invitation extends Model
{
    use HasFactory, HasUuids, HasActivityLogging;

    protected $fillable = [
        'email', 'name', 'account_type', 'valid_until'
    ];

    protected $casts = [
        'valid_until' => 'datetime',
        'account_type' => UserType::class
    ];

    public static function booted() 
    {
        static::creating( function ( Invitation $invitation ) {
            $invitation->setValidUntilDate();
            $invitation->createCode();
        } );

        static::created( function ( Invitation $invitation ) {
            $invitation->sendEmail();
        });
    }

    public function creator()
    {
        return $this->belongsTo( User::class, 'creator_id' );
    }

    public function organization()
    {
        return $this->belongsTo( Organization::class );
    }

    /**
     * Calculates and sets valid until date of this model
     *
     * @return Invitation
     */
    public function setValidUntilDate()
    {
        $this->valid_until = Carbon::now()->addSeconds( config( 'auth.invitation_timeout', 24 * 60 * 60 ) );
        return $this;
    }

    /**
     * Generates and sets a random invitation code
     *
     * @return void
     */
    public function createCode()
    {
        $this->code = (string) Str::uuid();
    }

    public function sendEmail()
    {
        Mail::to( $this->email )->send( new InvitationReceivedEmail( $this ) );
    }

    public function getAcceptationUrl()
    {
        return str( env( 'FRONTEND_URL' ) )->finish( '/' ) . "invitation/accept/{$this->code}";
    }

    public function getRouteKeyName(): string
    {
        return 'code';
    }
    
}
