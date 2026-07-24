<?php

namespace App\Livewire;

use App\Models\Webhook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class WebhookManager extends Component
{
    public string $url = '';
    public array $selectedEvents = [];

    protected array $availableEvents = ['job.created', 'application.stage_changed'];

    public function create(): void
    {
        $this->validate([
            'url' => 'required|url',
            'selectedEvents' => 'required|array|min:1',
        ]);

        Webhook::create([
            'tenant_id' => Auth::user()->tenant_id,
            'url' => $this->url,
            'events' => $this->selectedEvents,
            'secret' => Str::random(32),
            'is_active' => true,
        ]);

        $this->reset(['url', 'selectedEvents']);
    }

    public function toggle($id): void
    {
        $webhook = Webhook::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        $webhook->update(['is_active' => ! $webhook->is_active]);
    }

    public function render()
    {
        return view('livewire.webhook-manager', [
            'webhooks' => Webhook::where('tenant_id', Auth::user()->tenant_id)->get(),
            'availableEvents' => $this->availableEvents,
        ]);
    }
}
