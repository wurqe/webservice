<?php

namespace App\Policies;

use App\ServiceApplication;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any service applications.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the service application.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceApplication  $serviceApplication
     * @return mixed
     */
    public function view(User $user, ServiceApplication $serviceApplication)
    {
        //
    }

    /**
     * Determine whether the user can create service applications.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the service application.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceApplication  $serviceApplication
     * @return mixed
     */
    public function update(User $user, ServiceApplication $serviceApplication)
    {
      return $serviceApplication->user_id == $user->id
          || $serviceApplication->receiver_id == $user->id ;
    }

    /**
     * Determine whether the user can delete the service application.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceApplication  $serviceApplication
     * @return mixed
     */
    public function delete(User $user, ServiceApplication $serviceApplication)
    {
        //
    }

    /**
     * Determine whether the user can restore the service application.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceApplication  $serviceApplication
     * @return mixed
     */
    public function restore(User $user, ServiceApplication $serviceApplication)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the service application.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceApplication  $serviceApplication
     * @return mixed
     */
    public function forceDelete(User $user, ServiceApplication $serviceApplication)
    {
        //
    }
}
