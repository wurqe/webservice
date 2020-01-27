<?php

namespace App\Notifications\Invitation;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationUpdate extends Notification
{
    use Queueable;
    private $invitation, $user, $action, $name, $title;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($invitation, string $action)
    {
      $this->invitation = $invitation;
      $this->user       = $invitation->user;
      $this->name       = "{$this->user->firstname} {$this->user->lastname}";
      $this->action     = $action;
      $this->title      = $invitation->service->title;
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
      return [
        'invitation_id'   => $this->invitation->id,
        'service_id'      => $this->invitation->service_id,
        'receiver_id'     => $this->invitation->receiver_id,
        'user_id'         => $this->invitation->user_id,
        'title'           => trans('notify.invitation.update.title'),
        'message'         => trans('notify.invitation.update.message', ['name' => $this->name, 'action' => $this->action, 'title' => $this->title]),
        ];
    }
}
