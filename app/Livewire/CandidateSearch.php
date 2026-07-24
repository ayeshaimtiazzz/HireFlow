<?php

namespace App\Livewire;

use App\Models\Application;
use App\Models\Candidate;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CandidateSearch extends Component
{
    public string $query = '';

    public function render()
    {
        $results = collect();

        if (strlen($this->query) >= 2) {
            $candidates = Candidate::search($this->query)
                ->where('tenant_id', Auth::user()->tenant_id)
                ->take(10)
                ->get();

            $results = $candidates->map(function ($candidate) {
                $application = Application::where('candidate_id', $candidate->id)->latest()->first();
                $candidate->application_id = $application?->id;
                return $candidate;
            });
        }

        return view('livewire.candidate-search', ['results' => $results]);
    }
}
