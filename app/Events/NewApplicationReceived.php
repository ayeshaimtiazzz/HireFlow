<?php

namespace App\Events;

use App\Models\Application;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewApplicationReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Application $application) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('tenant.' . $this->application->tenant_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'candidateName' => $this->application->candidate->first_name . ' ' . $this->application->candidate->last_name,
            'jobTitle' => $this->application->jobPosting->title,
        ];
    }
}
