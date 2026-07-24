<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewBooking extends Model
{
    use HasFactory;

    protected $fillable = ['slot_id', 'application_id', 'confirmation_token', 'booked_at'];

    protected $casts = [
        'booked_at' => 'datetime',
    ];

    public function slot(): BelongsTo
    {
        return $this->belongsTo(InterviewSlot::class, 'slot_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
