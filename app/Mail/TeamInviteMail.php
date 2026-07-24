<?php

namespace App\Mail;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Tenant $tenant, public string $signedUrl, public string $role) {}

    public function build()
    {
        return $this->subject("You've been invited to join {$this->tenant->name} on HireFlow")
            ->view('emails.team-invite');
    }
}
