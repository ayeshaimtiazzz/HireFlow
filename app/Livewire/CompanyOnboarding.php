<?php

namespace App\Livewire;

use App\Jobs\CreateTenantJob;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CompanyOnboarding extends Component
{
    public int $step = 1;

    // Step 1: company details
    public string $companyName = '';
    public string $companySize = '';

    // Step 2: personal account
    public string $adminName = '';
    public string $adminEmail = '';
    public string $adminPassword = '';
    public string $adminPassword_confirmation = '';

    // Step 3: plan
    public string $plan = 'starter';

    public bool $submitted = false;

    public function nextStep(): void
    {
        if ($this->step === 1) {
            $this->validate([
                'companyName' => 'required|string|max:255',
                'companySize' => 'required|string',
            ]);
            $slug = \Illuminate\Support\Str::slug($this->companyName);
            if (\App\Models\Tenant::where('slug', $slug)->exists()){
                 $this->addError('companyName', 'A company with this name already exists. Please choose a different name.');
                 return;
            }
        }

        if ($this->step === 2) {
            $this->validate([
                'adminName' => 'required|string|max:255',
                'adminEmail' => 'required|email|unique:users,email',
                'adminPassword' => 'required|min:8|confirmed',
            ]);
        }

        $this->step++;
    }

    public function previousStep(): void
    {
        $this->step--;
    }

    public function submit(): void
    {
        $this->validate([
            'plan' => 'required|in:starter,growth,enterprise',
        ]);

        CreateTenantJob::dispatchSync(
            companyName: $this->companyName,
            plan: $this->plan,
            adminName: $this->adminName,
            adminEmail: $this->adminEmail,
            adminPassword: $this->adminPassword,
        );

        $tenant = Tenant::where('name', $this->companyName)->latest()->first();

        session()->flash('onboarding_domain', $tenant->domain);
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.company-onboarding');
    }
}
