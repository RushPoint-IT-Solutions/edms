<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Renewal extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $count;
    protected $countOverdue;
    public function __construct($count,$countOverdue)
    {
        //
        $this->count = $count;
        $this->countOverdue = $countOverdue;
        $this->renewal = $count-$countOverdue;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
                    ->greeting('Good Day!')
                    ->subject('For Renewal')
                    ->greeting('Good day,')
                    ->line('This is a friendly reminder that you have a (Permit / License / Certificate) that is subject for renewal.')
                    ->line('For Renewal : '.$this->renewal)
                    ->line('Please click the button provided for faster transaction')
                    ->action('Permits & Licenses', url('/permits'))
                    ->line('Thank you for using our application!');

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
