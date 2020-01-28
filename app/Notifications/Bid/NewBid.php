<?php

namespace App\Notifications\Bid;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBid extends Notification
{
    use Queueable;
    private $bid, $name, $title;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($bid)
    {
      $this->bid    = $bid;
      $editor       = $bid->editor;
      $service      = $bid->service;
      $this->name   = "{$editor->firstname} {$editor->lastname}";
      // $this->title  = $service->;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
      list('id' => $id, 'edit_type' => $edit_type, 'edit_id' => $edit_id, 'editor_id' => $editor_id) = $this->bid;
      $model = explode("\\", $edit_type)[1];
      return [
        'type'         => 'new_bid',
        'id'           => $id,
        'edit_id'      => $edit_id,
        'edit_type'    => $edit_type,
        'editor_id'    => $editor_id,
        'title'        => trans('notify.bid.new.title',   ['model' => $model]),
        'message'      => trans('notify.bid.new.message', ['model' => $model, 'name' => $this->name, /*'title' => $this->title*/]),
      ];
    }
}
