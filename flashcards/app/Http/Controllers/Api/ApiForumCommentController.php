<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForumComment\Api\CreateForumComment;
use App\Http\Resources\ForumComment\ForumCommentResource;
use App\Models\ForumComment;
use App\Models\ForumPost;
use Illuminate\Http\Request;
use App\Services\ForumReactions\ForumReactionService;
use App\Services\ForumReactions\ForumReactions;
use App\Http\Requests\ForumPost\Api\ReactToForumPost;
use App\Http\Resources\ForumPost\ForumPostReactionResource;

class ApiForumCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( ForumPost $forumPost )
    {
        return ForumCommentResource::collection( $forumPost->comments()->paginate() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( ForumPost $forumPost, CreateForumComment $request )
    {
        $comment = new ForumComment( $request->validated() );
        $comment->user()->associate( $request->user() );
        $forumPost->comments()->save( $comment );
        
        return new ForumCommentResource( $comment );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function reactToForumComment( ForumReactionService $service, ReactToForumPost $request, ForumComment $forumComment )
    {
        $reactionType = ForumReactions::Upvote;
        if ( $request->input( 'reaction' ) == 'downvote' ) {
            $reactionType = ForumReactions::Downvote;
        }

        $service->registerReaction( $forumComment, $request->user(), $reactionType );
        return new ForumPostReactionResource( $forumComment );
    }
}
