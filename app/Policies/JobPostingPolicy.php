<?php

namespace App\Policies;

use App\Models\JobPosting;
use App\Models\User;

class JobPostingPolicy
{
    public function create(User $user): bool
    {
        return $user->can('create-job');
    }

    public function update(User $user, JobPosting $jobPosting): bool
    {
        return $user->tenant_id === $jobPosting->tenant_id
            && ($user->hasRole('company_admin') || $jobPosting->created_by === $user->id);
    }

    public function delete(User $user, JobPosting $jobPosting): bool
    {
        return $user->tenant_id === $jobPosting->tenant_id
            && $user->hasRole('company_admin');
    }
}
