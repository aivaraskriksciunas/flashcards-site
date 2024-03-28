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
     * Retrieves any courses for the user (created by, assigned to, organization public courses)
     *
     * @param User $user
     * @return void
     */
    public function getUserCourses( User $user )
    {
        $user_courses = $user->courses()->limit( 5 )->get();
        $assigned_courses = $user->assignedCourses()
            ->withPivot([ 'created_at' ])
            ->orderByPivot( 'created_at', 'DESC' )
            ->get();
        $organization_courses = $this->getOrganizationVisibleCourses( $user );

        return [
            'user_courses' => CourseResource::collection( $user_courses ),
            'assigned_courses' => CourseResource::collection( $assigned_courses ),
            // 'organization_courses' => CourseResource::collection( $organization_courses ),
        ];
    }

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