<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Company $company): bool
    {
        return $user->workspace_id === $company->workspace_id;
    }

    public function update(User $user, Company $company): bool
    {
        return $user->workspace_id === $company->workspace_id;
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->workspace_id === $company->workspace_id;
    }
}
