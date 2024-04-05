<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\Api\AssignCourseToUser;
use App\Http\Requests\Courses\Api\CreateCourse;
use App\Http\Requests\Courses\Api\SetCoursePageOrder;
use App\Http\Requests\Courses\Api\UpdateCourse;
use App\Http\Resources\Courses\CourseDetailResource;
use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\Organization\OrganizationMemberResource;
use App\Http\Resources\User\UserDetailResource;
use App\Http\Resources\User\UserResource;
use App\Models\Course;
use App\Models\User;
use App\Services\Courses\CourseService;
use App\Services\DataTable\DataTable;
use Illuminate\Http\Request;

class ApiCourseController extends Controller
{

    public function __construct(
        private CourseService $courseService
    )
    { 
    }

    /**
     * Retrieves a list of courses created by this user
     *
     * @param Request $request
     * @return void
     */
    public function getUserCourses( Request $request )
    {
        $dt = new DataTable( sortable:[ 'title' ] );
        $dt->applyUserFilters( $request->user()->courses(), $request );
        return CourseResource::collection( $dt->getPaginated() );
    }

    /**
     * Retrieves a list of courses assigned to this user
     *
     * @param Request $request
     * @return void
     */
    public function getAssignedUserCourses( Request $request )
    {
        return CourseResource::collection( 
            $this->courseService->getUserAssignedCourses( $request->user() )->limit( 5 )->get()
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
        $this->authorize( 'store', Course::class );
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
        $this->authorize( 'view', $course );
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
     * Retrieve a list of users who can be assigned to this course
     */
    public function getAssignableUsers( Request $request, Course $course )
    {
        $this->authorize( 'update', $course );
        $dt = new DataTable( sortable:[ 'name' ] );
        $members = $this->courseService->getAssignableUsers( $request->user(), $course );

        return OrganizationMemberResource::collection( 
            $dt->applyUserFilters( $members, $request )->getPaginated()
        );
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
    public function update( UpdateCourse $request, Course $course )
    {
        $this->authorize( 'update', $course );
        $course->update( $request->validated() );
        
        return new CourseResource( $course );
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
