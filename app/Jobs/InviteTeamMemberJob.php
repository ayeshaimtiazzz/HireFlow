<?php

namespace App\Jobs;

use App\Mail\TeamInviteMail;
use App\Models\Tenant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class InviteTeamMemberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public int $tenantId,
        public string $inviteEmail,
        public string $role,
    ) {}

    public function handle(): void
    {
        $tenant = Tenant::find($this->tenantId);

        $signedUrl = URL::temporarySignedRoute(
            'invitation.accept',
            now()->addHours(72),
            [
                'tenant' => $tenant->id,
                'email' => $this->inviteEmail,
                'role' => $this->role,
            ]
        );

        Mail::to($this->inviteEmail)->send(new TeamInviteMail($tenant, $signedUrl, $this->role));
    }
}
