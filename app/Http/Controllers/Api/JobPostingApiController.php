<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobPostingResource;
use App\Jobs\SendWebhookJob;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobPostingApiController extends Controller
{
    public function index(Request $request)
    {
        $jobPostings = JobPosting::where('tenant_id', $request->user()->tenant_id)
            ->withCount('applications')
            ->latest()
            ->paginate(20);

        return JobPostingResource::collection($jobPostings);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'employment_type' => 'required|string',
            'location_type' => 'required|in:remote,onsite,hybrid',
            'location' => 'nullable|string',
            'salary_min' => 'nullable|integer',
            'salary_max' => 'nullable|integer',
            'skills' => 'nullable|array',
            'status' => 'required|in:draft,published,closed,archived',
        ]);

        $validated['tenant_id'] = $request->user()->tenant_id;
        $validated['created_by'] = $request->user()->id;
        $validated['published_at'] = $validated['status'] === 'published' ? now() : null;

        $jobPosting = JobPosting::create($validated);

        SendWebhookJob::dispatch($jobPosting->tenant_id, 'job.created', [
            'job_id' => $jobPosting->id,
            'title' => $jobPosting->title,
        ]);

        return new JobPostingResource($jobPosting);
    }

    public function show(Request $request, $id)
    {
        $jobPosting = JobPosting::where('tenant_id', $request->user()->tenant_id)->findOrFail($id);

        return new JobPostingResource($jobPosting);
    }
}
