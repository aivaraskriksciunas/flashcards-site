<?php

namespace App\Policies;

use App\Models\ForumComment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ForumCommentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function before( User $user, string $ability ): bool|null
    {
        if ( $user->isAdmin() ) {
            return true;
        }
    
        return null;
    }

    public function delete( User $user, ForumComment $comment ) 
    {
        return $user->id == $comment->user_id;
    }
}
