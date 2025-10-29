<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewPreAssessment extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $preAssessment;
    public $prefix;
    public $subject;
    public $type;
    public function __construct($preAssessment, $subject)
    {
        //
        $this->preAssessment = $preAssessment;
        $this->subject = $subject;
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
                    ->subject('Pre Assessment For Approval')
                    ->greeting('Good day,')
                    ->line('Requestor : '.$this->preAssessment->user->name)
                    ->line('Title of Request : '.$this->preAssessment->Title)
                    ->line('Please click the button provided for faster transaction')
                    ->action('Pending For Approval', url('/pre_assessment'))
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
