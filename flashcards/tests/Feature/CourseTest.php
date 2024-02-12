<?php

namespace Tests\Feature;

use App\Enums\CoursePageType;
use App\Models\Course;
use App\Models\CoursePage;
use App\Models\User;
use Database\Factories\CoursePageFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_can_create_course()
    {
        $user = User::factory()->create();

        $title = $this->faker()->sentence( 3 );
        $response = $this->actingAs( $user )
            ->postJson( route( 'api.courses.store' ), [
                'title' => $title
            ]);

        $response->assertCreated();

        $this->assertDatabaseCount( 'courses', 1 );
        $this->assertCount( 1, $user->courses()->get(), 'Course should be attributed to the user' );
        $this->assertEquals( $title, Course::first()->title, 'Course title should be set correctly' );
    }

    public function test_retrieves_user_course_list()
    {
        $user = User::factory()->create();
        $courses = Course::factory()->for( $user )->count( 5 )->create();
        $other_courses = Course::factory()->for(
            User::factory()->create()
        )->count( 10 )->create();

        $response = $this->actingAs( $user )
            ->getJson( route( 'api.courses.index' ) );
        
        $response->assertSuccessful();
        $json = $response->json();
        $this->assertCount( 5, $json, 'Should return only user\'s courses.' );
    }

    public function test_can_create_course_page()
    {
        $user = User::factory()->create();
        $course = Course::factory()->for( $user )->create();

        $title = $this->faker->words( 3, true );
        $type = CoursePageType::Page;
        $order = 1;

        $response = $this->actingAs( $user )
            ->postJson( route( 'api.courses.course_pages.store', $course ), [
                'title' => $title,
                'type' => $type,
                'order' => $order,
            ]);

        $response->assertCreated();

        $this->assertDatabaseCount( 'courses', 1 );
        $this->assertCount( 1, $course->coursePages()->get(), 'Page should be attributed to course' );
        $page = $course->coursePages()->first();
        $this->assertEquals( $title, $page->title, 'Page title should be set correctly' );
        $this->assertEquals( $order, $page->order, 'Page order should be set correctly' );
        $this->assertEquals( $type, $page->type, 'Page type should be set correctly' );
    }

    public function test_new_course_page_is_last_by_default()
    {
        $user = User::factory()->create();
        $course = Course::factory()->for( $user )->create();
        $pages = CoursePage::factory()->for( $course )->createMany([
            [ 'order' => 1 ],
            [ 'order' => 2 ],
            [ 'order' => 3 ],
        ]);

        $title = $this->faker->words( 4, true );
        $type = CoursePageType::Page;

        $response = $this->actingAs( $user )
            ->postJson( route( 'api.courses.course_pages.store', $course ), [
                'title' => $title,
                'type' => $type,
            ]);

        $response->assertCreated();

        $this->assertDatabaseCount( 'course_pages', 4 );
        $this->assertCount( 4, $course->coursePages()->get(), 'Page should be attributed to course' );
        $page = $course->coursePages()->where( 'title', $title )->first();
        $this->assertEquals( $title, $page->title, 'Page title should be set correctly' );
        $this->assertEquals( 4, $page->order, 'Page order should be set correctly' );
        $this->assertEquals( $type, $page->type, 'Page type should be set correctly' );
    }

    public function test_items_are_presented_in_order()
    {
        $user = User::factory()->create();
        $course = Course::factory()->for( $user )->create();
        $pages = CoursePage::factory()->for( $course )->createMany([
            [ 'order' => 3 ],
            [ 'order' => 1 ],
            [ 'order' => 4 ],
            [ 'order' => 2 ],
        ]);

        $response = $this->actingAs( $user )
            ->getJson( route( 'api.courses.show', $course ) );

        $response->assertSuccessful();

        $pages = collect( $response->json()['pages'] );
        $pages = $pages->pluck( 'order' );
        $ordered_pages = $pages->sort();
        $this->assertEquals( $ordered_pages->join( '' ), $pages->join( '' ), 'Items should be served in the right order.' );
    }

    public function test_updates_course_page()
    {
        $user = User::factory()->create();
        $course = Course::factory()->for( $user )->create();
        $page = CoursePage::factory()->for( $course )->create();

        $title = $this->faker()->sentence( 2 );

        $response = $this->actingAs( $user )
            ->patchJson( 
                route( 'api.courses.course_pages.update', [ 'course' => $course, 'course_page' => $page ] ), [
                'title' => $title,
            ]);

        $response->assertSuccessful();

        $this->assertDatabaseCount( 'course_pages', 1 );
        $this->assertCount( 1, $course->coursePages()->get(), 'Page should remain in course' );
        $page->refresh();
        $this->assertEquals( $title, $page->title, 'Title should be updated' );
    }

    public function test_enforces_course_and_page_hierarchy_in_url()
    {
        $user = User::factory()->create();
        $course = Course::factory()->for( $user )->create();
        $course2 = Course::factory()->for( $user )->create();
        $page = CoursePage::factory()->for( $course )->create();

        $title = $this->faker()->sentence( 2 );

        $response = $this->actingAs( $user )
            ->getJson( 
                route( 'api.courses.course_pages.show', [ 'course' => $course2, 'course_page' => $page ] )
            );

        $response->assertNotFound();
    }

    public function test_reorders_course_pages()
    {
        $user = User::factory()->create();
        $course = Course::factory()->for( $user )->create();
        $pages = CoursePage::factory()->for( $course )->createMany([
            [ 'order' => 3 ],
            [ 'order' => 1 ],
            [ 'order' => 4 ],
            [ 'order' => 5 ],
            [ 'order' => 2 ],
        ]);

        $response = $this->actingAs( $user )
            ->postJson( 
                route( 'api.courses.set-page-order', $course ),
                [
                    'pages' => [
                        $pages[0]->id, 
                        $pages[1]->id,
                        $pages[2]->id,
                        $pages[3]->id,
                        $pages[4]->id,
                    ]
                ]
            );

        $response->assertSuccessful();

        foreach( $pages as $index => $page ) {
            $page->refresh();
            $this->assertEquals( $index + 1, $page->order, 'Items should be sorted' );
        }
    }
}
