<?php

namespace App\Notifications\Application;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplication extends Notification
{
    use Queueable;
    private $application, $name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($application)
    {
      $this->application = $application;
      $applicant         = $application->applicant;
      $this->name        = "{$applicant->firstname} {$applicant->lastname}";
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
      list('id' => $id, 'receiver_id' => $receiver_id, 'service_id' => $service_id, 'user_id' => $user_id) = $this->application;
      return [
        'type'            => 'new_application',
        'application_id'  => $id,
        'user_id'         => $user_id,
        'receiver_id'     => $receiver_id,
        'service_id'      => $service_id,
        'title'           => trans('notify.application.new.title'),
        'message'         => trans('notify.application.new.message', ['name' => $this->name]),
      ];
    }
}
