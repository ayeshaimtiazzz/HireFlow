<?php

namespace App\Livewire;

use App\Models\JobPosting;
use App\Models\Tenant;
use Livewire\Component;

class CareersPage extends Component
{
    public function render()
    {
        $tenant = Tenant::current();

        $jobs = JobPosting::where('tenant_id', $tenant->id)
            ->where('status', 'published')
            ->latest('published_at')
            ->get();

        return view('livewire.careers-page', [
            'tenant' => $tenant,
            'jobs' => $jobs,
        ]);
    }
}
