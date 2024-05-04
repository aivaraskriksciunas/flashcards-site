<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\ForumComment;
use App\Models\User;
use App\Models\ForumPost;
use App\Models\ForumReaction;
use App\Models\ForumTopic;
use App\Services\ForumReactions\ForumReactions;

class ForumCommentReactionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_vote_unauthenticated()
    {
        $comment = $this->createForumComment();

        $response = $this->postJson( route( 'api.react-to-forum-comment', $comment->id ), [
            'reaction' => 'upvote'
        ]);
        
        $response->assertUnauthorized();
    }

    public function test_vote_is_registered()
    {
        $user = User::factory()->create();
        $comment = $this->createForumComment();

        $response = $this->actingAs( $user )
        ->postJson( route( 'api.react-to-forum-comment', $comment->id ), [
            'reaction' => 'upvote'
        ]);
        
        $response->assertSuccessful();
        $this->assertDatabaseCount( 'forum_reactions', 1 );

        $reaction = ForumReaction::first();
        $this->assertEquals( $user->id, $reaction->user_id );
        $this->assertEquals( $comment->id, $reaction->reactable_id );
        $this->assertEquals( ForumReactions::Upvote, $reaction->type );
    }

    public function test_negative_vote_is_registered()
    {
        $user = User::factory()->create();
        $comment = $this->createForumComment();

        $response = $this->actingAs( $user )
        ->postJson( route( 'api.react-to-forum-comment', $comment->id ), [
            'reaction' => 'downvote'
        ]);
        
        $response->assertSuccessful();
        $this->assertDatabaseCount( 'forum_reactions', 1 );

        $reaction = ForumReaction::first();
        $this->assertEquals( $user->id, $reaction->user_id );
        $this->assertEquals( $comment->id, $reaction->reactable_id );
        $this->assertEquals( ForumReactions::Downvote, $reaction->type );
    }

    public function test_vote_with_bad_data()
    {
        $user = User::factory()->create();
        $comment = $this->createForumComment();

        $response = $this->actingAs( $user )
        ->postJson( route( 'api.react-to-forum-comment', $comment->id ), [
            'reaction' => 'aaa'
        ]);
        
        $response->assertUnprocessable();
        $this->assertDatabaseCount( 'forum_reactions', 0 );
    }

    public function test_voting_twice_cancels_vote()
    {
        $user = User::factory()->create();
        $comment = $this->createForumComment();

        // First vote
        $response = $this->actingAs( $user )
        ->postJson( route( 'api.react-to-forum-comment', $comment->id ), [
            'reaction' => 'upvote'
        ]);
        $response->assertSuccessful();
        $this->assertDatabaseCount( 'forum_reactions', 1 );
        $reaction = ForumReaction::first();
        $this->assertEquals( $user->id, $reaction->user_id );
        $this->assertEquals( $comment->id, $reaction->reactable_id );
        $this->assertEquals( ForumReactions::Upvote, $reaction->type );

        // Second vote
        $response = $this->actingAs( $user )
        ->postJson( route( 'api.react-to-forum-comment', $comment->id ), [
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
        $comment = $this->createForumComment();

        // First vote
        $response = $this->actingAs( $user )
        ->postJson( route( 'api.react-to-forum-comment', $comment->id ), [
            'reaction' => 'upvote'
        ]);
        $response->assertSuccessful();
        $this->assertDatabaseCount( 'forum_reactions', 1 );

        // Second vote
        $response = $this->actingAs( $user )
        ->postJson( route( 'api.react-to-forum-comment', $comment->id ), [
            'reaction' => 'upvote'
        ]);
        $response->assertSuccessful();
        $this->assertDatabaseCount( 'forum_reactions', 0 );
    }

    private function createForumComment()
    {
        $user = User::factory()->create();
        $topic = ForumTopic::factory()->create();

        $post = ForumPost::factory()->for( $user )->for( $topic )->create();
        $comment = ForumComment::factory()->for( $user )->for( $post )->create();
        return $comment;
    }
}
