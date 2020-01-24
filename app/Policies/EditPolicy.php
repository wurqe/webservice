<?php

namespace App\Policies;

use App\Edit;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EditPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any edits.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the edit.
     *
     * @param  \App\User  $user
     * @param  \App\Edit  $edit
     * @return mixed
     */
    public function view(User $user, Edit $edit)
    {
        //
    }

    /**
     * Determine whether the user can create edits.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the edit.
     *
     * @param  \App\User  $user
     * @param  \App\Edit  $edit
     * @return mixed
     */
    public function update(User $user, Edit $edit)
    {
      return $user->id == $edit->moderator_id || $edit->editor_id == $user->id;
    }

    /**
     * Determine whether the user can delete the edit.
     *
     * @param  \App\User  $user
     * @param  \App\Edit  $edit
     * @return mixed
     */
    public function delete(User $user, Edit $edit)
    {
        //
    }

    /**
     * Determine whether the user can restore the edit.
     *
     * @param  \App\User  $user
     * @param  \App\Edit  $edit
     * @return mixed
     */
    public function restore(User $user, Edit $edit)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the edit.
     *
     * @param  \App\User  $user
     * @param  \App\Edit  $edit
     * @return mixed
     */
    public function forceDelete(User $user, Edit $edit)
    {
        //
    }
}
