<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Deck;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DeckPolicy
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
     * Bypass all checks and allow admin users all capabilities
     *
     * @param User $user
     * @return boolean|null
     */
    public function before( User $user ) : bool|null
    {
        if ( $user->is_admin ) return true;

        return null;
    }

    /**
     * Determine whether user can view this deck
     *
     * @param User $user
     * @param Deck $deck
     * @return Response
     */
    public function view( User $user, Deck $deck ) : Response 
    {
        return $deck->user_id === $user->id ?
            Response::allow() :
            Response::denyAsNotFound();
    }

    /**
     * Determine whether user can modify this deck
     *
     * @param User $user
     * @param Deck $deck
     * @return Response
     */
    public function update( User $user, Deck $deck ) : Response
    {
        return $deck->user_id === $user->id ?
            Response::allow() :
            Response::denyAsNotFound();
    }
}
