<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\Api\CreateCoursePageItem;
use App\Http\Requests\Courses\Api\UpdateCoursePageItem;
use App\Http\Resources\Courses\CoursePageItemDetailResource;
use App\Models\Course;
use App\Models\CoursePage;
use App\Models\CoursePageItem;
use Illuminate\Http\Request;

class ApiCoursePageItemController extends Controller
{
    public function __construct()
    {
        $this->middleware( 'can:view,course' );   
        // $this->authorizeResource( Course::class );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( CreateCoursePageItem $request, Course $course, CoursePage $coursePage )
    {
        $item = $coursePage->createPageItem(
            $request->validated()
        );

        return new CoursePageItemDetailResource( $item );
    }

    /**
     * Display the specified resource.
     */
    public function show( Course $course, CoursePage $coursePage, CoursePageItem $coursePageItem )
    {
        return new CoursePageItemDetailResource( $coursePageItem );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( UpdateCoursePageItem $request, Course $course, CoursePage $coursePage, CoursePageItem $coursePageItem )
    {
        $coursePageItem->update( $request->validated() );
        return new CoursePageItemDetailResource( $coursePageItem );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Course $course, CoursePage $coursePage, CoursePageItem $coursePageItem )
    {
        $coursePageItem->delete();
        return null;
    }
}
