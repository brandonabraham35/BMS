<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BranchPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Branch $branch): bool
    {
        return $user->company_id === $branch->company_id;
    }

    public function update(User $user, Branch $branch): bool
    {
        return $user->company_id === $branch->company_id;
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->company_id === $branch->company_id;
    }
}
