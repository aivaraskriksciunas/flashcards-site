<?php

namespace App\Http\Middleware\CourseAccessLinks;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureValidCourseSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $session = $request->course_session;

        if ( $session ) {

            if ( $session->user_id !== $request->user()->id ) {
                abort( 404 );
            }

            $course_page = $request->course_page;
            if ( $course_page->course_id !== $session->course_id ) {
                abort( 404 );
            }
        }
        
        return $next($request);
    }
}
