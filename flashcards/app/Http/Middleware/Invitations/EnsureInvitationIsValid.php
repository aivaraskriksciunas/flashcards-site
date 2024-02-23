<?php

namespace App\Http\Middleware\Invitations;

use App\Models\Invitation;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInvitationIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $invitation = $request->invitation;
        if ( !$invitation || $invitation->valid_until->isPast() ) 
        {
            abort( 404 );
        }

        return $next($request);
    }
}
