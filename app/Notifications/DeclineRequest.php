<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DeclineRequest extends Notification
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
    protected $req;
    public function __construct($copy_request,$type,$typedata,$req)
    {
        //
        $this->copy_request = $copy_request;
        $this->type = $type;
        $this->typedata = $typedata;
        $this->req = $req;
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
                ->subject('Declined Request')
                ->greeting('Good day,')
                ->line('We regret to inform you that your request has been Declined. You may view the reason/remarks in the system.')
                ->line('Reference Number : '.$this->type.str_pad($this->copy_request->id, 5, '0', STR_PAD_LEFT))
                ->line('Type of Request : '.$this->typedata)
                ->line('Please click the button provided for faster transaction')
                ->action('Approved Request', url($this->req))
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
