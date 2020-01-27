<?php

namespace App\Notifications\Invitation;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewInvitation extends Notification
{
    use Queueable;

    private $invitation, $user, $title, $name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($invitation)
    {
      $this->invitation = $invitation;
      $this->user       = $invitation->user;
      $this->title      = $invitation->service->title;
      $this->name       = "{$this->user->firstname} {$this->user->lastname}";
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
                    ->line(trans('notify.new.title'))
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
        return [
          'invitation_id'   => $this->invitation->id,
          'service_id'      => $this->invitation->service_id,
          'receiver_id'     => $this->invitation->receiver_id,
          'user_id'         => $this->invitation->user_id,
          'title'           => trans('notify.invitation.new.title'),
          'message'         => trans('notify.invitation.new.message', ['name' => $this->name, 'title' => $this->title]),
        ];
    }
}
