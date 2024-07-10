<?php

namespace App\Http\Middleware\Auth;

use App\Enums\UserType;
use App\Exceptions\Account\InvalidAccountType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsValidAccountType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $type = $request->user()->account_type;

        if ( $type == null || $type == UserType::UNDEFINED || $type == UserType::ANONYMOUS ) {
            throw new InvalidAccountType();
        } 
        
        return $next($request);
    }
}
