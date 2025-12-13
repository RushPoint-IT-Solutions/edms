<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovedRequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $change_request;
    public function __construct($change_request)
    {
        $this->change_request = $change_request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('no-reply-dms@gmail.com')
            ->subject('Approved request document')
            ->view('email.approved_request_document')
            ->with(['change_request' => $this->change_request]);
    }
}
