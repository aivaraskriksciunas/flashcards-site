<?php

namespace App\Http\Controllers\Api\Courses;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseAccessLink\Api\CreateCourseAccessLink;
use App\Http\Requests\CourseAccessLink\Api\UpdateCourseAccessLink;
use App\Http\Resources\CourseAccessLink\CourseAccessLinkDetailResource;
use App\Models\Course;
use App\Models\CourseAccessLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ApiCourseAccessLinkController extends Controller
{
    /**
     * Lists access links for the given course
     *
     * @param Course $course
     * @return void
     */
    public function index( Course $course )
    {
        $this->authorize( 'update', $course );

        return CourseAccessLinkDetailResource::collection( $course->accessLinks->where( 'user_created', true ) );
    }

    /**
     * Creates access link
     *
     * @param CreateCourseAccessLink $request
     * @param Course $course
     * @return void
     */
    public function store( CreateCourseAccessLink $request, Course $course )
    {
        $this->authorize( 'update', $course );

        $accessLink = new CourseAccessLink( $request->validated() );
        $accessLink->course()->associate( $course );
        $accessLink->user()->associate( $request->user() );
        $accessLink->save();

        return new CourseAccessLinkDetailResource( $accessLink );
    }

    /**
     * Changes access link
     *
     * @param UpdateCourseAccessLink $request
     * @param Course $course
     * @param CourseAccessLink $access_link
     * @return void
     */
    public function update( UpdateCourseAccessLink $request, Course $course, CourseAccessLink $access_link )
    {
        $this->authorize( 'update', $access_link );

        $access_link->update( $request->validated() );
        
        return new CourseAccessLinkDetailResource( $access_link );
    }

    /**
     * Deletes an access link
     *
     * @param Request $request
     * @param Course $course
     * @param CourseAccessLink $access_link
     * @return void
     */
    public function destroy( Request $request, Course $course, CourseAccessLink $access_link )
    {
        $this->authorize( 'delete', $access_link );

        $access_link->delete();

        return Response::noContent();
    }
}
