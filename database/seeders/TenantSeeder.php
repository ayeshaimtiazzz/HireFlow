<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\JobPosting;
use App\Models\Candidate;
use App\Models\PipelineStage;
use App\Models\Application;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $companies = ['NovaTech', 'Systems Limited', 'Arbisoft'];

        foreach ($companies as $companyName) {
            $slug = str($companyName)->slug();

            $tenant = Tenant::create([
                'name' => $companyName,
                'slug' => $slug,
                'domain' => "{$slug}.hireflow.test",
                'plan' => 'growth',
                'status' => 'active',
            ]);

            $tenant->makeCurrent();

            $admin = User::create([
                'name' => "{$companyName} Admin",
                'email' => "admin@{$slug}.test",
                'password' => Hash::make('password'),
                'tenant_id' => $tenant->id,
            ]);
            $admin->assignRole('company_admin');

            $recruiter = User::create([
                'name' => "{$companyName} Recruiter",
                'email' => "recruiter@{$slug}.test",
                'password' => Hash::make('password'),
                'tenant_id' => $tenant->id,
            ]);
            $recruiter->assignRole('recruiter');

            $hiringManager = User::create([
                'name' => "{$companyName} Hiring Manager",
                'email' => "manager@{$slug}.test",
                'password' => Hash::make('password'),
                'tenant_id' => $tenant->id,
            ]);
            $hiringManager->assignRole('hiring_manager');

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

            $firstStage = PipelineStage::where('tenant_id', $tenant->id)
                ->where('order_position', 0)
                ->first();

            for ($j = 1; $j <= 2; $j++) {
                $job = JobPosting::create([
                    'tenant_id' => $tenant->id,
                    'title' => $j === 1 ? 'Backend AI Engineer' : 'Frontend Developer',
                    'description' => 'A great opportunity to join our growing team.',
                    'employment_type' => 'full-time',
                    'location_type' => 'hybrid',
                    'location' => 'Islamabad, Pakistan',
                    'salary_min' => 100000,
                    'salary_max' => 200000,
                    'skills' => ['PHP', 'Laravel', 'MySQL'],
                    'status' => 'published',
                    'published_at' => now(),
                    'created_by' => $admin->id,
                ]);

                for ($c = 1; $c <= 10; $c++) {
                    $candidate = Candidate::create([
                        'tenant_id' => $tenant->id,
                        'first_name' => "Candidate{$c}",
                        'last_name' => "ForJob{$j}",
                        'email' => "candidate{$c}job{$j}@{$slug}.test",
                        'source' => 'careers page',
                    ]);

                    Application::create([
                        'tenant_id' => $tenant->id,
                        'job_posting_id' => $job->id,
                        'candidate_id' => $candidate->id,
                        'current_stage_id' => $firstStage?->id,
                        'status' => 'active',
                        'applied_at' => now(),
                    ]);
                }
            }
        }
    }
}
