<?php

namespace App\Services\Courses;

use App\Enums\CourseVisibility;
use App\Enums\UserType;
use App\Http\Resources\Courses\CourseResource;
use App\Models\Course;
use App\Models\User;

class CourseService 
{
    /**
     * Undocumented function
     *
     * @param User $user
     * @return object Query
     */
    public function getUserAssignedCourses( User $user )
    {
        return $user->assignedCourses()
            ->withPivot([ 'created_at' ])
            ->orderByPivot( 'created_at', 'DESC' );
    }

    public function getAssignableUsers( User $user, Course $course )
    {
        if ( !$user->organization ) return collect( [] );

        return $user->organization->users()
            ->whereNotIn('id', function ( $query ) use ( $course ) {
                $query->select( 'user_id' )
                    ->from( 'assigned_user_courses' )
                    ->where( 'course_id', $course->id );
            })
            ->whereNot( 'id', $user->id );
    }

    /**
     * Retrieves all courses from the organization that are visible to the user
     * 
     * @param User $user
     * @return void
     */
    public function getOrganizationVisibleCourses( User $user )
    {
        if ( !$user->organization ) return [];

        $visibility_levels = [ CourseVisibility::Public, CourseVisibility::OrgEveryone ];
        if ( $user->account_type == UserType::ORG_ADMIN )
        {
            $visibility_levels[] = CourseVisibility::OrgAdmin;
        }
        if ( $user->account_type == UserType::ORG_MANAGER )
        {
            $visibility_levels[] = CourseVisibility::OrgManager;
        }

        $query = Course::whereRelation( 'user', 'organization_id', $user->organization->id )
            ->whereIn( 'visibility', $visibility_levels );

        return $query;
    }
}