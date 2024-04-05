<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\CoursePage;
use App\Models\CourseProgress;
use App\Models\User;
use App\Services\AccountLimiter\AccountLimiter;
use App\Services\AccountLimiter\LimiterAction;
use Illuminate\Auth\Access\Response;
use App\Policies\Utils\CoursePolicyBase;

class CoursePagePolicy extends CoursePolicyBase
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function before( User $user )
    {
        if ( $user->isAdmin() ) return true;

        return null;
    }

    public function create( User $user, Course $course )
    {
        AccountLimiter::limit( $user, LimiterAction::Create, CoursePage::class, $course );

        return Response::allow();
    }

    public function view( User $user, CoursePage $coursePage )
    {
        // We only need to check if this is a locked course,
        // other permissions should be checked by the course policy

        if ( $this->isOwner( $user, $coursePage->course ) ) {
            return Response::allow();
        }

        $course = $coursePage->course;
        if ( $course->is_unlocked ) return Response::allow();

        // First page should always be acceptable
        if ( $coursePage->order == 1 ) return Response::allow();

        // Get the previous page
        $prev_page = $coursePage->course->coursePages()
            ->where( 'order', '<', $coursePage->order )
            ->orderby( 'order', 'DESC' )
            ->first();
        
        if ( !$prev_page ) return Response::allow(); // Maybe this is the first page

        // User has visited the previous page, so they can see this page
        if ( $prev_page->courseProgress()->first() ) return Response::allow();

        return Response::deny();
    }
}
