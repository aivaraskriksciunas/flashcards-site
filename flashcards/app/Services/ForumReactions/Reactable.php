<?php

namespace App\Services\ForumReactions;

use App\Models\ForumReaction;

trait Reactable {

    public function reactions()
    {
        return $this->morphMany( ForumReaction::class, 'reactable' );
    }

    public function countReactions( ForumReactions $reaction )
    {
        return $this->reactions->where( 'type', $reaction )->count();
    }
}