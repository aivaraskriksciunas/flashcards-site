<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Quiz;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class QuizPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Check if the current user can view this quiz
     *
     * @param User $user
     * @param Quiz $quiz
     * @return void
     */
    public function view( User $user, Quiz $quiz )
    {
        if ( $user->is_admin ) return Response::allow();

        return $quiz->user() == $user ? Response::allow() : Response::denyAsNotFound();
    }
}
