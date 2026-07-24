<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;

class JobPostingController extends Controller
{
    public function index()
    {
        $jobPostings = JobPosting::where('tenant_id', Auth::user()->tenant_id)
            ->withCount('applications')
            ->latest()
            ->get();

        return view('job-postings.index', compact('jobPostings'));
    }
}
