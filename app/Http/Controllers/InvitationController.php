<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function accept(Request $request)
    {
        if (! $request->hasValidSignature()) {
            return view('invitation-expired');
        }

        return view('livewire.accept-invitation', [
            'tenantId' => $request->query('tenant'),
            'email' => $request->query('email'),
            'role' => $request->query('role'),
        ]);
    }
}
