<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public string $message
    ) {
    }


    public function build(): Mailable
    {
        return $this->markdown('emails.contact');
    }
}
