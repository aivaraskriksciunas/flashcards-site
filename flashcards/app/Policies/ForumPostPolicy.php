<?php

namespace App\Policies;

use App\Exceptions\Quiz\ForumPostRateLimitReached;
use App\Models\ForumPost;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ForumPostPolicy
{
    use HandlesAuthorization;

    public function before( User $user, string $ability ): bool|null
    {
        if ( $user->isAdmin() ) {
            return true;
        }
    
        return null;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ForumPost  $forumPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view( User $user, ForumPost $forumPost )
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create( User $user )
    {
        
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ForumPost  $forumPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ForumPost $forumPost)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ForumPost  $forumPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ForumPost $forumPost)
    {
        return $forumPost->user->id == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ForumPost  $forumPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ForumPost $forumPost)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ForumPost  $forumPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ForumPost $forumPost)
    {
        //
    }
}
