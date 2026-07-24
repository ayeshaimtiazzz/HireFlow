<?php

namespace App\Jobs;

use App\Jobs\SendWebhookJob;
use App\Mail\StageTransitionMail;
use App\Models\Application;
use App\Models\EmailSent;
use App\Models\EmailTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StageTransitionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public int $applicationId,
        public int $newStageId,
    ) {}

    public function handle(): void
    {
        $application = Application::with(['candidate', 'jobPosting', 'currentStage'])
            ->find($this->applicationId);

        if (! $application) {
            return;
        }

        $template = EmailTemplate::where('tenant_id', $application->tenant_id)
            ->where('trigger_stage_id', $this->newStageId)
            ->first();

        $subject = $template->subject ?? "Update on your application for {$application->jobPosting->title}";
        $body = $template->body ?? "Hi {$application->candidate->first_name}, your application has moved to the {$application->currentStage->name} stage.";

        $body = str_replace(
            ['{{candidate_name}}', '{{job_title}}'],
            [$application->candidate->first_name, $application->jobPosting->title],
            $body
        );

        Mail::to($application->candidate->email)->send(new StageTransitionMail($subject, $body));

        EmailSent::create([
            'tenant_id' => $application->tenant_id,
            'application_id' => $application->id,
            'template_id' => $template?->id,
            'subject' => $subject,
            'body' => $body,
            'sent_at' => now(),
            'tracking_token' => Str::random(32),
        ]);

        SendWebhookJob::dispatch($application->tenant_id, 'application.stage_changed', [
            'application_id' => $application->id,
            'candidate_name' => $application->candidate->first_name . ' ' . $application->candidate->last_name,
            'new_stage' => $application->currentStage->name,
        ]);
    }
}
