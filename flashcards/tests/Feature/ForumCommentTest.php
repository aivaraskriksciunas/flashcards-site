<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\ForumComment;

class ForumCommentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_cannot_post_unauthenticated()
    {
        $user = User::factory()->create();
        $post = $this->createForumPost();

        $response = $this->postJson( 
            route( 'api.forum-posts.comments.store', [ 'forum_post' => $post->id ] ),
            [ 'content' => 'Long text' ]
        );
        $response->assertUnauthorized();

        $this->assertDatabaseEmpty( 'forum_comments' ); 
    }

    public function test_creates_comment()
    {
        $user = User::factory()->create();
        $post = $this->createForumPost();

        $response = $this->actingAs( $user )->postJson(
            route( 'api.forum-posts.comments.store', [ 'forum_post' => $post->id ] ),
            [ 'content' => $this->faker->paragraph() ]
        );

        $response->assertSuccessful();
        $this->assertDatabaseCount( 'forum_comments', 1 );
        $this->assertCount( 1, $post->comments, 'Forum post should have comment' );
    }

    public function test_cannot_add_empty_comment()
    {
        $user = User::factory()->create();
        $post = $this->createForumPost();

        $response = $this->actingAs( $user )->postJson(
            route( 'api.forum-posts.comments.store', [ 'forum_post' => $post->id ] ),
            [ 'content' => '' ]
        );

        $response->assertUnprocessable();
        $response = $this->actingAs( $user )->postJson(
            route( 'api.forum-posts.comments.store', [ 'forum_post' => $post->id ] ),
            [  ]
        );

        $response->assertUnprocessable();
    }

    public function test_comments_are_rate_limited() 
    {
        $user = User::factory()->create();
        $post = $this->createForumPost();

        $post_limit = env( 'FORUM_COMMENTS_PER_DAY' );
        for ( $i = 0; $i < $post_limit; $i++ )
        {
            $response = $this->actingAs( $user )->postJson(
                route( 'api.forum-posts.comments.store', [ 'forum_post' => $post->id ] ),
                [ 'content' => $this->faker->paragraph() ]
            );

            $response->assertSuccessful();
        }

        // Response should fail
        $response = $this->actingAs( $user )->postJson(
            route( 'api.forum-posts.comments.store', [ 'forum_post' => $post->id ] ),
            [ 'content' => $this->faker->paragraph() ]
        );
        $response->assertForbidden();

        $this->assertDatabaseCount( 'forum_posts', $post_limit );
    }

    private function createForumPost()
    {
        $user = User::factory()->create();
        $topic = ForumTopic::factory()->create();

        $post = ForumPost::factory()->for( $user )->for( $topic )->create();
        return $post;
    }
}
