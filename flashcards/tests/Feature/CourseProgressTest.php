<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseAccessLink;
use App\Models\CoursePage;
use Illuminate\Database\Eloquent\Collection;

class CourseProgressTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $owner;
    private User $other;
    private User $assigned;
    private Course $course;
    private CourseAccessLink $access_link;
    private Collection $pages;

    public function test_anyone_can_view_any_page_in_unlocked_course(): void
    {
        $this->course->update([ 'is_unlocked' => true ]);
        foreach ( $this->pages as $page )
        {
            $response = $this->actingAs( $this->assigned )->getJson(
                route( 'api.courses.access-link.course_pages.show', [ $this->access_link, $page ] )
            );
            $response->assertSuccessful();
        }
    }

    public function test_only_first_page_can_be_viewed_in_locked_course(): void
    {
        $this->course->update([ 'is_unlocked' => false ]);

        $first_page = $this->pages->shift();
        $other_pages = $this->pages->all();

        $response = $this->actingAs( $this->assigned )->getJson(
            route( 'api.courses.access-link.course_pages.show', [ $this->access_link, $first_page ] )
        );
        $response->assertSuccessful();

        foreach ( $other_pages as $page )
        {
            $response = $this->actingAs( $this->assigned )->getJson(
                route( 'api.courses.access-link.course_pages.show', [ $this->access_link, $page ] )
            );
            $response->assertForbidden();
        }
    }

    public function test_can_view_after_visiting_previous_page(): void
    {
        $this->course->update([ 'is_unlocked' => false ]);

        $first_page = $this->pages[0];
        $second_page = $this->pages[1];

        // Get the course session
        $response = $this->actingAs( $this->assigned )->getJson(
            route( 'api.courses.access-link.session', [ $this->access_link ] )
        );
        $response->assertSuccessful();
        $session_id = $response->json( 'id' );

        $response = $this->actingAs( $this->assigned )->getJson(
            route( 'api.courses.access-link.course_pages.show', [ $this->access_link, $first_page ] )
        );
        $response->assertSuccessful();

        // Try to access next page
        $response = $this->actingAs( $this->assigned )->getJson(
            route( 'api.courses.access-link.course_pages.show', [ $this->access_link, $second_page ] )
        );
        $response->assertForbidden();

        // Report progress on first page
        $response = $this->actingAs( $this->assigned )->postJson(
            route( 'api.courses.access-link.progress', [ 
                'access_link' => $this->access_link, 
                'course_page' => $first_page,
                'course_session' => $session_id 
            ] )
        );
        $response->assertSuccessful();

        // Try to access next page
        $response = $this->actingAs( $this->assigned )->getJson(
            route( 'api.courses.access-link.course_pages.show', [ $this->access_link, $second_page ] )
        );
        $response->assertSuccessful();
    }

    public function test_ensure_creates_only_one_progress_per_page(): void
    {
        $this->course->update([ 'is_unlocked' => false ]);

        $first_page = $this->pages[0];
        $second_page = $this->pages[1];

        $session_id = $this->getCourseSessionId();

        // Report progress on first page three times
        $response = $this->actingAs( $this->assigned )->postJson(
            route( 'api.courses.access-link.progress', [ $this->access_link, $session_id, $first_page ] )
        );
        $response->assertSuccessful();

        $response = $this->actingAs( $this->assigned )->postJson(
            route( 'api.courses.access-link.progress', [ $this->access_link, $session_id, $first_page ] )
        );
        $response->assertSuccessful();

        $response = $this->actingAs( $this->assigned )->postJson(
            route( 'api.courses.access-link.progress', [ $this->access_link, $session_id, $first_page ] )
        );
        $response->assertSuccessful();

        $this->assertDatabaseCount( 'course_session_pages', 1 );
        
    }

    protected function afterRefreshingDatabase()
    {
        // Seed the database
        $this->owner = User::factory()->create();
        $this->other = User::factory()->create();
        
        $this->course = Course::factory()->for( $this->owner )->create();
        $this->access_link = $this->course->getPublicAccessLink();

        $this->assigned = User::factory()->create();
        $this->course->assignedUsers()->attach( $this->assigned, [ 'assigned_by' => $this->owner->id ] );

        $this->pages = CoursePage::factory()->for( $this->course )->createMany([
            [ 'order' => 1 ],
            [ 'order' => 2 ],
            [ 'order' => 3 ],
            [ 'order' => 4 ]
        ]);
    }

    private function getCourseSessionId()
    {
        $response = $this->actingAs( $this->assigned )->getJson(
            route( 'api.courses.access-link.session', [ $this->access_link ] )
        );
        $response->assertSuccessful();
        return $response->json( 'id' );
    }
}
