<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Department $department): bool
    {
        return $user->branch_id === $department->branch_id;
    }

    public function update(User $user, Department $department): bool
    {
        return $user->branch_id === $department->branch_id;
    }

    public function delete(User $user, Department $department): bool
    {
        return $user->branch_id === $department->branch_id;
    }
}
