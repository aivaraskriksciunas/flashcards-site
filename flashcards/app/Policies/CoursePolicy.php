<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Organization;
use App\Models\User;
use App\Services\AccountLimiter\AccountLimiter;
use App\Services\AccountLimiter\LimiterAction;
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
        if ( $user->id === $course->user_id ) {
            return Response::allow();
        }
        
        return Response::denyAsNotFound();
    }

    public function create( User $user )
    {
        AccountLimiter::limit( $user, LimiterAction::Create, Course::class );
        
        return $user->isOrgManager() || $user->isStudent();
    }

    public function update( User $user, Course $course )
    {
        return $this->isOwner( $user, $course ) || $this->isManagerOfSameOrg( $user, $course ) ?
            Response::allow() : 
            Response::denyAsNotFound();
    }

    public function delete( User $user, Course $course )
    {
        return $this->update( $user, $course );
    }

    /**
     * Check if user is the creator of the course
     *
     * @param User $user
     * @param Course $course
     * @return boolean
     */
    private function isOwner( User $user, Course $course ): bool 
    {
        return $course->user_id == $user->id;
    }

    /**
     * Check if the user belongs to the same org as the course, and that the user is at least a manager
     *
     * @param User $user
     * @param Course $course
     * @return boolean
     */
    private function isManagerOfSameOrg( User $user, Course $course ): bool
    {
        return $user->isOrgManager() && $user->organization == $this->getOrganization( $course );
    }

    /**
     * Retrieves the organization to which the course belongs
     *
     * @param Course $course
     * @return Organization
     */
    private function getOrganization( Course $course ): Organization 
    {
        return $course->user->organization;
    }
}
