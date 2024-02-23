<?php

namespace App\Models;

use App\Models\Utils\HasActivityLogging;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory, HasUlids, HasActivityLogging;

    protected $fillable = [
        'name', 'type'
    ];

    public function user() {
        return $this->hasOne( User::class );
    }

    public function users() {
        return $this->hasMany( User::class );
    }

    public function invitations() {
        return $this->hasMany( Invitation::class );
    }

    public function getValidInvitations() {
        return $this->invitations()->where( 'valid_until', '>', Carbon::now() );
    }

    /**
     * Checks if the user is already assigned to the organization
     *
     * @param string $email
     * @return boolean
     */
    public function hasUser( string $email ) 
    {
        $inv = $this->invitations()->where( 'email', $email )->count();
        $members = $this->users()->where( 'email', $email )->count();

        return $inv !== 0 || $members !== 0;
    }
}
