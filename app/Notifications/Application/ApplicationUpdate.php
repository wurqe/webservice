<?php

namespace App\Notifications\Application;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationUpdate extends Notification
{
    use Queueable;
    private $application, $user, $action, $name, $title;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($application, $user, string $action)
    {
      $this->application  = $application;
      $this->user         = $user;
      $this->name         = "{$user->firstname} {$user->lastname}";
      $this->action       = $action;
      $this->title        = $application->service->title;
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
      list('id' => $id, 'service_id' => $service_id, 'receiver_id' => $receiver_id, 'user_id' => $user_id) = $this->application;
      return [
        'type'            => 'application_attempt',
        'application_id'  => $id,
        'service_id'      => $service_id,
        'receiver_id'     => $receiver_id,
        'user_id'         => $user_id,
        'title'           => trans('notify.application.update.title'),
        'message'         => trans('notify.application.update.message', ['name' => $this->name, 'title' => $this->title, 'action' => $this->action]),
      ];
    }
}
