<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\Api\CreateCourse;
use App\Http\Requests\Courses\Api\SetCoursePageOrder;
use App\Http\Resources\Courses\CourseDetailResource;
use App\Http\Resources\Courses\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;

class ApiCourseController extends Controller
{

    public function __construct()
    { 
        $this->authorizeResource( Course::class );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        return CourseResource::collection( 
            $request->user()->courses()->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( CreateCourse $request )
    {
        $course = new Course( $request->validated() );
        $request->user()->courses()->save( $course );

        return new CourseResource( $course );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( Course $course )
    {
        return new CourseDetailResource( $course );
    }

    /**
     * Endpoint to reorder the pages in the course
     *
     * @param SetCoursePageOrder $request
     * @param Course $course
     * @return void
     */
    public function setCoursePageOrder( SetCoursePageOrder $request, Course $course )
    {
        $this->authorize( 'update', $course );
        $course->setPageOrder( $request->input( 'pages', [] ) );
        return new CourseDetailResource( $course->refresh() );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
