<?php

namespace App\Http\Middleware\Auth;

use App\Exceptions\Auth\UserInvalid;
use Closure;
use Illuminate\Http\Request;

class IsValidUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ( !$request->isMethod( 'get' ) && !$request->user()->is_valid ) {
            throw new UserInvalid();
        }

        return $next($request);
    }
}
