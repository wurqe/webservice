<?php

namespace App\Notifications\Work;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReview extends Notification
{
    use Queueable;
    private $review, $title, $name, $service_id, $invitation_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($review, $work, $user, $invitation)
    {
      $this->review         = $review;
      $this->service_id     = $work->service_id;
      $this->invitation_id  = $work->invitation_id;
      $this->name           = "{$user->firstname} {$user->lastname}";
      $this->title          = $invitation->service->title;
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
      list('id'      => $id,
        'rating'     => $rating,
        'author_id'  => $user_id
      ) = $this->review;

      return [
        'type'            => 'new_review',
        'review_id'       => $id,
        'invitation_id'   => $this->invitation_id,
        'user_id'         => $user_id,
        'service_id'      => $this->service_id,
        'title'           => trans('notify.work.new_review.title',   ['name' => $this->name]),
        'message'         => trans('notify.work.new_review.message', ['title' => $this->title, 'name' => $this->name, 'rating' => $rating]),
      ];
    }
}
