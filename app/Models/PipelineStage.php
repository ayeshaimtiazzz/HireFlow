<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PipelineStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'job_posting_id', 'name', 'order_position',
        'is_default', 'requires_scorecard', 'auto_email_template_id',
    ];
}
