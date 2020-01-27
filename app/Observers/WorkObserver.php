<?php

namespace App\Observers;

use App\Work;
use App\Notifications\Work\WorkStart;
use App\Notifications\Work\WorkUpdate;
use Notification;

class WorkObserver
{
    /**
     * Handle the work "created" event.
     *
     * @param  \App\Work  $work
     * @return void
     */
    public function created(Work $work)
    {
      $invitation = $work->invitation;
      $title      = $work->invitation->service->title;
      $user       = $invitation->receiver;
      $users      = $user->whereIn('id', [$invitation->user_id, $invitation->receiver_id])->get();
      Notification::send($users, new WorkStart($work, $title));
    }

    /**
     * Handle the work "updated" event.
     *
     * @param  \App\Work  $work
     * @return void
     */
    public function updated(Work $work)
    {
      if ($work->isDirty('completed_at')) {
        $invitation = $work->invitation;
        $title      = $work->invitation->service->title;
        $user       = $invitation->receiver;
        $users      = $user->whereIn('id', [$invitation->user_id, $invitation->receiver_id])->get();
        Notification::send($users, new WorkUpdate($work, $title));
      }
    }

    /**
     * Handle the work "deleted" event.
     *
     * @param  \App\Work  $work
     * @return void
     */
    public function deleted(Work $work)
    {
        //
    }

    /**
     * Handle the work "restored" event.
     *
     * @param  \App\Work  $work
     * @return void
     */
    public function restored(Work $work)
    {
        //
    }

    /**
     * Handle the work "force deleted" event.
     *
     * @param  \App\Work  $work
     * @return void
     */
    public function forceDeleted(Work $work)
    {
        //
    }
}
