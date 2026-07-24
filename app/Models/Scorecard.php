<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Scorecard extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'application_id', 'scorecard_template_id', 'submitted_by',
        'ratings', 'overall_rating', 'decision', 'submitted_at',
    ];

    protected $casts = [
        'ratings' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function scorecardTemplate(): BelongsTo
    {
        return $this->belongsTo(ScorecardTemplate::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['decision', 'overall_rating']);
    }
}
