<?php

namespace App\Http\Middleware\Auth;

use App\Exceptions\Account\OrganizationNotAssigned;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class IsValidOrgAdmin
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
        // An organization admin is valid when it has an organization assigned to it
        if ( $request->user()->account_type !== User::USER_ORG_ADMIN ) {
            return $next( $request );
        }

        if ( $request->user()->organization_id == null ) {
            throw new OrganizationNotAssigned();
        }

        return $next( $request );
    }
}
