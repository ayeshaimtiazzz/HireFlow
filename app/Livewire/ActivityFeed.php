<?php

namespace App\Livewire;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ActivityFeed extends Component
{
    public Application $application;
    public int $tenantId;
    protected $listeners = ['echo-private:tenant.{tenantId},NewApplicationReceived' => '$refresh'];

    public function mount(Application $application): void
    {
        $this->application = $application;
        $this->tenantId = $application->tenant_id;
    }

    public function render()
    {
        $stageHistory = $this->application->stageHistory()->with(['fromStage', 'toStage', 'movedBy'])->latest('moved_at')->get();
        $scorecards = $this->application->scorecards()->with('submittedBy')->latest('submitted_at')->get();

        $events = collect();

        foreach ($stageHistory as $h) {
            $events->push([
                'type' => 'stage_move',
                'text' => ($h->fromStage->name ?? 'Start') . ' → ' . $h->toStage->name,
                'by' => $h->movedBy->name ?? 'System',
                'at' => $h->moved_at,
            ]);
        }

        foreach ($scorecards as $s) {
            $events->push([
                'type' => 'scorecard',
                'text' => 'Submitted scorecard — ' . ucfirst($s->decision),
                'by' => $s->submittedBy->name,
                'at' => $s->submitted_at,
            ]);
        }

        $events = $events->sortByDesc('at')->values();

        return view('livewire.activity-feed', ['events' => $events]);
    }
}
