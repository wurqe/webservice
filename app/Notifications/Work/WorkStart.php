<?php

namespace App\Notifications\Work;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkStart extends Notification
{
    use Queueable;
    private $title, $work;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($work, $title)
    {
      $this->work = $work;
      $this->title = $title;
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
      list('id' => $id, 'invitation_id' => $invitation_id, 'service_id' => $service_id) = $this->work;
      return [
        'type'            => 'work_started',
        'work_id'         => $id,
        'invitation_id'   => $invitation_id,
        'service_id'      => $service_id,
        'title'           => trans('notify.work.started.title'),
        'message'         => trans('notify.work.started.message', ['title' => $this->title]),
      ];
    }
}
