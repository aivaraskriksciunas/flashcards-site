<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\CoursePage;
use Illuminate\Database\Eloquent\Collection;

class CourseProgressTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $owner;
    private User $other;
    private User $assigned;
    private Course $course;
    private Collection $pages;

    public function test_anyone_can_view_any_page_in_unlocked_course(): void
    {
        $this->course->update([ 'is_unlocked' => true ]);
        foreach ( $this->pages as $page )
        {
            $response = $this->actingAs( $this->assigned )->getJson(
                route( 'api.courses.course_pages.show', [ $this->course, $page ] )
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
            route( 'api.courses.course_pages.show', [ $this->course, $first_page ] )
        );
        $response->assertSuccessful();

        foreach ( $other_pages as $page )
        {
            $response = $this->actingAs( $this->assigned )->getJson(
                route( 'api.courses.course_pages.show', [ $this->course, $page ] )
            );
            $response->assertForbidden();
        }
    }

    public function test_can_view_after_visiting_previous_page(): void
    {
        $this->course->update([ 'is_unlocked' => false ]);

        $first_page = $this->pages[0];
        $second_page = $this->pages[1];

        $response = $this->actingAs( $this->assigned )->getJson(
            route( 'api.courses.course_pages.show', [ $this->course, $first_page ] )
        );
        $response->assertSuccessful();

        // Try to access next page
        $response = $this->actingAs( $this->assigned )->getJson(
            route( 'api.courses.course_pages.show', [ $this->course, $second_page ] )
        );
        $response->assertForbidden();

        // Report progress on first page
        $response = $this->actingAs( $this->assigned )->postJson(
            route( 'api.courses.progress', [ $this->course, $first_page ] )
        );
        $response->assertSuccessful();

        // Try to access next page
        $response = $this->actingAs( $this->assigned )->getJson(
            route( 'api.courses.course_pages.show', [ $this->course, $second_page ] )
        );
        $response->assertSuccessful();
    }

    public function test_ensure_creates_only_one_progress_per_page(): void
    {
        $this->course->update([ 'is_unlocked' => false ]);

        $first_page = $this->pages[0];
        $second_page = $this->pages[1];

        // Report progress on first page three times
        $response = $this->actingAs( $this->assigned )->postJson(
            route( 'api.courses.progress', [ $this->course, $first_page ] )
        );
        $response->assertSuccessful();

        $response = $this->actingAs( $this->assigned )->postJson(
            route( 'api.courses.progress', [ $this->course, $first_page ] )
        );
        $response->assertSuccessful();

        $response = $this->actingAs( $this->assigned )->postJson(
            route( 'api.courses.progress', [ $this->course, $first_page ] )
        );
        $response->assertSuccessful();

        $this->assertDatabaseCount( 'course_progress', 1 );
        
    }

    protected function afterRefreshingDatabase()
    {
        // Seed the database
        $this->owner = User::factory()->create();
        $this->other = User::factory()->create();
        
        $this->course = Course::factory()->for( $this->owner )->create();

        $this->assigned = User::factory()->create();
        $this->course->assignedUsers()->attach( $this->assigned, [ 'assigned_by' => $this->owner->id ] );

        $this->pages = CoursePage::factory()->for( $this->course )->createMany([
            [ 'order' => 1 ],
            [ 'order' => 2 ],
            [ 'order' => 3 ],
            [ 'order' => 4 ]
        ]);
    }
}
