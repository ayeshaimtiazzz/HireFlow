<?php

namespace App\Events;

use App\Models\Scorecard;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScorecardSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Scorecard $scorecard) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('tenant.' . $this->scorecard->application->tenant_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'reviewerName' => $this->scorecard->submittedBy->name,
            'candidateName' => $this->scorecard->application->candidate->first_name,
        ];
    }
}
