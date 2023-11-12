<?php 

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EmailConfirmation extends Model
{
    protected $table = 'email_verifications';

    protected $casts = [
        'valid_until' => 'datetime'
    ];

    public function user() 
    {
        return $this->belongsTo( User::class );
    }

    public function getPublicUrl() 
    {
        return env( 'FRONTEND_URL' ) . "verify-email/" . $this->verification_code;
    }
}