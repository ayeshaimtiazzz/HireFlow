<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Candidate extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'tenant_id', 'first_name', 'last_name', 'email', 'phone',
        'linkedin_url', 'portfolio_url', 'resume_path', 'parsed_data', 'source',
    ];

    protected $casts = [
        'parsed_data' => 'array',
    ];

    public function toSearchableArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'tenant_id' => $this->tenant_id,
            'skills' => $this->parsed_data['detected_skills'] ?? [],
        ];
    }

    public function getResumeDownloadUrlAttribute(): ?string
    {
        if (! $this->resume_path) {
            return null;
        }

        $client = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'endpoint' => env('AWS_URL'),
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $command = $client->getCommand('GetObject', [
            'Bucket' => env('AWS_BUCKET'),
            'Key' => $this->resume_path,
        ]);

        $request = $client->createPresignedRequest($command, '+30 minutes');

        return (string) $request->getUri();
    }
}
