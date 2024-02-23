<?php 

namespace App\Services\AccountLimiter;

use App\Exceptions\Tier\AccountLimitReached;
use App\Exceptions\Tier\UnsupportedAction;
use App\Limiters\Limiter;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class AccountLimiter
{
    /**
     * Checks if the user can perform the given action for the provided model.
     * Throws an exception in case they have reached the limit
     *
     * @param User $user
     * @param LimiterAction $action action the user wants to perform
     * @param [type] $model Model or class on which the action will be performed
     * @return void
     * @throws \App\Exceptions\Tier\AccountLimitReached
     */
    public static function limit( User $user, LimiterAction $action, string|Model $model = null )
    {
        $object_class = class_basename( $model );
        $limiter_class = "\\App\\Limiters\\{$object_class}Limiter";

        if ( !class_exists( $limiter_class ) )
        {
            // No limits were set, allow by default
            return true; 
        }

        if ( !method_exists( $limiter_class, $action->value ) ) 
        {
            // No limits for this action were set, allo by default
            return true;
        }

        $limiter = App::make( $limiter_class );
        if ( !is_subclass_of( $limiter, Limiter::class ) )
        {
            // Class misconfigured
            throw new Exception( 'Limiter class must extend Account Limiter' );
        }

        // Perform checking action
        $limiter->before( $user );
        $result = $limiter->{$action->value}( $user, $model );
        $limiter->after( $user );

        if ( !$result )
        {
            static::throwLimitReachedException( $action, $limiter );
        }
    }

    /**
     * Same as limit, but returns boolean instead of throwing exception
     *
     * @param User $user
     * @param LimiterAction $action
     * @param string|Model $model
     * @return boolean
     */
    public static function can( User $user, LimiterAction $action, string|Model $model ) : bool
    {
        try {
            static::limit( $user, $action, $model );
        }
        catch ( AccountLimitReached $exception ) {
            return false;
        }
        catch ( UnsupportedAction $exception ) {
            return false;
        }

        return true;
    }

    private static function throwLimitReachedException( LimiterAction $action, $limiter )
    {
        $message = 'You have reached the maximum number actions for your account.';

        if ( $action == LimiterAction::Create ) 
        {
            $message = $limiter->getCreateLimitErrorMessage();
        }
        else if ( $action == LimiterAction::Undelete ) 
        {
            $message = $limiter->getUndeleteLimitErrorMessage();
        }
        else if ( $action == LimiterAction::View ) 
        {
            $message = $limiter->getViewLimitErrorMessage();
        }

        throw new AccountLimitReached( $message );
    }
}