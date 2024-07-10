<?php

namespace Tests\Feature;

use App\Enums\CourseAccessLinkType;
use App\Enums\UserType;
use App\Models\Course;
use App\Models\CourseAccessLink;
use App\Models\CoursePage;
use App\Models\CoursePageItem;
use App\Models\CourseSession;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReadCourseWithAccessLinkTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $owner;
    private User $guest;
    
    private Organization $org;
    private User $orgadmin;
    private User $orgmember;

    private Course $course;
    private Course $course2;
    private Course $orgcourse;
    private Collection $coursePages;
    private Collection $course2Pages;
    private Collection $orgcoursePages;

    public function test_guest_can_access()
    {
        $link = $this->createCourseLink( CourseAccessLinkType::Unrestricted );
        $response = $this->actingAs( $this->guest )
            ->getJson( route( 'api.courses.access-link.show', [ 'access_link' => $link->link ] ));
        $response->assertSuccessful();
        $this->assertEquals( $this->course->title, $response->json( 'title' ) );
    }

    public function test_guest_can_access_pages()
    {
        $link = $this->createCourseLink( CourseAccessLinkType::Unrestricted );
        $response = $this->actingAs( $this->guest )
            ->getJson( route( 'api.courses.access-link.course_pages.show', [ 
                'access_link' => $link->link, 
                'course_page' => $this->coursePages[0]->id 
            ] ));
        $response->assertSuccessful();
        $this->assertEquals( $this->coursePages[0]->title, $response->json( 'title' ) );
    }

    public function test_cannot_access_with_nonexisting_link()
    {
        $link = $this->createCourseLink( CourseAccessLinkType::Unrestricted );
        $response = $this->actingAs( $this->guest )
            ->getJson( route( 'api.courses.access-link.show', [ 'access_link' => $this->faker->uuid() ] ));
        $response->assertNotFound();
    }

    public function test_ensures_course_page_hierarchy()
    {
        $link = $this->createCourseLink( CourseAccessLinkType::Unrestricted );
        $response = $this->actingAs( $this->guest )
            ->getJson( route( 'api.courses.access-link.course_pages.show', [ 
                'access_link' => $link->link, 
                'course_page' => $this->course2Pages[0]->id  // Page from a different course
            ] ));
        $response->assertNotFound();
    }

    public function test_cannot_access_course_with_expired_access_link()
    {
        $link = $this->createCourseLink( CourseAccessLinkType::Unrestricted, Carbon::now()->subDays( 3 ) );
        $response = $this->actingAs( $this->guest )
            ->getJson( route( 'api.courses.access-link.show', [ 'access_link' => $link->link ] ));
        $response->assertNotFound();

        // Test the same with course pages
        $response = $this->actingAs( $this->guest )
            ->getJson( route( 'api.courses.access-link.course_pages.show', [ 
                'access_link' => $link->link, 
                'course_page' => $this->coursePages[0]->id 
            ] ));
        $response->assertNotFound();
    }

    public function test_anonymous_links()
    {
        // Check if we can access without an account
        $link = $this->createCourseLink( CourseAccessLinkType::Unrestricted );
        $response = $this->getJson( route( 'api.courses.access-link.show', [ 'access_link' => $link->link ] ));
        $response->assertSuccessful();

        // Test the same with course pages
        $response = $this->getJson( route( 'api.courses.access-link.course_pages.show', [ 
                'access_link' => $link->link, 
                'course_page' => $this->coursePages[0]->id 
            ] ));
        $response->assertSuccessful();

        // Check if we cannot access when it's not an anonymous
        $link = $this->createCourseLink( CourseAccessLinkType::RequireAccount );
        $response = $this->getJson( route( 'api.courses.access-link.show', [ 'access_link' => $link->link ] ));
        $response->assertForbidden();

        // Test the same with course pages
        $response = $this->getJson( route( 'api.courses.access-link.course_pages.show', [ 
                'access_link' => $link->link, 
                'course_page' => $this->coursePages[0]->id 
            ] ));
        $response->assertForbidden();
    }

    public function test_link_is_only_available_to_assigned_members()
    {
        // Get access link 
        $link = $this->orgcourse->getAccessLinkForAssignedUsers();
        // Check if non assigned user cannot access it
        $response = $this->actingAs( $this->orgmember )
            ->getJson( route( 'api.courses.access-link.show', [ 'access_link' => $link->link ] ));
        $response->assertNotFound();
        
        // Assign member to the course and try again
        $this->orgcourse->assignedUsers()->attach( $this->orgmember, [ 'assigned_by' => $this->orgadmin->id ] );

        $response = $this->actingAs( $this->orgmember )
            ->getJson( route( 'api.courses.access-link.show', [ 'access_link' => $link->link ] ));
        $response->assertSuccessful();

        $response = $this->actingAs( $this->guest )
            ->getJson( route( 'api.courses.access-link.show', [ 'access_link' => $link->link ] ));
        $response->assertNotFound();
    }

    public function test_anonymous_accounts()
    {
        $link = $this->createCourseLink( CourseAccessLinkType::RequireName );
        
        // Create anonymous account
        $name = $this->faker->name();
        $response = $this->postJson( 
            route( 'api.courses.access-link.anonymous-account.create', $link ), 
            [ 'name' => $name ] 
        );
        $response->assertSuccessful();

        $anon_user = User::where( 'account_type', UserType::ANONYMOUS )->first();
        $this->assertNotNull( $anon_user, 'Anonymous account should be created' );
        $this->assertEquals( $name, $anon_user->name );

        // Check if can view course
        $response = $this->actingAs( $anon_user )
            ->getJson( route( 'api.courses.access-link.show', [ 'access_link' => $link->link ] ));
        $response->assertSuccessful();

        // Check if can view course page
        $response = $this->actingAs( $anon_user )
            ->getJson( route( 'api.courses.access-link.course_pages.show', [ 
                'access_link' => $link->link, 
                'course_page' => $this->coursePages[0]->id 
            ] ));
        $response->assertSuccessful();

        // Check if can get session progress 
        $response = $this->actingAs( $anon_user )
            ->getJson( route( 'api.courses.access-link.session', [ 
                'access_link' => $link->link, 
            ] ));
        $response->assertSuccessful();
        $this->assertEquals( 1, CourseSession::count(), 'Course progress record should be created' );



        // Now test the same but when making a more restricted access link
        $link = $this->createCourseLink( CourseAccessLinkType::RequireAccount );

        $response = $this->actingAs( $anon_user )
            ->getJson( route( 'api.courses.access-link.show', [ 'access_link' => $link->link ] ));
        $response->assertForbidden();

        $response = $this->actingAs( $anon_user )
            ->getJson( route( 'api.courses.access-link.course_pages.show', [ 
                'access_link' => $link->link, 
                'course_page' => $this->coursePages[0]->id 
            ] ));
        $response->assertForbidden();

        $response = $this->actingAs( $anon_user )
            ->getJson( route( 'api.courses.access-link.session', [ 
                'access_link' => $link->link, 
            ] ));
        $response->assertForbidden();
        $this->assertEquals( 1, CourseSession::count(), 'New progress should not be created.' );
    }

    public function afterRefreshingDatabase()
    {
        $this->owner = User::factory()->create();
        $this->guest = User::factory()->create();
        $this->course = Course::factory()->for( $this->owner )->create();
        $this->course2 = Course::factory()->for( $this->owner )->create();

        $this->org = Organization::factory()->create();
        $this->orgadmin = User::factory()->for( $this->org )->orgAdmin()->create();
        $this->orgcourse = Course::factory()->for( $this->orgadmin )->create();
        $this->orgmember = User::factory()->for( $this->org )->orgMember()->create();
        
        $this->coursePages = CoursePage::factory()->for( $this->course )->createMany( 10 );
        $this->coursePages->each( fn ( $page ) => CoursePageItem::factory()->for( $page )->createMany( 3 ) );

        $this->course2Pages = CoursePage::factory()->for( $this->course2 )->createMany( 10 );
        $this->course2Pages->each( fn ( $page ) => CoursePageItem::factory()->for( $page )->createMany( 3 ) );

        $this->orgcoursePages = CoursePage::factory()->for( $this->course2 )->createMany( 10 );
        $this->orgcoursePages->each( fn ( $page ) => CoursePageItem::factory()->for( $page )->createMany( 3 ) );
    }

    private function createCourseLink( CourseAccessLinkType $type, ?Carbon $expires = null ): CourseAccessLink
    {
        $link = new CourseAccessLink([
            'name' => $this->faker->word(),
            'type' => $type,
            'expires_at' => $expires
        ]);

        $link->course()->associate( $this->course );
        $link->user()->associate( $this->owner );
        $link->save();
        return $link;
    }
}
