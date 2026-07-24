<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobPosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'department_id', 'title', 'description',
        'employment_type', 'location_type', 'location',
        'salary_min', 'salary_max', 'skills', 'status',
        'published_at', 'closes_at', 'created_by',
    ];

    protected $casts = [
        'skills' => 'array',
        'published_at' => 'datetime',
        'closes_at' => 'datetime',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function pipelineStages(): HasMany
    {
        return $this->hasMany(PipelineStage::class, 'job_posting_id');
    }
}
