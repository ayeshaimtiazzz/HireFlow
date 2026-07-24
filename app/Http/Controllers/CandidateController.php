<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationStageHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function show(Request $request, $tenant, $application)
    {
        $applicationModel = Application::where('tenant_id', Auth::user()->tenant_id)
            ->with(['candidate', 'currentStage', 'jobPosting'])
            ->find($application);

        if (! $applicationModel) {
            abort(404, 'Application not found for this tenant');
        }

        $stageHistory = ApplicationStageHistory::where('application_id', $applicationModel->id)
            ->with(['fromStage', 'toStage', 'movedBy'])
            ->latest('moved_at')
            ->get();

        return view('candidates.show', ['application' => $applicationModel, 'stageHistory' => $stageHistory]);
    }
}
