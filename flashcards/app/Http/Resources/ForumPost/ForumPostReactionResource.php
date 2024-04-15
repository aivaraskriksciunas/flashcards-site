<?php

namespace App\Http\Resources\ForumPost;

use App\Services\ForumReactions\ForumReactions;
use Illuminate\Http\Resources\Json\JsonResource;

class ForumPostReactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $userReaction = $this->getUserReaction( $request );

        return [
            'upvotes' => $this->countReactions( ForumReactions::Upvote ),
            'downvotes' => $this->countReactions( ForumReactions::Downvote ),
            'user_reaction' => $userReaction,
        ];
    }

    private function getUserReaction( $request )
    {
        if ( !$request->user() ) return null;

        $userReaction = $this->reactions
            ->where( 'user_id', $request->user()->id )
            ->first();

        if ( !$userReaction ) 
        {
            return null;
        }
        
        return $userReaction->type->value;
    }
}
