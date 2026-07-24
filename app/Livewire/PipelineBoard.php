<?php

namespace App\Livewire;

use App\Jobs\StageTransitionJob;
use App\Models\Application;
use App\Models\ApplicationStageHistory;
use App\Models\JobPosting;
use App\Models\PipelineStage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PipelineBoard extends Component
{
    public JobPosting $jobPosting;

    public function mount(JobPosting $jobPosting): void
    {
        abort_unless(
            Auth::user()->tenant_id === $jobPosting->tenant_id,
            403
        );
        $this->jobPosting = $jobPosting;
    }

    public function moveApplication($applicationId, $newStageId): void
    {
        $application = Application::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($applicationId);

        $oldStageId = $application->current_stage_id;

        $application->update(['current_stage_id' => $newStageId]);

        ApplicationStageHistory::create([
            'application_id' => $application->id,
            'from_stage_id' => $oldStageId,
            'to_stage_id' => $newStageId,
            'moved_by' => Auth::id(),
            'moved_at' => now(),
        ]);

        StageTransitionJob::dispatch($application->id, $newStageId);
    }

    public function render()
    {
        // Eager load everything up front — this is the fix for the N+1 problem.
        // Without ->with([...]) here, every card would trigger 2-3 extra queries each.
        $stages = PipelineStage::where('tenant_id', Auth::user()->tenant_id)
            ->where(function ($q) {
                $q->where('job_posting_id', $this->jobPosting->id)
                  ->orWhereNull('job_posting_id');
            })
            ->orderBy('order_position')
            ->get();

        $applications = Application::where('job_posting_id', $this->jobPosting->id)
            ->with(['candidate', 'currentStage'])
            ->get()
            ->groupBy('current_stage_id');

        return view('livewire.pipeline-board', [
            'stages' => $stages,
            'applicationsByStage' => $applications,
        ]);
    }
}
