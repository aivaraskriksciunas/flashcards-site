<?php 

namespace App\Services\ForumReactions;

use App\Models\ForumComment;
use App\Models\ForumPost;
use App\Models\ForumReaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ForumReactionService 
{
    /**
     * Updates user reaction for the specified post
     *
     * @param ForumPost $post
     * @param User $user
     * @param ForumReactions $reactionType
     * @return ForumPostReaction|null
     */
    public function registerReaction( ForumPost|ForumComment $post, User $user, ForumReactions $reactionType ) : ForumReaction|null
    {
        // Check if this reaction already exists
        $reaction = $this->getReaction( $post, $user );

        if ( $reaction && $reaction->type != $reactionType )
        {
            // Opposite reaction exists, change it
            $reaction->type = $reactionType->value;
            $reaction->save();

            return $this->getReaction( $post, $user );
        }
        else if ( $reaction )
        {
            // Reaction exists, delete it.
            $reaction->delete();
            return null;
        }

        $reaction = new ForumReaction();
        $reaction->user()->associate( $user );
        $reaction->reactable()->associate( $post );
        $reaction->type = $reactionType;
        $reaction->save();

        return $reaction;
    }

    public function getReaction( ForumPost|ForumComment $post, User $user )
    {
        return $post->reactions()->where( 'user_id', $user->id )->first();
    }
}