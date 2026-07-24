<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSent extends Model
{
    use HasFactory;

    protected $table = 'email_sents';

    protected $fillable = [
        'tenant_id', 'application_id', 'template_id', 'subject', 'body',
        'sent_at', 'opened_at', 'opened_count', 'tracking_token',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
    ];
}
