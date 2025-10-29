<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class NewPolicy extends Notification
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
            ->subject('Approved Notification')
            ->greeting('Good day,')
            ->line('You may now view the new approved document.')
            ->line('Title : '.$this->copy_request->title)
            ->line('Reference Number : '.$this->type.str_pad($this->copy_request->id, 5, '0', STR_PAD_LEFT))
            ->line('Type of Request : '.$this->typedata)
            ->line(new HtmlString('<span><i>Please note that a filled-out <a href="https://edms.wsystem.online/document_attachments/FR-BPD-015%20Acknowledgment%20Form-lc.pdf" target="_blank">WGI-FR-BPD-003 Acknowledgement Form</a> shall be sent to BPD within 30 days after receipt of this email as proof of roll-out</i></span>'))
            
            ->action('PDF File', url($this->copy_request->pdf_copy))
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
