<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForumPost\Api\ReactToForumPost;
use App\Http\Requests\ForumPost\Api\StoreForumPost;
use App\Http\Requests\ForumPost\Api\UpdateForumPost;
use App\Http\Resources\ForumPost\ForumPostResource;
use App\Http\Resources\ForumPost\ForumPostDetailResource;
use App\Http\Resources\ForumPost\ForumPostReactionResource;
use App\Models\ForumPost;
use App\Models\ForumTopic;
use App\Http\Resources\ForumTopic\ForumTopicResource;
use App\Services\ForumReactions\ForumReactions;
use App\Services\ForumReactions\ForumReactionService;

class ApiForumPostController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreForumPost  $request
     * @return \Illuminate\Http\Response
     */
    public function store( StoreForumPost $request )
    {
        $topic = ForumTopic::where( 'slug', $request->input( 'forum_topic' ) )->first();
        
        $post = new ForumPost( $request->validated() );
        $post->forumTopic()->associate( $topic );
        $request->user()->forumPosts()->save( $post );

        return $post;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ForumPost  $forumPost
     * @return \Illuminate\Http\Response
     */
    public function show( ForumPost $forum_post )
    {
        return new ForumPostDetailResource( $forum_post );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateForumPost  $request
     * @param  \App\Models\ForumPost  $forumPost
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateForumPost $request, ForumPost $forumPost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ForumPost  $forumPost
     * @return \Illuminate\Http\Response
     */
    public function destroy(ForumPost $forumPost)
    {
        //
    }


    /**
     * Returns a list of topics to the user
     *
     * @return void
     */
    public function getTopicList() 
    {
        return ForumTopicResource::collection( 
            ForumTopic::orderBy( 'title' )->get()
        );
    }

    public function getPostList( ?ForumTopic $forumTopic )
    {
        $posts = ForumPost::orderBy( 'created_at', 'desc' );

        if ( $forumTopic->exists ) {
            $posts = $forumTopic->forumPosts()->orderBy( 'created_at', 'desc' );
        }

        return ForumPostResource::collection(
            $posts->paginate( 8 )
        );
    }

    public function reactToForumPost( ForumReactionService $service, ReactToForumPost $request, ForumPost $forumPost )
    {
        $reactionType = ForumReactions::Upvote;
        if ( $request->input( 'reaction' ) == 'downvote' ) {
            $reactionType = ForumReactions::Downvote;
        }

        $service->registerReaction( $forumPost, $request->user(), $reactionType );
        return new ForumPostReactionResource( $forumPost );
    }
}
