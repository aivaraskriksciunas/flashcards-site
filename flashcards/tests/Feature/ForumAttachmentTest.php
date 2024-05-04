<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Deck;
use App\Models\ForumAttachment;
use App\Models\ForumPost;
use App\Models\ForumTopic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForumAttachmentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private ForumTopic $topic; 

    private User $user;

    public function test_assigns_attachment()
    {
        $deck = Deck::factory()->for( $this->user )->create();
        $course = Course::factory()->for( $this->user )->create();

        $response = $this->actingAs( $this->user )->postJson( route( 'api.forum-posts.store' ), [
            'title' => $this->faker()->sentence( 4 ),
            'content' => $this->faker()->text(),
            'forum_topic' => $this->topic->slug,
            'attachments' => [
                [ 'type' => 'deck', 'id' => $deck->id, 'title' => $deck->name ],
                [ 'type' => 'course', 'id' => $course->id, 'title' => $course->title ],
            ]
        ] );

        $response->assertSuccessful();
        $post = ForumPost::first();
        $this->assertEquals( 2, $post->attachments()->count(), 'Should have attachments' );
    }

    public function test_validate_incorrect_fields()
    {
        $deck = Deck::factory()->for( $this->user )->create();
        $course = Course::factory()->for( $this->user )->create();

        // Missing type field
        $response = $this->actingAs( $this->user )->postJson( route( 'api.forum-posts.store' ), [
            'title' => $this->faker()->sentence( 4 ),
            'content' => $this->faker()->text(),
            'forum_topic' => $this->topic->slug,
            'attachments' => [
                [ 'id' => $deck->id, 'title' => $deck->name ],
                [ 'type' => 'course', 'id' => $course->id, 'title' => $course->title ],
            ]
        ] );
        $response->assertUnprocessable();

        // Missing ID field
        $response = $this->actingAs( $this->user )->postJson( route( 'api.forum-posts.store' ), [
            'title' => $this->faker()->sentence( 4 ),
            'content' => $this->faker()->text(),
            'forum_topic' => $this->topic->slug,
            'attachments' => [
                [ 'type' => 'deck', 'id' => $deck->id, 'title' => $deck->name ],
                [ 'type' => 'course', 'title' => $course->title ],
            ]
        ] );
        $response->assertUnprocessable();

        // Wrong type field
        $response = $this->actingAs( $this->user )->postJson( route( 'api.forum-posts.store' ), [
            'title' => $this->faker()->sentence( 4 ),
            'content' => $this->faker()->text(),
            'forum_topic' => $this->topic->slug,
            'attachments' => [
                [ 'type' => 'deckxz', 'id' => $deck->id, 'title' => $deck->name ],
                [ 'type' => 'course', 'id' => $course->id, 'title' => $course->title ],
            ]
        ] );
        $response->assertUnprocessable();

        // No title field
        $response = $this->actingAs( $this->user )->postJson( route( 'api.forum-posts.store' ), [
            'title' => $this->faker()->sentence( 4 ),
            'content' => $this->faker()->text(),
            'forum_topic' => $this->topic->slug,
            'attachments' => [
                [ 'type' => 'deckxz', 'id' => $deck->id, 'title' => $deck->name ],
                [ 'type' => 'course', 'id' => $course->id ],
            ]
        ] );
        $response->assertUnprocessable();

        $this->assertEquals( 0, ForumPost::count(), 'Should have no saved posts' );
        $this->assertEquals( 0, ForumAttachment::count(), 'Should have no saved attachments' );
    }

    public function test_does_not_assign_invalid_attachment()
    {
        // Test with foreign deck
        $response = $this->actingAs( $this->user )->postJson( route( 'api.forum-posts.store' ), [
            'title' => $this->faker()->sentence( 4 ),
            'content' => $this->faker()->text(),
            'forum_topic' => $this->topic->slug,
            'attachments' => [
                [ 'type' => 'deck', 'id' => 754, 'title' => 'Hey hey deck' ],
                [ 'type' => 'course', 'id' => $this->faker->uuid(), 'title' => 'Hey hey' ],
            ]
        ] );

        $response->assertSuccessful();
        $post = ForumPost::first();
        $this->assertEquals( 0, $post->attachments()->count(), 'Should have no attachments' );
    }

    public function test_does_not_assign_non_owner_attachment()
    {
        $another_user = User::factory()->create();
        $deck = Deck::factory()->for( $another_user )->create();
        $course = Course::factory()->for( $another_user )->create();

        // Test with foreign course
        $response = $this->actingAs( $this->user )->postJson( route( 'api.forum-posts.store' ), [
            'title' => $this->faker()->sentence( 4 ),
            'content' => $this->faker()->text(),
            'forum_topic' => $this->topic->slug,
            'attachments' => [
                [ 'type' => 'course', 'id' => $course->id, 'title' => $course->title ],
            ]
        ] );

        $response->assertSuccessful();
        $post = ForumPost::first();
        $this->assertEquals( 0, $post->attachments()->count(), 'Should not have attachments' );

        // Test with foreign deck
        $response = $this->actingAs( $this->user )->postJson( route( 'api.forum-posts.store' ), [
            'title' => $this->faker()->sentence( 4 ),
            'content' => $this->faker()->text(),
            'forum_topic' => $this->topic->slug,
            'attachments' => [
                [ 'type' => 'deck', 'id' => $deck->id, 'title' => $deck->name ],
            ]
        ] );

        $response->assertSuccessful();
        $post = ForumPost::first();
        $this->assertEquals( 0, $post->attachments()->count(), 'Should not have attachments' );
    }

    public function test_does_not_attach_same_twice()
    {
        $deck = Deck::factory()->for( $this->user )->create();

        $response = $this->actingAs( $this->user )->postJson( route( 'api.forum-posts.store' ), [
            'title' => $this->faker()->sentence( 4 ),
            'content' => $this->faker()->text(),
            'forum_topic' => $this->topic->slug,
            'attachments' => [
                [ 'type' => 'deck', 'id' => $deck->id, 'title' => $deck->name ],
                [ 'type' => 'deck', 'id' => $deck->id, 'title' => $deck->name ],
            ]
        ] );

        $response->assertSuccessful();
        $post = ForumPost::first();
        $this->assertEquals( 1, $post->attachments()->count(), 'Should have no double attachments' );
    }

    protected function afterRefreshingDatabase()
    {
        $this->topic = ForumTopic::factory()->create();
        $this->user = User::factory()->create();
    }
}
