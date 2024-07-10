<?php

namespace App\Policies;

use App\Enums\CourseAccessLinkType;
use App\Exceptions\CourseAccessLink\AccountRequired;
use App\Exceptions\CourseAccessLink\AnonymousAccountRequired;
use App\Models\CourseAccessLink;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CourseAccessLinkPolicy
{

    public function before( User $user )
    {
        if ( $user->isAdmin() ) return true;

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view( ?User $user, CourseAccessLink $courseAccessLink ): Response
    {
        if ( $courseAccessLink->type === CourseAccessLinkType::Unrestricted )
        {
            // No restrictions for anonymous links
            return Response::allow();
        }

        if ( $courseAccessLink->type === CourseAccessLinkType::RequireName && $user == null )
        {
            throw new AnonymousAccountRequired();
        }

        if ( $courseAccessLink->type === CourseAccessLinkType::RequireAccount )
        {
            if ( $user == null || $user->isAnonymous() ) 
            {
                throw new AccountRequired();
            }
        }

        if ( $courseAccessLink->type === CourseAccessLinkType::AssignedOnly )
        {
            // Deny to anonymous users or to users that are not assigned to this course
            if ( !$user ) return Response::denyAsNotFound();

            if ( !$courseAccessLink->course->assignedUsers->contains( $user ) ) {
                return Response::denyAsNotFound();
            }
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update( User $user, CourseAccessLink $courseAccessLink ): Response
    {
        if ( $courseAccessLink->user_created == false ) {
            return Response::deny();
        }

        return $courseAccessLink->user->id == $user->id ? 
            Response::allow() : 
            Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete( User $user, CourseAccessLink $courseAccessLink ): Response
    {
        if ( $courseAccessLink->user_created == false ) {
            return Response::deny();
        }

        return $courseAccessLink->user->id == $user->id ? 
            Response::allow() : 
            Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create an anonymous account to view this course
     *
     * @param User|null $user
     * @param CourseAccessLink $courseAccessLink
     * @return Response
     */
    public function create_anonymous_account( ?User $user, CourseAccessLink $courseAccessLink ): Response 
    {
        if ( $courseAccessLink->type == CourseAccessLinkType::RequireName && $user == null ) 
        {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CourseAccessLink $courseAccessLink)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CourseAccessLink $courseAccessLink)
    {
        //
    }
}
