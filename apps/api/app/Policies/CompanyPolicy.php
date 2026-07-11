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
        return (string) $user->workspace_id === (string) $company->workspace_id;
    }

    public function update(User $user, Company $company): bool
    {
        \Illuminate\Support\Facades\Log::info('CompanyPolicy@update', [
            'user_workspace_id' => $user->workspace_id,
            'company_workspace_id' => $company->workspace_id,
            'match' => (string) $user->workspace_id === (string) $company->workspace_id
        ]);
        return (string) $user->workspace_id === (string) $company->workspace_id;
    }

    public function delete(User $user, Company $company): bool
    {
        return (string) $user->workspace_id === (string) $company->workspace_id;
    }
}
