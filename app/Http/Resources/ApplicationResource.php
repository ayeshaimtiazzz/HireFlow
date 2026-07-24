<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'applied_at' => $this->applied_at?->toIso8601String(),
            'current_stage' => $this->currentStage?->name,
            'job_posting' => [
                'id' => $this->jobPosting->id,
                'title' => $this->jobPosting->title,
            ],
            'candidate' => [
                'first_name' => $this->candidate->first_name,
                'last_name' => $this->candidate->last_name,
                'email' => $this->candidate->email,
            ],
        ];
    }
}
