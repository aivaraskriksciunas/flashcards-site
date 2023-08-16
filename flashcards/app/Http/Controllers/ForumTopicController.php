<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForumTopic\CreateForumTopic;
use App\Http\Requests\ForumTopic\EditForumTopic;
use App\Models\ForumTopic;
use Illuminate\Http\Request;

class ForumTopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view( 'forum-topics.index', [ 
            'topics' => ForumTopic::orderBy( 'title' )->get()
        ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view( 'forum-topics.create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( CreateForumTopic $request )
    {
        $topic = ForumTopic::create( $request->validated() );

        return redirect( route( 'forum-topic.show', $topic ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( ForumTopic $forum_topic )
    {
        return view( 'forum-topics.show', [
            'topic' => $forum_topic
        ] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( ForumTopic $forum_topic )
    {
        return view( 'forum-topics.edit', [
            'topic' => $forum_topic,
        ] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( EditForumTopic $request, ForumTopic $forum_topic )
    {
        $forum_topic->update( $request->validated() );
        return redirect( route( 'forum-topic.show', $forum_topic ) );
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
}
