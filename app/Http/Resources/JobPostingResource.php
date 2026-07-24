<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobPostingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'employment_type' => $this->employment_type,
            'location_type' => $this->location_type,
            'location' => $this->location,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'skills' => $this->skills,
            'status' => $this->status,
            'published_at' => $this->published_at?->toIso8601String(),
            'applications_count' => $this->whenCounted('applications'),
        ];
    }
}
