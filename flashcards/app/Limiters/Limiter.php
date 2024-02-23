<?php 

namespace App\Limiters;

use App\Exceptions\Tier\AccountLimitReached;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class Limiter 
{

    public function before( User $user )
    {

    }

    public function after( User $user )
    {
        
    }

    public function getCreateLimitErrorMessage()
    {
        return "You have reached the maximum number of {$this->getObjectName()} on your account.";
    }

    public function getUndeleteLimitErrorMessage()
    {
        return "You have reached the maximum number of {$this->getObjectName()} on your account.";
    }

    public function getViewLimitErrorMessage()
    {
        return "You have reached the view limit for {$this->getObjectName()}.";
    }

    /**
     * Returns the plural name of the objects, which will be used in the error messages
     *
     * @return string plural lowercase name of the affected object
     */
    protected function getObjectName(): string
    {
        return Str::of( class_basename( $this ) )
            ->replaceEnd( 'Limiter', '' )
            ->pluralStudly()
            ->headline()
            ->lower()
            ->toString();
    }
}