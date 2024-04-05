<?php 

namespace App\Limiters;

use App\Models\Course;
use App\Models\User;
use App\Models\CoursePage;

class CoursePageLimiter extends Limiter 
{
    public function canCreate( User $user, CoursePage|string $coursePage, Course $course ) : bool
    {
        $pages = $course->coursePages()->count();

        return $pages < config( 'tiers.default.courses.pages' );
    }

    public function canUndelete( User $user, CoursePage $coursePage, Course $course ): bool
    {
        return $this->canCreate( $user, $coursePage, $course );
    }
}