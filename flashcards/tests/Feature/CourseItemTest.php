<?php

namespace Tests\Feature;

use App\Enums\CoursePageItemType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\CoursePage;
use App\Models\CoursePageItem;

class CourseItemTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_can_create_item()
    {
        $user = User::factory()->create();
        $courses = Course::factory()
            ->for( $user )
            ->has( CoursePage::factory()->count( 3 ) )
            ->count( 5 )
            ->create();
        
        $title = $this->faker()->sentence( 3 );
        $response = $this->actingAs( $user )->postJson(
            route( 'api.courses.course_pages.course_page_items.store', [ $courses[0], $courses[0]->coursePages()->first() ] ),
            [
                'type' => CoursePageItemType::Text,
                'order' => 1,
                'title' => $title,
                'content' => $this->faker->text()
            ]
        );

        $response->assertCreated();
        $this->assertDatabaseCount( 'course_page_items', 1 );
        $item = CoursePageItem::first();
        $this->assertEquals( $title, $item->title );
        $this->assertEquals( $courses[0]->coursePages()->first()->id, $item->course_page_id, 'Should be assigned to the correct course page' );
    }

    public function test_can_create_with_empty_title()
    {
        $user = User::factory()->create();
        $courses = Course::factory()
            ->for( $user )
            ->has( CoursePage::factory()->count( 3 ) )
            ->count( 5 )
            ->create();
        
        $text = $this->faker->text();
        $response = $this->actingAs( $user )->postJson(
            route( 'api.courses.course_pages.course_page_items.store', [ $courses[0], $courses[0]->coursePages()->first() ] ),
            [
                'type' => CoursePageItemType::Text,
                'order' => 1,
                'title' => '',
                'content' => $text
            ]
        );

        $response->assertCreated();
        $this->assertDatabaseCount( 'course_page_items', 1 );
        $item = CoursePageItem::first();
        $this->assertEquals( '', $item->title );
        $this->assertEquals( $text, $item->content );
    }

    public function test_can_update_item()
    {
        $user = User::factory()->create();
        $course = Course::factory()
            ->for( $user )
            ->create();
        $page = $course->coursePages()->save( CoursePage::factory()->make() );
        $item = CoursePageItem::factory()
            ->for( $page )
            ->count( 5 )
            ->create()[0];
        
        $title = $this->faker()->sentence( 3 );
        $content = $this->faker()->text();
        $response = $this->actingAs( $user )->patchJson(
            route( 'api.courses.course_pages.course_page_items.update', [ $course, $page, $item ] ),
            [
                'order' => 1,
                'title' => $title,
                'content' => $content,
            ]
        );

        $response->assertSuccessful();
        $this->assertDatabaseCount( 'course_page_items', 5 );
        $item->refresh();
        $this->assertEquals( $title, $item->title, 'Title should be updated' );
        $this->assertEquals( $content, $item->content, 'Content should be updated' );
    }

    public function test_ensure_correct_url_params()
    {
        $user = User::factory()->create();
        $courses = Course::factory()
            ->for( $user )
            ->has( 
                CoursePage::factory()
                    ->count( 3 )
                    ->has( 
                        CoursePageItem::factory()->count( 3 ) 
                    ) 
                )
            ->count( 5 )->create();

        $course = $courses[0];
        $page = $course->coursePages()->first();
        $item = $page->coursePageItems()->first();

        // Test: correct everything
        $response = $this->actingAs( $user )->getJson(
            route( 
                'api.courses.course_pages.course_page_items.show',
                [ $course, $page, $item ] 
            )
        );
        $response->assertSuccessful();

        // Test: incorrect course
        $course = $courses[1];
        $response = $this->actingAs( $user )->getJson(
            route( 
                'api.courses.course_pages.course_page_items.show',
                [ $course, $page, $item ] 
            )
        );
        $response->assertNotFound();

        // Test: incorrect page
        $course = $courses[0];
        $page = $courses[1]->coursePages()->first();
        $response = $this->actingAs( $user )->getJson(
            route( 
                'api.courses.course_pages.course_page_items.show',
                [ $course, $page, $item ] 
            )
        );
        $response->assertNotFound();

        // Test: incorrect page and course
        $course = $courses[2];
        $page = $course->coursePages()->first();
        $response = $this->actingAs( $user )->getJson(
            route( 
                'api.courses.course_pages.course_page_items.show',
                [ $course, $page, $item ] 
            )
        );
        $response->assertNotFound();
    }

    public function test_sanitizes_html()
    {
        $user = User::factory()->create();
        $course = Course::factory()
            ->for( $user )
            ->create();
        $page = CoursePage::factory()->for( $course )->create();
        
        $text = 'Hello World! <script>alert("Hello XSS")</script><b onclick="danger()">Bolded text</b> because <a href="https://google.com" onclick="danger()">Link!</a>bold tag is allowed.<em>This tag is never actually closed.';
        $response = $this->actingAs( $user )->postJson(
            route( 'api.courses.course_pages.course_page_items.store', [ $course, $page ] ),
            [
                'type' => CoursePageItemType::Text,
                'order' => 1,
                'title' => $this->faker()->sentence( 3 ),
                'content' => $text,
            ]
        );

        $response->assertCreated();
        $this->assertDatabaseCount( 'course_page_items', 1 );
        $item = CoursePageItem::first();
        $this->assertStringNotContainsString( '<script>', $item->content, 'Should not contain not allowed tags' );
        $this->assertStringContainsString( '<b>', $item->content, 'Should contain allowed tags' );
        $this->assertStringNotContainsString( '<em>', $item->content, 'Should not contain non closed tags' );
        $this->assertEquals( 'Hello World! <b>Bolded text</b> because <a href="https://google.com">Link!</a>bold tag is allowed.This tag is never actually closed.', $item->content );
    }

    public function test_can_reorder_items()
    {
        $user = User::factory()->create();
        $course = Course::factory()->for( $user )->create();
        $page = CoursePage::factory()->for( $course )->create();
        $items = CoursePageItem::factory()->for( $page )->createMany([
            [ 'order' => 2 ],
            [ 'order' => 1 ],
            [ 'order' => 5 ],
            [ 'order' => 3 ],
            [ 'order' => 4 ]
        ]);

        $response = $this->actingAs( $user )
            ->postJson( 
                route( 'api.courses.course_pages.set-page-item-order', [ $course, $page ] ),
                [
                    'items' => [
                        $items[0]->id, 
                        $items[1]->id,
                        $items[2]->id,
                        $items[3]->id,
                        $items[4]->id,
                    ]
                ]
            );

        $response->assertSuccessful();

        foreach( $items as $index => $item ) {
            $item->refresh();
            $this->assertEquals( $index + 1, $item->order, 'Items should be sorted' );
        }
    }

    public function test_items_are_shown_in_order()
    {
        $user = User::factory()->create();
        $course = Course::factory()->for( $user )->create();
        $page = CoursePage::factory()->for( $course )->create();
        $items = CoursePageItem::factory()->for( $page )->createMany([
            [ 'order' => 2 ],
            [ 'order' => 1 ],
            [ 'order' => 5 ],
            [ 'order' => 3 ],
            [ 'order' => 4 ]
        ]);

        $response = $this->actingAs( $user )
            ->getJson( route( 'api.courses.course_pages.show', [ $course, $page ] ), );

        $response->assertSuccessful();

        $items = collect( $response->json()['items'] );
        $items = $items->pluck( 'order' );
        $ordered_items = $items->sort();
        $this->assertEquals( $ordered_items->join( '' ), $items->join( '' ), 'Items should be served in the right order.' );
    }
}
