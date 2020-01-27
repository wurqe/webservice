<?php

namespace App\Observers;

use App\ServiceApplication;
use App\Notifications\Application\NewApplication;
use App\Notifications\Application\ApplicationUpdate;

class ServiceApplicationObserver
{
    /**
     * Handle the service application "created" event.
     *
     * @param  \App\ServiceApplication  $serviceApplication
     * @return void
     */
    public function created(ServiceApplication $serviceApplication)
    {
      $user = $serviceApplication->receiver;
      $user->notify(new NewApplication($serviceApplication));
    }

    /**
     * Handle the service application "updated" event.
     *
     * @param  \App\ServiceApplication  $serviceApplication
     * @return void
     */
    public function updated(ServiceApplication $serviceApplication)
    {
      if($serviceApplication->isDirty('status')){
        $action = $serviceApplication->status;
        $user = $action == 'canceled' ? $serviceApplication->receiver : $serviceApplication->applicant;
        $user->notify(new ApplicationUpdate($serviceApplication, $user, $action));
      }
    }

    /**
     * Handle the service application "deleted" event.
     *
     * @param  \App\ServiceApplication  $serviceApplication
     * @return void
     */
    public function deleted(ServiceApplication $serviceApplication)
    {
        //
    }

    /**
     * Handle the service application "restored" event.
     *
     * @param  \App\ServiceApplication  $serviceApplication
     * @return void
     */
    public function restored(ServiceApplication $serviceApplication)
    {
        //
    }

    /**
     * Handle the service application "force deleted" event.
     *
     * @param  \App\ServiceApplication  $serviceApplication
     * @return void
     */
    public function forceDeleted(ServiceApplication $serviceApplication)
    {
        //
    }
}
