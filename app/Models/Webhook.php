<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'url', 'events', 'secret', 'is_active', 'last_triggered_at'];

    protected $casts = [
        'events' => 'array',
        'last_triggered_at' => 'datetime',
    ];
}
