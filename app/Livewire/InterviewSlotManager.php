<?php

namespace App\Livewire;

use App\Models\Application;
use App\Models\InterviewSlot;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class InterviewSlotManager extends Component
{
    public Application $application;
    public string $date = '';
    public string $time = '';
    public int $durationMinutes = 30;
    public string $location = '';

    public function mount(Application $application): void
    {
        abort_unless($application->tenant_id === Auth::user()->tenant_id, 403);
        $this->application = $application;
    }

    public function createSlot(): void
    {
        $this->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'durationMinutes' => 'required|integer|min:15',
        ]);

        $startsAt = \Carbon\Carbon::parse("{$this->date} {$this->time}");

        InterviewSlot::create([
            'tenant_id' => Auth::user()->tenant_id,
            'job_posting_id' => $this->application->job_posting_id,
            'created_by' => Auth::id(),
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addMinutes($this->durationMinutes),
            'duration_minutes' => $this->durationMinutes,
            'location' => $this->location,
        ]);

        $this->reset(['date', 'time', 'location']);
    }

    public function getBookingUrl(): string
    {
        $tenant = Tenant::find($this->application->tenant_id);

        return URL::temporarySignedRoute(
            'interview.book',
            now()->addDays(7),
            [
                'tenant' => $tenant->slug,
                'application' => $this->application->id,
            ]
        );
    }

    public function render()
    {
        $slots = InterviewSlot::where('job_posting_id', $this->application->job_posting_id)
            ->where('is_booked', false)
            ->orderBy('starts_at')
            ->get();

        return view('livewire.interview-slot-manager', ['slots' => $slots]);
    }
}
