<?php

namespace App\Observers;

use App\Invitation;
use App\Notifications\Invitation\NewInvitation;
use App\Notifications\Invitation\InvitationUpdate;

class InvitationObserver
{
    /**
     * Handle the invitation "created" event.
     *
     * @param  \App\Invitation  $invitation
     * @return void
     */
    public function created(Invitation $invitation)
    {
      $user = $invitation->receiver;
      $user->notify(new NewInvitation($invitation));
    }

    /**
     * Handle the invitation "updated" event.
     *
     * @param  \App\Invitation  $invitation
     * @return void
     */
    public function updated(Invitation $invitation)
    {
      // $invitation->getDirty();
      if($invitation->isDirty('status')){
        $action = $invitation->status;
        $user = $action == 'canceled' ? $invitation->receiver : $invitation->user;
        $user->notify(new InvitationUpdate($invitation, $action));
      }
    }

    /**
     * Handle the invitation "deleted" event.
     *
     * @param  \App\Invitation  $invitation
     * @return void
     */
    public function deleted(Invitation $invitation)
    {
        //
    }

    /**
     * Handle the invitation "restored" event.
     *
     * @param  \App\Invitation  $invitation
     * @return void
     */
    public function restored(Invitation $invitation)
    {
        //
    }

    /**
     * Handle the invitation "force deleted" event.
     *
     * @param  \App\Invitation  $invitation
     * @return void
     */
    public function forceDeleted(Invitation $invitation)
    {
        //
    }
}
