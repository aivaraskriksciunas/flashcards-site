<?php 

namespace App\Limiters;

use App\Exceptions\Tier\UnsupportedAction;
use App\Models\Course;
use App\Models\User;

class CourseLimiter extends Limiter 
{
    public function canCreate( User $user ) : bool
    {
        $courses = $this->getUserCourseCount( $user );
        
        $organization = $user->organization;
        if ( $organization )
        {
            $courses += $this->getOrganizationCourseCount( $organization );
        }

        return $courses < config( 'tiers.default.courses.count' );
    }

    public function canUndelete( User $user, Course $model ): bool
    {
        return $this->canCreate( $user );
    }

    private function getOrganizationCourseCount( $organization )
    {
        // TODO:
        return 0;
    }

    private function getUserCourseCount( User $user )
    {
        return $user->courses()->count();
    }
}