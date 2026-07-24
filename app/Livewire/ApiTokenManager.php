<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ApiTokenManager extends Component
{
    public string $tokenName = '';
    public ?string $newToken = null;

    public function generateToken(): void
    {
        $this->validate(['tokenName' => 'required|string|max:255']);

        $token = Auth::user()->createToken($this->tokenName);
        $this->newToken = $token->plainTextToken;
        $this->reset('tokenName');
    }

    public function revoke($tokenId): void
    {
        Auth::user()->tokens()->where('id', $tokenId)->delete();
    }

    public function render()
    {
        return view('livewire.api-token-manager', [
            'tokens' => Auth::user()->tokens()->latest()->get(),
        ]);
    }
}
