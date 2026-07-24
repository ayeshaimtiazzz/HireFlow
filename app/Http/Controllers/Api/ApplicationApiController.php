<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobPosting;
use App\Models\PipelineStage;
use Illuminate\Http\Request;

class ApplicationApiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_posting_id' => 'required|integer',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
        ]);

        $tenantId = $request->user()->tenant_id;

        $jobPosting = JobPosting::where('tenant_id', $tenantId)->findOrFail($validated['job_posting_id']);

        $candidate = Candidate::create([
            'tenant_id' => $tenantId,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'source' => 'api',
        ]);

        $firstStage = PipelineStage::where('tenant_id', $tenantId)
            ->where('order_position', 0)
            ->first();

        $application = Application::create([
            'tenant_id' => $tenantId,
            'job_posting_id' => $jobPosting->id,
            'candidate_id' => $candidate->id,
            'current_stage_id' => $firstStage?->id,
            'status' => 'active',
            'applied_at' => now(),
        ]);

        return new ApplicationResource($application->load(['candidate', 'currentStage', 'jobPosting']));
    }

    public function pipeline(Request $request, $jobPostingId)
    {
        $tenantId = $request->user()->tenant_id;

        JobPosting::where('tenant_id', $tenantId)->findOrFail($jobPostingId);

        $applications = Application::where('tenant_id', $tenantId)
            ->where('job_posting_id', $jobPostingId)
            ->with(['candidate', 'currentStage', 'jobPosting'])
            ->get();

        return ApplicationResource::collection($applications);
    }
}
