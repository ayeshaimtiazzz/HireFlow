<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class JobPostingForm extends Component
{
    public ?JobPosting $jobPosting = null;

    public string $title = '';
    public string $description = '';
    public string $employmentType = 'full-time';
    public string $locationType = 'onsite';
    public string $location = '';
    public ?int $salaryMin = null;
    public ?int $salaryMax = null;
    public string $skillsInput = '';
    public ?int $departmentId = null;
    public string $status = 'draft';

    public function mount(?JobPosting $jobPosting = null): void
    {
        abort_unless(Auth::user()->can('create-job'), 403);

        if ($jobPosting && $jobPosting->exists) {
            $this->jobPosting = $jobPosting;
            $this->title = $jobPosting->title ?? '';
            $this->description = $jobPosting->description ?? '';
            $this->employmentType = $jobPosting->employment_type ?? 'full-time';
            $this->locationType = $jobPosting->location_type ?? 'onsite';
            $this->location = $jobPosting->location ?? '';
            $this->salaryMin = $jobPosting->salary_min;
            $this->salaryMax = $jobPosting->salary_max;
            $this->skillsInput = implode(', ', $jobPosting->skills ?? []);
            $this->departmentId = $jobPosting->department_id;
            $this->status = $jobPosting->status ?? 'draft' ;
        }
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'employmentType' => 'required|string',
            'locationType' => 'required|in:remote,onsite,hybrid',
            'location' => 'nullable|string|max:255',
            'salaryMin' => 'nullable|integer|min:0',
            'salaryMax' => 'nullable|integer|min:0',
            'status' => 'required|in:draft,published,closed,archived',
        ]);

        $skills = array_filter(array_map('trim', explode(',', $this->skillsInput)));

        $data = [
            'tenant_id' => Auth::user()->tenant_id,
            'title' => $this->title,
            'description' => $this->description,
            'employment_type' => $this->employmentType,
            'location_type' => $this->locationType,
            'location' => $this->location,
            'salary_min' => $this->salaryMin,
            'salary_max' => $this->salaryMax,
            'skills' => $skills,
            'department_id' => $this->departmentId,
            'status' => $this->status,
            'published_at' => $this->status === 'published' ? now() : null,
            'created_by' => Auth::id(),
        ];

        if ($this->jobPosting) {
            $this->authorize('update', $this->jobPosting);
            $this->jobPosting->update($data);
        } else {
            JobPosting::create($data);
        }

        session()->flash('status', 'Job posting saved!');
        $this->redirect('/jobs', navigate: true);
    }

    public function render()
    {
        return view('livewire.job-posting-form', [
            'departments' => Department::where('tenant_id', Auth::user()->tenant_id)->get(),
        ]);
    }
}
