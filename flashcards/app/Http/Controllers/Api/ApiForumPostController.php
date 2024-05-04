<?php

namespace App\Http\Controllers\Api;

use App\Enums\ForumPostAttachmentType;
use Illuminate\Http\Request;
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
use App\Models\Course;
use App\Models\Deck;
use App\Models\ForumAttachment;
use App\Services\DataTable\DataTable;
use App\Services\ForumReactions\ForumReactions;
use App\Services\ForumReactions\ForumReactionService;
use Illuminate\Foundation\Http\FormRequest;

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

        $this->saveForumPostAttachments( $request, $post );

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
    public function getTopicList( Request $request ) 
    {
        $dt = new DataTable([ 'title' ], searchable:[ 'title' ], max_page_size:100 );
        $dt->applyUserFilters( ForumTopic::orderBy( 'title' ), $request );

        return ForumTopicResource::collection( 
            $dt->getQuery()->get()
        );
    }

    public function getForumTopic( ForumTopic $forumTopic )
    {
        return new ForumTopicResource( $forumTopic );
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


    private function saveForumPostAttachments( FormRequest $request, ForumPost $post )
    {
        foreach ( $request->validated( 'attachments', [] ) as $attachment )
        {
            $attachable = null;

            // Check that this attachment has all the necessary fields
            if ( $attachment['type'] == ForumPostAttachmentType::Deck->value )
            {
                $attachable = Deck::where( 'user_id', $request->user()->id )
                    ->where( 'id', $attachment['id'] )
                    ->first();
            }
            else if ( $attachment['type'] == ForumPostAttachmentType::Course->value )
            {
                $attachable = Course::where( 'user_id', $request->user()->id )
                    ->where( 'id', $attachment['id'] )
                    ->first();
            }

            if ( $attachable == null ) continue;

            // Check if this item wasn't already attached
            $has_attachment = $attachable->attachments()->where( 'forum_post_id', $post->id )->exists();
            if ( $has_attachment ) continue;

            $obj = new ForumAttachment([ 'title' => $attachment['title'] ]);
            $obj->forumPost()->associate( $post );
            $obj->attachable()->associate( $attachable );
            $obj->save();
        }   
    }
}
