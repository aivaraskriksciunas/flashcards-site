<?php

namespace Tests\Feature;

use App\Models\ForumPost;
use App\Models\ForumReaction;
use App\Models\ForumTopic;
use App\Models\User;
use App\Services\ForumReactions\ForumReactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForumReactionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_vote_unauthenticated()
    {
        $post = $this->createForumPost();

        $response = $this->postJson( route( 'api.react-to-forum-post', $post->id ), [
            'reaction' => 'upvote'
        ]);
        
        $response->assertUnauthorized();
    }

    public function test_vote_is_registered()
    {
        $user = User::factory()->create();
        $post = $this->createForumPost();

        $response = $this->actingAs( $user )
        ->postJson( route( 'api.react-to-forum-post', $post->id ), [
            'reaction' => 'upvote'
        ]);
        
        $response->assertSuccessful();
        $this->assertDatabaseCount( 'forum_reactions', 1 );

        $reaction = ForumReaction::first();
        $this->assertEquals( $user->id, $reaction->user_id );
        $this->assertEquals( $post->id, $reaction->reactable_id );
        $this->assertEquals( ForumReactions::Upvote, $reaction->type );
    }

    public function test_negative_vote_is_registered()
    {
        $user = User::factory()->create();
        $post = $this->createForumPost();

        $response = $this->actingAs( $user )
        ->postJson( route( 'api.react-to-forum-post', $post->id ), [
            'reaction' => 'downvote'
        ]);
        
        $response->assertSuccessful();
        $this->assertDatabaseCount( 'forum_reactions', 1 );

        $reaction = ForumReaction::first();
        $this->assertEquals( $user->id, $reaction->user_id );
        $this->assertEquals( $post->id, $reaction->reactable_id );
        $this->assertEquals( ForumReactions::Downvote, $reaction->type );
    }

    public function test_vote_with_bad_data()
    {
        $user = User::factory()->create();
        $post = $this->createForumPost();

        $response = $this->actingAs( $user )
        ->postJson( route( 'api.react-to-forum-post', $post->id ), [
            'reaction' => 'aaa'
        ]);
        
        $response->assertUnprocessable();
        $this->assertDatabaseCount( 'forum_reactions', 0 );
    }

    public function test_voting_twice_cancels_vote()
    {
        $user = User::factory()->create();
        $post = $this->createForumPost();

        // First vote
        $response = $this->actingAs( $user )
        ->postJson( route( 'api.react-to-forum-post', $post->id ), [
            'reaction' => 'upvote'
        ]);
        $response->assertSuccessful();
        $this->assertDatabaseCount( 'forum_reactions', 1 );
        $reaction = ForumReaction::first();
        $this->assertEquals( $user->id, $reaction->user_id );
        $this->assertEquals( $post->id, $reaction->reactable_id );
        $this->assertEquals( ForumReactions::Upvote, $reaction->type );

        // Second vote
        $response = $this->actingAs( $user )
        ->postJson( route( 'api.react-to-forum-post', $post->id ), [
            'reaction' => 'downvote'
        ]);
        $response->assertSuccessful();
        $this->assertDatabaseCount( 'forum_reactions', 1 );
        $reaction = ForumReaction::first();
        $this->assertEquals( ForumReactions::Downvote, $reaction->type );
    }

    public function test_voting_twice_replaces_vote()
    {
        $user = User::factory()->create();
        $post = $this->createForumPost();

        // First vote
        $response = $this->actingAs( $user )
        ->postJson( route( 'api.react-to-forum-post', $post->id ), [
            'reaction' => 'upvote'
        ]);
        $response->assertSuccessful();
        $this->assertDatabaseCount( 'forum_reactions', 1 );

        // Second vote
        $response = $this->actingAs( $user )
        ->postJson( route( 'api.react-to-forum-post', $post->id ), [
            'reaction' => 'upvote'
        ]);
        $response->assertSuccessful();
        $this->assertDatabaseCount( 'forum_reactions', 0 );
    }

    private function createForumPost()
    {
        $user = User::factory()->create();
        $topic = ForumTopic::factory()->create();

        $post = ForumPost::factory()->for( $user )->for( $topic )->create();
        return $post;
    }
}
