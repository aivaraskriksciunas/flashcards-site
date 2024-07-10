<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\CoursePage;
use App\Models\CourseSession;
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

    public function view( ?User $user, CoursePage $coursePage )
    {
        // We only need to check if this is a locked course,
        // other permissions should be checked by the course policy

        if ( !$user ) {
            // Allow to anonymous users by default, more permissions should be handled in other policies
            return Response::allow();
        }

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

        // Check if user has visited the previous page
        $session = CourseSession::getUserCourseSession( $user, $coursePage->course );
        if ( $session->courseSessionPages()->where( 'course_page_id', $prev_page->id )->exists() ) {
            return Response::allow();
        }

        return Response::deny();
    }
}
