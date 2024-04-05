<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\Api\CreateCoursePage;
use App\Http\Requests\Courses\Api\UpdateCoursePage;
use App\Http\Resources\Courses\CoursePageDetailResource;
use App\Http\Resources\Courses\CoursePageResource;
use App\Http\Requests\Courses\Api\SetCoursePageItemOrder;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CoursePage;
use App\Models\CourseProgress;

class ApiCoursePageController extends Controller
{
    public function __construct()
    {
        $this->middleware( 'can:view,course' );     
    }

    public function store( CreateCoursePage $request, Course $course )
    {
        $this->authorize( 'update', $course );
        $this->authorize( 'create', [ CoursePage::class, $course ]);
        $page = $course->createPage( $request->validated() );

        return new CoursePageDetailResource( $page );
    }

    public function update( UpdateCoursePage $request, Course $course, CoursePage $course_page )
    {
        $this->authorize( 'update', $course );
        $course_page->update( $request->validated() );

        return new CoursePageDetailResource( $course_page );
    }

    public function show( Course $course, CoursePage $course_page )
    {
        $this->authorize( 'view', $course );
        $this->authorize( 'view', $course_page );

        return new CoursePageDetailResource( $course_page );
    }

    public function setCoursePageItemOrder( SetCoursePageItemOrder $request, Course $course, CoursePage $course_page )
    {
        $this->authorize( 'update', $course );
        $course_page->setPageItemOrder( $request->validated( 'items' ) );
        return new CoursePageDetailResource( $course_page );
    }

    public function storeUserCourseProgress( Request $request, Course $course, CoursePage $course_page )
    {
        $this->authorize( 'view', $course );
        $this->authorize( 'view', $course_page );

        if ( !$course_page->courseProgress()->where( 'user_id', $request->user()->id )->exists() )
        {
            $progress = new CourseProgress();
            $progress->coursePage()->associate( $course_page );
            $progress->user()->associate( $request->user() );
            $progress->save();
        }

        return new CoursePageDetailResource( $course_page );
    }
}
