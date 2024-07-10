<?php

namespace App\Http\Middleware\CourseAccessLinks;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCourseAccessLinkIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $access_link = $request->access_link;
        if ( !$access_link ) {
            return $next($request);
        }

        if ( Carbon::now()->greaterThan( $access_link->expires_at ) ) {
            return abort( 404 );
        }

        // Ensure URL hierarchy for links
        $course_page = $request->course_page;
        if ( $course_page && $course_page->course_id !== $access_link->course_id ) {
            abort( 404 );
        }

        return $next($request);
    }
}
