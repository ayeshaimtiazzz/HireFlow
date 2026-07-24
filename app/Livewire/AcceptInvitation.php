<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AcceptInvitation extends Component
{
    public int $tenantId;
    public string $email;
    public string $role;
    public string $name = '';
    public string $password = '';
    public string $passwordConfirmation = '';

    public function mount($tenantId, $email, $role)
    {
        $this->tenantId = $tenantId;
        $this->email = $email;
        $this->role = $role;
    }

    public function joinTeam()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'tenant_id' => $this->tenantId,
        ]);
        $user->assignRole($this->role);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function render()
    {
        return view('livewire.accept-invitation');
    }
}
