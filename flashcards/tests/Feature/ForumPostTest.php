<?php

namespace Tests\Feature;

use App\Models\ForumTopic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForumPostTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_creates_forum_post()
    {
        $user = User::factory()->create();
        $topic = ForumTopic::factory()->create();

        $response = $this->actingAs( $user )->postJson( '/api/forum-posts', [
            'title' => 'Forum Post',
            'content' => 'Long forum post content',
            'forum_topic' => $topic->slug,
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseCount( 'forum_posts', 1 );
        $this->assertCount( 1, $user->forumPosts()->get(), 'Post should be assigned to user' );
    }

    public function test_cannot_post_unauthenticated() 
    {
        $response = $this->postJson( '/api/forum-posts', [
            'title' => 'Forum Post',
            'content' => 'Long forum post content',
            'forum_topic' => ForumTopic::factory()->create()->slug,
        ]);

        $response->assertUnauthorized( 'Request should be denied' );
    }

    public function test_cannot_post_empty_title() 
    {
        $user = User::factory()->create();

        $response = $this->actingAs( $user )->postJson( '/api/forum-posts', [
            'title' => '',
            'content' => 'Long forum post content',
            'forum_topic' => ForumTopic::factory()->create()->slug,
        ]);

        $response->assertUnprocessable();
        $this->assertDatabaseEmpty( 'forum_posts' );
    }

    public function test_cannot_post_empty_forum_topic() 
    {
        $user = User::factory()->create();

        $response = $this->actingAs( $user )->postJson( '/api/forum-posts', [
            'title' => 'Post title',
            'content' => 'Long forum post content',
        ]);

        $response->assertUnprocessable();
        $this->assertDatabaseEmpty( 'forum_posts' );
    }

    public function test_cannot_post_nonexistant_forum_topic() 
    {
        $user = User::factory()->create();
        ForumTopic::factory( 10 )->create();

        $response = $this->actingAs( $user )->postJson( '/api/forum-posts', [
            'title' => 'Post title',
            'content' => 'Long forum post content',
            'forum_topic' => 10,
        ]);

        $response->assertUnprocessable();
        $this->assertDatabaseEmpty( 'forum_posts' );
    }

    public function test_posts_are_rate_limited() 
    {
        $user = User::factory()->create();
        $topic = ForumTopic::factory()->create();

        $post_limit = env( 'FORUM_POSTS_PER_DAY' );
        for ( $i = 0; $i < $post_limit; $i++ )
        {
            $response = $this->actingAs( $user )->postJson( '/api/forum-posts', [
                'title' => 'Post title',
                'content' => 'Long forum post content',
                'forum_topic' => $topic->slug,
            ]);

            $response->assertSuccessful();
        }

        // Response should fail
        $response = $this->actingAs( $user )->postJson( '/api/forum-posts', [
            'title' => 'Post title',
            'content' => 'Long forum post content',
            'forum_topic' => $topic->slug,
        ]);
        $response->assertForbidden();

        $this->assertDatabaseCount( 'forum_posts', $post_limit );
    }
}
