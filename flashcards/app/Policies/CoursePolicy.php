<?php

namespace App\Policies;

use App\Enums\CourseVisibility;
use App\Enums\UserType;
use App\Models\Course;
use App\Models\Organization;
use App\Models\User;
use App\Policies\Utils\CoursePolicyBase;
use App\Services\AccountLimiter\AccountLimiter;
use App\Services\AccountLimiter\LimiterAction;
use Illuminate\Auth\Access\Response;

class CoursePolicy extends CoursePolicyBase
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function before( User $user, string $ability ): bool|null
    {
        if ( $user->isAdmin() ) {
            return true;
        }
    
        return null;
    }

    public function viewAny( User $user ) 
    {
        return true;    
    }

    public function view( User $user, Course $course )
    {
        if ( $this->isOwner( $user, $course ) ) {
            return Response::allow();
        }

        else if ( $course->assignedUsers->contains( $user ) ) {
            return Response::allow();
        }

        else if ( $course->visibility == CourseVisibility::OrgAdmin && 
            $this->isOfSameOrg( $user, $course ) && 
            $user->account_type == UserType::ORG_ADMIN
        ) 
        {
            return Response::allow();
        }

        else if ( $course->visibility == CourseVisibility::OrgManager &&
            $this->isManagerOfSameOrg( $user, $course )
        )
        {
            return Response::allow();
        }

        else if ( $course->visibility == CourseVisibility::OrgEveryone && 
            $this->isOfSameOrg( $user, $course )
        )
        {
            return Response::allow();
        }
        
        return $course->visibility == CourseVisibility::Public ? 
            Response::allow() :
            Response::denyAsNotFound();
    }

    public function create( User $user )
    {
        AccountLimiter::limit( $user, LimiterAction::Create, Course::class );
        
        return $user->isOrgManager() || $user->isStudent();
    }

    public function update( User $user, Course $course )
    {
        if ( $this->isOwner( $user, $course ) )
        {
            return Response::allow();
        }

        return $course->visibility != CourseVisibility::Private && $this->isManagerOfSameOrg( $user, $course ) ?
            Response::allow() : 
            Response::denyAsNotFound();
    }

    public function delete( User $user, Course $course )
    {
        return $this->update( $user, $course );
    }
}
