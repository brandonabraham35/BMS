<?php

namespace App\Policies;

use App\Models\OrganizationPolicy;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicyPolicy
{
    use HandlesAuthorization;

    public function view(User $user, OrganizationPolicy $policy): bool
    {
        return $user->workspace_id === $policy->workspace_id;
    }

    public function update(User $user, OrganizationPolicy $policy): bool
    {
        return $user->workspace_id === $policy->workspace_id;
    }

    public function delete(User $user, OrganizationPolicy $policy): bool
    {
        return $user->workspace_id === $policy->workspace_id;
    }
}
