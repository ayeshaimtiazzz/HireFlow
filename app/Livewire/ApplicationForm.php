<?php

namespace App\Livewire;

use App\Jobs\ParseResumeJob;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobPosting;
use App\Models\PipelineStage;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ApplicationForm extends Component
{
    use WithFileUploads;

    public JobPosting $jobPosting;

    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';
    public string $phone = '';
    public string $linkedinUrl = '';
    public $resume;
    public bool $submitted = false;

    public function mount(JobPosting $jobPosting): void
    {
        $this->jobPosting = $jobPosting;
    }

    public function submitApplication(): void
    {
        $this->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'linkedinUrl' => 'nullable|url',
            'resume' => 'required|file|mimes:pdf,docx|max:5120',
        ]);

        $path = $this->resume->store('resumes', 's3');

        $candidate = Candidate::create([
            'tenant_id' => $this->jobPosting->tenant_id,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'linkedin_url' => $this->linkedinUrl,
            'resume_path' => $path,
            'source' => 'careers page',
        ]);

        $firstStage = PipelineStage::where('tenant_id', $this->jobPosting->tenant_id)
            ->where(function ($q) {
                $q->where('job_posting_id', $this->jobPosting->id)
                  ->orWhereNull('job_posting_id');
            })
            ->orderBy('order_position')
            ->first();

        $application = Application::create([
            'tenant_id' => $this->jobPosting->tenant_id,
            'job_posting_id' => $this->jobPosting->id,
            'candidate_id' => $candidate->id,
            'current_stage_id' => $firstStage?->id,
            'status' => 'active',
            'applied_at' => now(),
        ]);

        ParseResumeJob::dispatch($candidate->id);
        event( new \App\Events\NewApplicationReceived($application));
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.application-form');
    }
}
