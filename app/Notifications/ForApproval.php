<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ForApproval extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $copy_request;
    protected $type;
    protected $typedata;
    public function __construct($copy_request,$type,$typedata)
    {
        //
        $this->copy_request = $copy_request;
        $this->type = $type;
        $this->typedata = $typedata;
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
                    ->subject('For Approval Notification')
                    ->greeting('Good day,')
                    ->line('Request for Approval.')
                    ->line('Reference Number : '.$this->type.str_pad($this->copy_request->id, 5, '0', STR_PAD_LEFT))
                    ->line('Control Code : '.$this->copy_request->control_code)
                    ->line('Title : '.$this->copy_request->title)
                    ->line('Requestor : '.$this->copy_request->user->name)
                    ->line('Type of Request : '.$this->typedata)
                    ->line('Please click the button provided for faster transaction')
                    ->action('Pending For Approval', url('/for-approval'))
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
