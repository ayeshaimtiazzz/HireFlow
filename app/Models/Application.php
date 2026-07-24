<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'job_posting_id', 'candidate_id', 'current_stage_id',
        'status', 'applied_at', 'notes',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function currentStage(): BelongsTo
    {
        return $this->belongsTo(PipelineStage::class, 'current_stage_id');
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function scorecards(): HasMany
    {
        return $this->hasMany(Scorecard::class);
    }

    public function stageHistory(): HasMany
    {
        return $this->hasMany(ApplicationStageHistory::class);
    }
}
