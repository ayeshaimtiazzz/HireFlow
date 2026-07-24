<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InterviewSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'job_posting_id', 'created_by', 'starts_at', 'ends_at',
        'duration_minutes', 'location', 'meeting_link', 'is_booked',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_booked' => 'boolean',
    ];

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function booking(): HasOne
    {
        return $this->hasOne(InterviewBooking::class, 'slot_id');
    }
}
