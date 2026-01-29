<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovedDateEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $document;
    protected $approver;
    public function __construct($document,$approver)
    {
        $this->document = $document;
        $this->approver = $approver;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply-dms@gmail.com')
                    ->subject('Edit of approval date')
                    ->view('email.approved_date')
                    ->with(['document' => $this->document, 'approver' => $this->approver]);
    }
}
