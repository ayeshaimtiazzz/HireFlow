<?php

namespace App\Jobs;

use App\Models\Webhook;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(
        public int $tenantId,
        public string $event,
        public array $payload,
    ) {}

    public function handle(): void
    {
        $webhooks = Webhook::where('tenant_id', $this->tenantId)
            ->where('is_active', true)
            ->get()
            ->filter(fn ($w) => in_array($this->event, $w->events ?? []));

        foreach ($webhooks as $webhook) {
            $body = json_encode(['event' => $this->event, 'data' => $this->payload]);
            $signature = hash_hmac('sha256', $body, $webhook->secret);

            try {
                $response = Http::withHeaders([
                    'X-HireFlow-Signature' => $signature,
                    'Content-Type' => 'application/json',
                ])->post($webhook->url, ['event' => $this->event, 'data' => $this->payload]);

                $webhook->update(['last_triggered_at' => now()]);

                Log::info('Webhook sent', ['url' => $webhook->url, 'status' => $response->status()]);
            } catch (\Exception $e) {
                Log::warning('Webhook failed', ['url' => $webhook->url, 'error' => $e->getMessage()]);
                throw $e;
            }
        }
    }
}
