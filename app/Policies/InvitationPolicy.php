<?php

namespace App\Policies;

use App\Invitation;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any invitations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the invitation.
     *
     * @param  \App\User  $user
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function view(User $user, Invitation $invitation)
    {
        //
    }

    /**
     * Determine whether the user can create invitations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the invitation.
     *
     * @param  \App\User  $user
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function update(User $user, Invitation $invitation)
    {
      return $invitation->user_id == $user->id
          || $invitation->service->user_id == $user->id ;
    }

    /**
     * Determine whether the user can delete the invitation.
     *
     * @param  \App\User  $user
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function delete(User $user, Invitation $invitation)
    {
        //
    }

    /**
     * Determine whether the user can restore the invitation.
     *
     * @param  \App\User  $user
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function restore(User $user, Invitation $invitation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the invitation.
     *
     * @param  \App\User  $user
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function forceDelete(User $user, Invitation $invitation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the invitation.
     *
     * @param  \App\User  $user
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function hire(User $user, Invitation $invitation)
    {
      return $user->id == $invitation->user_id;
    }
}
