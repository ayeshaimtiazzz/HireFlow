<?php

namespace App\Mail;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Tenant $tenant) {}

    public function build()
    {
        return $this->subject("Welcome to HireFlow, {$this->tenant->name}!")
            ->view('emails.welcome');
    }
}
