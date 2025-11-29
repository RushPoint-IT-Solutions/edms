<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestDocumentApproval extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $change_request;
    // protected $user;
    public function __construct($change_request)
    {
        $this->change_request = $change_request;
        // $this->user = $user;
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
            ->subject('Request for Document Approval – '.$this->change_request->title)
            ->view('email.request_document_approval')
            ->with(['change_request' => $this->change_request]);
    }
}
