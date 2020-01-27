<?php

namespace App\Notifications\Wallet;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WalletUpdate extends Notification
{
    use Queueable;
    private $action, $name, $otherName, $work_id, $service_id, $amount, $currency;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($action, $name, $otherName, $amount, $work_id, $service_id)
    {
      $this->action      = $action;
      $this->name        = $name;
      $this->otherName   = $otherName;
      $this->work_id     = $work_id;
      $this->service_id  = $service_id;
      $this->amount      = $amount;
      $this->currency    = '$';
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
        'type'            => 'wallet_update',
        'work_id'         => $this->work_id,
        'service_id'      => $this->service_id,
        'title'           => trans("notify.wallet.updated.{$this->action}.title",   ['name' => $this->name, 'action' => $this->action]),
        'message'         => trans("notify.wallet.updated.{$this->action}.message", [
          'name'          => $this->name,   'otherName' => $this->otherName,
          'action'        => $this->action, 'amount'   => $this->amount, 'currency'  => $this->currency
        ]),
      ];
    }
}
