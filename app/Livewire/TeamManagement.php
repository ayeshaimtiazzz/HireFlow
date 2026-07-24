<?php

namespace App\Livewire;

use App\Jobs\InviteTeamMemberJob;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TeamManagement extends Component
{
    public string $inviteEmail = '';
    public string $inviteRole = 'recruiter';
    public bool $inviteSent = false;

    public function invite(): void
    {
        $this->validate([
            'inviteEmail' => 'required|email',
            'inviteRole' => 'required|in:recruiter,hiring_manager,company_admin',
        ]);

        InviteTeamMemberJob::dispatch(
            tenantId: Auth::user()->tenant_id,
            inviteEmail: $this->inviteEmail,
            role: $this->inviteRole,
        );

        $this->inviteSent = true;
        $this->reset('inviteEmail');
    }

    public function render()
    {
        $teamMembers = \App\Models\User::where('tenant_id', Auth::user()->tenant_id)->get();

        return view('livewire.team-management', ['teamMembers' => $teamMembers]);
    }
}
