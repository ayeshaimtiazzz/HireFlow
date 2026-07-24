<?php

namespace App\Livewire;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ConsensusView extends Component
{
    public Application $application;

    public function mount(Application $application): void
    {
        abort_unless($application->tenant_id === Auth::user()->tenant_id, 403);
        $this->application = $application;
    }

    public function render()
    {
        $scorecards = $this->application->scorecards()->with('submittedBy')->get();

        $criteriaNames = collect($scorecards)
            ->flatMap(fn ($s) => array_keys($s->ratings))
            ->unique()
            ->values();

        return view('livewire.consensus-view', [
            'scorecards' => $scorecards,
            'criteriaNames' => $criteriaNames,
        ]);
    }
}
