<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScorecardTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'name', 'criteria'];

    protected $casts = [
        'criteria' => 'array',
    ];
}
