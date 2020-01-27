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
    public function __construct($invitation, $user, string $action)
    {
      $this->invitation = $invitation;
      $this->user       = $user;
      $this->name       = "{$user->firstname} {$user->lastname}";
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
      $action = $this->action;
      list('id' => $id, 'service_id' => $service_id, 'receiver_id' => $receiver_id, 'user_id' => $user_id) = $this->invitation;
      return [
        'invitation_id'   => $id,
        'service_id'      => $service_id,
        'receiver_id'     => $receiver_id,
        'user_id'         => $user_id,
        'title'           => $action == 'hired' ? trans('notify.service.hired.title') : trans('notify.invitation.update.title'),
        'message'         => $action == 'hired' ? trans('notify.service.hired.message', ['name' => $this->name, 'title' => $this->title]) : trans('notify.invitation.update.message', ['name' => $this->name, 'action' => $action, 'title' => $this->title]),
      ];
    }
}
