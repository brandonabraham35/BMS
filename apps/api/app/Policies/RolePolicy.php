<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Role $role): bool
    {
        return $user->workspace_id === $role->workspace_id;
    }

    public function update(User $user, Role $role): bool
    {
        return $user->workspace_id === $role->workspace_id;
    }

    public function delete(User $user, Role $role): bool
    {
        if ($role->is_system) {
            return false;
        }
        return $user->workspace_id === $role->workspace_id;
    }
}
