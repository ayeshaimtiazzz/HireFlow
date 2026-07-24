<?php

namespace App\Jobs;

use App\Mail\WelcomeMail;
use App\Models\PipelineStage;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CreateTenantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public string $companyName,
        public string $plan,
        public string $adminName,
        public string $adminEmail,
        public string $adminPassword,
    ) {}

    public function handle(): void
    {
        $slug = str($this->companyName)->slug();

        $tenant = Tenant::create([
            'name' => $this->companyName,
            'slug' => $slug,
            'domain' => "{$slug}.hireflow.test",
            'plan' => $this->plan,
            'status' => 'active',
        ]);

        $tenant->makeCurrent();

        $admin = User::create([
            'name' => $this->adminName,
            'email' => $this->adminEmail,
            'password' => Hash::make($this->adminPassword),
            'tenant_id' => $tenant->id,
        ]);
        $admin->assignRole('company_admin');

        $stageNames = ['Applied', 'Screening', 'Phone Interview', 'Technical', 'Final Interview', 'Offer', 'Hired', 'Rejected'];
        foreach ($stageNames as $i => $stageName) {
            PipelineStage::create([
                'tenant_id' => $tenant->id,
                'job_posting_id' => null,
                'name' => $stageName,
                'order_position' => $i,
                'is_default' => true,
                'requires_scorecard' => in_array($stageName, ['Technical', 'Final Interview']),
            ]);
        }

        Mail::to($admin->email)->send(new WelcomeMail($admin, $tenant));
    }
}
