<?php

namespace App\Observers;

use App\Edit;
use App\Notifications\Bid\NewBid;
use App\Notifications\Bid\BidUpdate;

class BidObserver
{
    /**
     * Handle the edit "created" event.
     *
     * @param  \App\Edit  $edit
     * @return void
     */
    public function created(Edit $edit)
    {
      $moderator = $edit->moderator;
      $moderator->notify(new NewBid($edit));
    }

    /**
     * Handle the edit "updated" event.
     *
     * @param  \App\Edit  $edit
     * @return void
     */
    public function updated(Edit $edit)
    {
      if($edit->isDirty('status')){
        $action = $edit->status;
        $user = $action == 'canceled' ? $edit->moderator : $edit->editor;
        $actor = $action == 'canceled' ? $edit->editor : $edit->moderator;
        $name = "{$actor->firstname} {$actor->lastname}";
        $user->notify(new BidUpdate($edit, $name, $action));
      }
    }

    /**
     * Handle the edit "deleted" event.
     *
     * @param  \App\Edit  $edit
     * @return void
     */
    public function deleted(Edit $edit)
    {
        //
    }

    /**
     * Handle the edit "restored" event.
     *
     * @param  \App\Edit  $edit
     * @return void
     */
    public function restored(Edit $edit)
    {
        //
    }

    /**
     * Handle the edit "force deleted" event.
     *
     * @param  \App\Edit  $edit
     * @return void
     */
    public function forceDeleted(Edit $edit)
    {
        //
    }
}
