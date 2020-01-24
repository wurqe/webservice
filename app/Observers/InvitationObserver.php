<?php

namespace App\Observers;

use App\Invitation;
use App\Notifications\Invitation\NewInvitation;

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
      $user = $invitation->user;
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
        //
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
