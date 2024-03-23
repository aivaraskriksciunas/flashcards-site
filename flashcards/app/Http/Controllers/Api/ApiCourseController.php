<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\Api\AssignCourseToUser;
use App\Http\Requests\Courses\Api\CreateCourse;
use App\Http\Requests\Courses\Api\SetCoursePageOrder;
use App\Http\Resources\Courses\CourseDetailResource;
use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\User\UserDetailResource;
use App\Http\Resources\User\UserResource;
use App\Models\Course;
use App\Models\User;
use App\Services\DataTable\DataTable;
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
     * Endpoint to retrieve the users who have the course assigned to them
     *
     * @param Request $request
     * @param Course $course
     * @return void
     */
    public function getAssignedUsers( Request $request, Course $course )
    {
        $this->authorize( 'update', $course );
        $dt = new DataTable( sortable:[ 'name' ] );
        $dt->applyUserFilters( $course->assignedUsers(), $request );
        return UserResource::collection( $dt->getPaginated() );
    }

    /**
     * Endpoint to assign course to a new user
     *
     * @param Request $request
     * @param Course $course
     * @return void
     */
    public function assignToOrgMember( AssignCourseToUser $request, Course $course )
    {
        $this->authorize( 'update', $course );

        $users = $request->user()->organization->users()
            ->whereIn( 'id', $request->validated( 'user_ids' ) )
            ->get();
        
        $assignedUsers = [];
        foreach ( $users as $user ) 
        {
            try {
                $course->assignedUsers()->attach( $user->id, [ 'assigned_by' => $request->user()->id ] );
                $assignedUsers[] = $user;
            }
            catch ( \Exception $e ) {}
        }

        return UserResource::collection( $assignedUsers );
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
