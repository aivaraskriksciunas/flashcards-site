<?php 

namespace App\Policies\Utils;
use App\Models\User;
use App\Models\Course;
use App\Models\Organization;

class CoursePolicyBase 
{
    /**
     * Check if user is the creator of the course
     *
     * @param User $user
     * @param Course $course
     * @return boolean
     */
    protected function isOwner( User $user, Course $course ): bool 
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
    protected function isManagerOfSameOrg( User $user, Course $course ): bool
    {
        return $user->isOrgManager() && $this->isOfSameOrg( $user, $course );
    }

    /**
     * Check whether the user and the course are from the same organization
     *
     * @param User $user
     * @param Course $course
     * @return boolean
     */
    protected function isOfSameOrg( User $user, Course $course ): bool 
    {
        return $user->organization == $this->getOrganization( $course );
    }

    /**
     * Retrieves the organization to which the course belongs
     *
     * @param Course $course
     * @return Organization
     */
    protected function getOrganization( Course $course ): Organization|null 
    {
        return $course->user->organization;
    }
}