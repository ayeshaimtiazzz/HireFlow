<?php

namespace App\Livewire;

use App\Events\ScorecardSubmitted;
use App\Models\Application;
use App\Models\Scorecard;
use App\Models\ScorecardTemplate;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ScoreCardForm extends Component
{
    public Application $application;
    public ?ScorecardTemplate $template = null;
    public array $ratings = [];
    public string $decision = 'undecided';
    public bool $submitted = false;

    public function mount(Application $application): void
    {
        abort_unless($application->tenant_id === Auth::user()->tenant_id, 403);
        $this->application = $application;

        $this->template = ScorecardTemplate::where('tenant_id', Auth::user()->tenant_id)->first();

        if (! $this->template) {
            $this->template = ScorecardTemplate::create([
                'tenant_id' => Auth::user()->tenant_id,
                'name' => 'Standard Interview Scorecard',
                'criteria' => [
                    ['name' => 'Technical Skills', 'type' => 'rating', 'weight' => 1],
                    ['name' => 'Communication', 'type' => 'rating', 'weight' => 1],
                    ['name' => 'Culture Fit', 'type' => 'rating', 'weight' => 1],
                    ['name' => 'Additional Notes', 'type' => 'text', 'weight' => 0],
                ],
            ]);
        }

        foreach ($this->template->criteria as $criterion) {
            $this->ratings[$criterion['name']] = $criterion['type'] === 'rating' ? null : '';
        }
    }

    public function submit(): void
    {
        $this->validate([
            'decision' => 'required|in:proceed,reject,undecided',
        ]);

        $ratingValues = array_filter($this->ratings, fn ($v, $k) => is_numeric($v), ARRAY_FILTER_USE_BOTH);
        $overallRating = count($ratingValues) > 0 ? round(array_sum($ratingValues) / count($ratingValues), 1) : null;

        $scorecard = Scorecard::create([
            'application_id' => $this->application->id,
            'scorecard_template_id' => $this->template->id,
            'submitted_by' => Auth::id(),
            'ratings' => $this->ratings,
            'overall_rating' => $overallRating,
            'decision' => $this->decision,
            'submitted_at' => now(),
        ]);

        event(new ScorecardSubmitted($scorecard));

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.score-card-form');
    }
}
