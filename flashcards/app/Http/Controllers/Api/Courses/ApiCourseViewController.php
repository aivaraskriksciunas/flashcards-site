<?php

namespace App\Http\Controllers\Api\Courses;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseAccessLink\Api\CreateAnonymousAccount;
use App\Http\Resources\Courses\CourseDetailPublicResource;
use App\Http\Resources\Courses\CoursePageDetailResource;
use App\Http\Resources\CourseSession\CourseSessionResource;
use App\Models\Course;
use App\Models\CourseAccessLink;
use App\Models\CoursePage;
use App\Models\CourseSession;
use App\Models\CourseSessionPage;
use App\Models\User;
use App\Services\Accounts\AccountManager;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiCourseViewController extends Controller
{
    /**
     * Shows course placed under this access link
     *
     * @param CourseAccessLink $access_link
     * @return void
     */
    public function showCourse( CourseAccessLink $access_link )
    {
        $this->authorize( 'view', $access_link );
        $course = $this->getCourse( $access_link );

        return new CourseDetailPublicResource( $course );
    }

    /**
     * Shows course page under the access link
     *
     * @param CourseAccessLink $access_link
     * @param CoursePage $course_page
     * @return void
     */
    public function showCoursePage( CourseAccessLink $access_link, CoursePage $course_page )
    {
        $this->authorize( 'view', $access_link );
        $this->authorize( 'view', $course_page );

        return new CoursePageDetailResource( $course_page );
    }

    /**
     * Returns the current or a new session link for this user
     *
     * @param Request $request
     * @param CourseAccessLink $accessLink
     * @return CourseSessionResource|null
     */
    public function getCourseSession( Request $request, CourseAccessLink $access_link )
    {
        $this->authorize( 'view', $access_link );

        if ( $request->user() == null ) return null;

        return new CourseSessionResource( 
            CourseSession::getUserCourseSession( $request->user(), $access_link->course ) 
        );
    }


    public function storeUserCourseProgress( Request $request, CourseAccessLink $access_link, CourseSession $course_session, CoursePage $course_page )
    {
        $this->authorize( 'view', $access_link );
        $this->authorize( 'view', $course_page );

        if ( !$course_session->courseSessionPages()
                ->where( 'course_page_id', $course_page->id )
                ->exists() )
        {
            $progress = new CourseSessionPage();
            $progress->coursePage()->associate( $course_page );
            $progress->started_at = Carbon::now();
            $course_session->courseSessionPages()->save( $progress );
        }

        return new CoursePageDetailResource( $course_page );
    }

    public function createAnonymousAccount( CreateAnonymousAccount $request, CourseAccessLink $accessLink )
    {
        $this->authorize( 'create_anonymous_account', $accessLink );
        
        $accountManager = app( AccountManager::class );
        $account = $accountManager->registerAnonymousAccount( $request->input( 'name' ) );

        return response()->json([
            'token' => $account->createToken( User::ANONYMOUS_USER_TOKEN )->plainTextToken,
        ]);
    }

    /**
     * Gets the course associated to an access link
     *
     * @param CourseAccessLink $access_link
     * @return Course
     */
    private function getCourse( CourseAccessLink $access_link ) : Course
    {
        return $access_link->course;
    }
}
