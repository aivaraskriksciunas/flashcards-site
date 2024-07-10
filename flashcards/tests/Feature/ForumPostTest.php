<?php

namespace Tests\Feature;

use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForumPostTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    
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
        ForumTopic::factory( 10 )->create();
        $user = User::factory()->create();

        $response = $this->actingAs( $user )->postJson( '/api/forum-posts', [
            'title' => 'Post title',
            'content' => 'Long forum post content',
            'forum_topic' => 20,
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

    public function test_owner_can_delete_post()
    {
        $user = User::factory()->create();
        $topic = ForumTopic::factory()->create();
        $post = ForumPost::factory()->for( $topic )->for( $user )->create();

        $response = $this->actingAs( $user )->deleteJson( 
            route( 'api.forum-posts.delete', $post )
        );

        $response->assertNoContent();
        $this->assertSoftDeleted( 'forum_posts', [ 'id' => $post->id ] );
    }

    public function test_admin_can_delete_post()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $topic = ForumTopic::factory()->create();
        $post = ForumPost::factory()->for( $topic )->for( $user )->create();

        $response = $this->actingAs( $admin )->deleteJson( 
            route( 'api.forum-posts.delete', $post )
        );

        $response->assertNoContent();
        $post->refresh();
        $this->assertSoftDeleted( 'forum_posts', [ 'id' => $post->id ] );
    }

    public function test_non_owner_cannot_delete()
    {
        $admin = User::factory()->create();
        $user = User::factory()->create();
        $topic = ForumTopic::factory()->create();
        $post = ForumPost::factory()->for( $topic )->for( $user )->create();

        $response = $this->actingAs( $admin )->deleteJson( 
            route( 'api.forum-posts.delete', $post )
        );

        $response->assertForbidden();
        $post->refresh();
        $this->assertNotSoftDeleted( 'forum_posts', [ 'id' => $post->id ] );
    }

    public function test_creates_unique_slugs()
    {
        $user = User::factory()->create();
        $topic = ForumTopic::factory()->create();

        // Create first forum post
        $response = $this->actingAs( $user )->postJson( '/api/forum-posts', [
            'title' => 'Post title',
            'content' => 'Long forum post content',
            'forum_topic' => $topic->slug,
        ]);

        $response->assertSuccessful();

        // Make a second forum post
        $response = $this->actingAs( $user )->postJson( '/api/forum-posts', [
            'title' => 'Post title',
            'content' => 'Long long forum post content',
            'forum_topic' => $topic->slug,
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseCount( 'forum_posts', 2 );
        $this->assertCount( 0, ForumPost::all()->pluck( 'slug' )->duplicates(), 'Should contain no duplicates' );

        // Delete forum posts
        ForumPost::all()->each( fn ( $a ) => $a->delete() );

        // Make a third forum post
        $response = $this->actingAs( $user )->postJson( '/api/forum-posts', [
            'title' => 'Post title',
            'content' => 'Long long forum post content',
            'forum_topic' => $topic->slug,
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseCount( 'forum_posts', 3 );
        $this->assertCount( 0, ForumPost::all()->pluck( 'slug' )->duplicates(), 'Should contain no duplicates' );
    }
}
