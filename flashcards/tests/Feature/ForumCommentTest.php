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
            route( 'api.forum-posts.comments.store', [ 'forum_post' => $post->slug ] ),
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
            route( 'api.forum-posts.comments.store', [ 'forum_post' => $post->slug ] ),
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
            route( 'api.forum-posts.comments.store', [ 'forum_post' => $post->slug ] ),
            [ 'content' => '' ]
        );

        $response->assertUnprocessable();
        $response = $this->actingAs( $user )->postJson(
            route( 'api.forum-posts.comments.store', [ 'forum_post' => $post->slug ] ),
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
                route( 'api.forum-posts.comments.store', [ 'forum_post' => $post->slug ] ),
                [ 'content' => $this->faker->paragraph() ]
            );

            $response->assertSuccessful();
        }

        // Response should fail
        $response = $this->actingAs( $user )->postJson(
            route( 'api.forum-posts.comments.store', [ 'forum_post' => $post->slug ] ),
            [ 'content' => $this->faker->paragraph() ]
        );
        $response->assertForbidden();

        $this->assertDatabaseCount( 'forum_comments', $post_limit );
    }

    public function test_owner_can_delete_comment()
    {
        $user = User::factory()->create();
        $post = $this->createForumPost();
        $comment = ForumComment::factory()->for( $user )->for( $post )->create();

        $response = $this->actingAs( $user )->deleteJson( 
            route( 'api.comments.destroy', $comment )
        );

        $response->assertNoContent();
        $this->assertSoftDeleted( 'forum_comments', [ 'id' => $comment->id ] );
    }

    public function test_admin_can_delete_comment()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $post = $this->createForumPost();
        $comment = ForumComment::factory()->for( $user )->for( $post )->create();

        $response = $this->actingAs( $admin )->deleteJson( 
            route( 'api.comments.destroy', $comment )
        );

        $response->assertNoContent();
        $this->assertSoftDeleted( 'forum_comments', [ 'id' => $comment->id ] );
    }

    public function test_non_owner_cannot_delete()
    {
        $non_owner = User::factory()->create();
        $user = User::factory()->create();
        $post = $this->createForumPost();
        $comment = ForumComment::factory()->for( $user )->for( $post )->create();

        $response = $this->actingAs( $non_owner )->deleteJson( 
            route( 'api.comments.destroy', $comment )
        );

        $response->assertForbidden();
        $this->assertNotSoftDeleted( 'forum_comments', [ 'id' => $comment->id ] );
    }

    private function createForumPost()
    {
        $user = User::factory()->create();
        $topic = ForumTopic::factory()->create();

        $post = ForumPost::factory()->for( $user )->for( $topic )->create();
        return $post;
    }
}
