<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
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
        return $user->id === $course->user_id ?
            Response::allow() :
            Response::denyAsNotFound();
    }

    public function create( User $user )
    {
        // TODO: use dynamic permission policy
        return $user->isOrgAdmin() || $user->isStudent();
    }

    public function update( User $user, Course $course )
    {
        return $user->id === $course->user_id ?
            Response::allow() : 
            Response::denyAsNotFound();
    }

    public function delete( User $user, Course $course )
    {
        return $user->id === $course->user_id ?
            Response::allow() : 
            Response::denyAsNotFound();
    }
}
