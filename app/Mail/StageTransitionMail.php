<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StageTransitionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $subjectLine, public string $bodyContent) {}

    public function build()
    {
        return $this->subject($this->subjectLine)->view('emails.stage-transition');
    }
}
